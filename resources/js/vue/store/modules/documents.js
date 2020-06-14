import _ from 'lodash'
import { Map, Set, List } from 'immutable'
import filesize from 'filesize'

const module = {
    namespaced: true,

    modules: {
        upload: {
            namespaced: true,
            state: {
                files: List()
            },
            mutations: {
                addFile(state, fileToAdd) {
                    const files = state.files

                    const containsKey = (files, keyToCheck) => files.find(file => file.key === keyToCheck)
                     
                    if (containsKey(files, fileToAdd.name))  return

                    const data = {
                        key: fileToAdd.name,
                        shared: true,
                        title: fileToAdd.name,
                        file: fileToAdd,
                        fileSizeFormatted: filesize(fileToAdd.size)
                    }

                    state.files = files.push(data)
                },
                removeFile(state, index) {
                    const files = state.files

                    state.files = files.splice(index, 1)
                },
                clearFiles(state) {
                    state.files = List()
                }
            },
            actions: {
                // upload 
                async upload({state, commit, rootState}) {
                    const filesToUpload = state.files
                    const clearFiles = () => commit('clearFiles');

                    // build form
                    const formData = new FormData();
                    for (const file of filesToUpload) {    
                        formData.append('file['+ file.key +']', file.file);
                        formData.append('file_attributes['+ file.key +'][title]', file.title);
                        formData.append('file_attributes['+ file.key +'][shared]', file.shared);
                    }

                    // submit form
                    try {
                        const data = ( await axios.post('/api/documents', formData, { headers: { 'Content-Type': 'multipart/form-data' } }) ).data
                        const successMsg = _.get(data, 'status');
                        clearFiles()
                        return successMsg
                    } catch(e) {
                        const errorCode = _.get(e, 'response.data.code');
                        const errors = _.get(e, 'response.data.errors')

                        // remove files sucessfully uploaded, leaving only failed ones
                        if (errorCode && errorCode === 'D-1') {
                            const keysToKeep = Object.keys(errors);
                            this.state.files = filesToUpload.filter(doc => keysToKeep.includes(doc.key));
                        }

                        throw e
                    }
                }
            }
        },

        search: {
            namespaced: true,
            state: {
                list: List(),
                matchedItems: null,

                selectedSearchTags: Set(),
                suggestedSearchTags: Set(),
            },
            getters: {
                documents: state => {
                    const documents = state.list
                    const matchedItems = state.matchedItems
                    return (!matchedItems) ? documents : documents.filter(d => matchedItems.includes(d.id))
                },
                suggestedSearchTags: state => (filterText) => {
                    return state.suggestedSearchTags
                        .filter(i => (i.toLowerCase().indexOf(filterText.toLowerCase()) !== -1))
                        .toArray()
                }
               
            },
            mutations: {
                setMatchedItems(state, documentIds) {
                    state.matchedItems = documentIds
                },
                setDocuments(state, documents) {
                    state.list = documents
                },
                updateDocument(state, document) {
                    state.list = updateUsingId(state.list, document)
                },
                deleteDocument(state, docId) {
                    state.list = deleteUsingId(state.list, docId)
                },
                setSelectedSearchTags(state, tags) {
                    state.selectedSearchTags = tags
                },
                clearSelectedSearchTags(state) {
                    state.selectedSearchTags = Set()
                    state.matchedItems = null
                },
                setSuggestedSearchTags(state, suggestions) {
                    state.suggestedSearchTags = suggestions
                }
            },
            actions: {
                async selectSearchTags({state, commit, dispatch}, tags) {
                    if (_.get(tags, 'size')) {
                        commit('setSelectedSearchTags', tags)
                        await dispatch('search')
                        await dispatch('fetchSuggestedSearchTags')
                    } else {
                        commit('clearSelectedSearchTags')
                        await dispatch('fetchSuggestedSearchTags')
                    }
                },

                async fetchDocuments({state, commit, rootState}) {
                    const apiFetch = async () => List((await axios.get(`/api/documents`)).data)
                    const setDocuments = (documents) => commit('setDocuments', documents)

                    setDocuments(await apiFetch())
                },

                async fetchSuggestedSearchTags({state, commit, rootState}) {
                    const selectedSearchTags = state.selectedSearchTags

                    const fetchAllTags = async () => {
                        const tags = (await axios.get(`/api/tags`,{ params: { resource_type: "document" } })).data
                        return Set(tags).map(t => t.label)
                    }
                    const fetchAssociatedTagsFor = async (tags) => {
                        const data = (await axios.get(`/api/tag-labels/associated`, { params: { tag_labels: tags } })).data
                        return Set.intersect(Map(data).toSetSeq())
                    }
                    const setSuggestions = (suggestedTags) => commit('setSuggestedSearchTags', suggestedTags)

                    setSuggestions(!selectedSearchTags.size
                        ? await fetchAllTags()
                        : await fetchAssociatedTagsFor(selectedSearchTags.toArray())
                    )
                },

                async search({state, commit, rootState}) {
                    const selectedSearchTags = state.selectedSearchTags

                    const fetchDocuments = async (tags) => {
                        const params = { resource_type: "document", tag_labels: tags }
                        const data = (await axios.get(`/api/tagged-items`, { params:  params })).data
                        return Set(data).map(ti => ti.resource_id)
                    }
                    const setMatchedItems = (items) => commit('setMatchedItems', items)

                    setMatchedItems(await fetchDocuments(selectedSearchTags.toArray()))
                },

                async fetchDocumentDownloadUrl({state, commit, rootState}, documentId) {
                    const downloadData = (await axios.get(`/api/documents/${documentId}/download`)).data
                    return downloadData.download_url
                },

                async deleteDocument({state, commit, rootState}, {document, hardDelete=false}) {
                    const deleteDoc = async (document, hardDelete) => 
                        (await axios.delete(`/api/documents/${document.id}`, { params: { 'really_delete': hardDelete } })).data

                    const updatedDoc = await deleteDoc(document, hardDelete)

                    hardDelete
                        ? commit('deleteDocument', updatedDoc.id)
                        : commit('updateDocument', updatedDoc)
                },

                async restoreDocument({state, commit, rootState}, {document}) {
                    const restoreDoc = async (document) => (await axios.post(`/api/documents/${document.id}/restore`)).data

                    const updatedDoc = await restoreDoc(document)

                    commit('updateDocument', updatedDoc)
                },

                async shareDocument({state, commit, rootState}, {document, share=true}) {
                    const shareDoc = async (document, share) => (await axios.post(`/api/documents/${document.id}/share`, { 'share': share })).data

                    const updatedDoc = await shareDoc(document, share)

                    commit('updateDocument', updatedDoc)
                }
            }
        }
    }
}

const updateUsingId = (items, updated) => items.map(i => (i.id === updated.id) ? updated : i)

const deleteUsingId = (items, deleteId) => items.filter(i => i.id !== deleteId)

export default module