import _ from 'lodash'
import { Map, Set, List } from 'immutable'

const module = {
    namespaced: true,

    state: {
        // locally cached documents
        documents: List(),
        filteredDocumentIds: null
    },

    getters: {
        documentsFiltered: (state) => {
            const documents = state.documents
            const filteredDocumentIds = state.filteredDocumentIds
            
            return (!filteredDocumentIds) ? documents : documents.filter(d => filteredDocumentIds.includes(d.id))
        },
    },

    mutations: {
        setDocuments(state, documents) {
            state.documents = documents
        },
        setFilteredDocumentIds(state, documentIds) {
            state.filteredDocumentIds = documentIds
        },
        addDocument(state, document) {
            state.documents = state.documents.insert(0, document)
        },
        updateDocument(state, document) {
            state.documents = updateUsingId(state.documents, document)
        },
        deleteDocument(state, docId) {
            state.documents = deleteUsingId(state.documents, docId)
        },
        clearFilteredDocumentIds(state) {
            state.filteredDocumentIds = null
        }
    },

    actions: {
        async initialiseDocuments({commit}) {
            const apiFetch = async () => List((await axios.get(`/api/documents`)).data)
            const setDocuments = (documents) => commit('setDocuments', documents)

            const docs = await apiFetch()

            setDocuments(docs)
        },

        async fetchDocumentDownloadUrl({commit}, documentId) {
            const downloadData = (await axios.get(`/api/documents/${documentId}/download`)).data
            
            return downloadData.download_url
        },

        async deleteDocument({commit}, {document, hardDelete=false}) {
            const deleteDoc = async (document, hardDelete) => 
                (await axios.delete(`/api/documents/${document.id}`, { params: { 'really_delete': hardDelete } })).data

            const updatedDoc = await deleteDoc(document, hardDelete)

            hardDelete
                ? commit('deleteDocument', updatedDoc.id)
                : commit('updateDocument', updatedDoc)
        },

        async restoreDocument({commit}, {document}) {
            const restoreDoc = async (document) => (await axios.post(`/api/documents/${document.id}/restore`)).data

            const updatedDoc = await restoreDoc(document)

            commit('updateDocument', updatedDoc)
        },

        async shareDocument({commit}, {document, share=true}) {
            const shareDoc = async (document, share) => (await axios.post(`/api/documents/${document.id}/share`, { 'share': share })).data

            const updatedDoc = await shareDoc(document, share)

            commit('updateDocument', updatedDoc)
        }
    },

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
                    if (containsKey(files, fileToAdd.key))  return
                    state.files = files.push(fileToAdd)
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
                async upload({state, commit}) {
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
                    } catch (e) {
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
                searchTags: Set(),
                suggestedSearchTags: Set(),
            },
            getters: {
                suggestedSearchTags: state => (filterText) => {
                    return state.suggestedSearchTags
                        .filter(i => (i.toLowerCase().indexOf(filterText.toLowerCase()) !== -1))
                        .toArray()
                }
               
            },
            mutations: {
                setSearchTags(state, tags) {
                    state.searchTags = tags
                },
                clearSearchTags(state) {
                    state.searchTags = Set()
                    state.matchedItems = null
                },
                setSuggestedSearchTags(state, suggestions) {
                    state.suggestedSearchTags = suggestions
                },
                clearSuggestedSearchTags(state) {
                    state.suggestedSearchTags = Set()
                }
            },
            actions: {
                async submitSearchTags({commit, dispatch}, tags) {
                    if (_.get(tags, 'size')) {
                        commit('setSearchTags', tags)
                        commit('clearSuggestedSearchTags')
                        await dispatch('initialiseSuggestedSearchTags')
                        await dispatch('search')
                    } else {
                        commit('documents/clearFilteredDocumentIds', null, {root: true})
                        commit('clearSearchTags')
                        await dispatch('initialiseSuggestedSearchTags')
                    }
                },

                async initialiseSuggestedSearchTags({state, commit}) {
                    const searchTags = state.searchTags

                    const fetchAllTags = async () => {
                        const tags = (await axios.get(`/api/tags`,{ params: { resource_type: "document" } })).data
                        return Set(tags).map(t => t.label)
                    }
                    const fetchAssociatedTagsFor = async (tags) => {
                        const data = (await axios.get(`/api/tag-labels/associated`, { params: { tag_labels: tags } })).data
                        return Set.intersect(Map(data).toSetSeq())
                    }
                    const setSuggestions = (suggestedTags) => commit('setSuggestedSearchTags', suggestedTags)

                    setSuggestions(!searchTags.size
                        ? await fetchAllTags()
                        : await fetchAssociatedTagsFor(searchTags.toArray())
                    )
                },

                async search({state, commit}) {
                    const searchTags = state.searchTags

                    const fetchTaggedItem = async (tags) => {
                        const params = { resource_type: "document", tag_labels: tags }
                        const data = (await axios.get(`/api/tagged-items`, { params:  params })).data
                        return Set(data).map(ti => ti.resource_id)
                    }
                    const setMatchedItems = (items) => commit('documents/setFilteredDocumentIds', items, {root: true})

                    setMatchedItems(await fetchTaggedItem(searchTags.toArray()))
                },
            }
        }
    }
}

const updateUsingId = (items, updated) => items.map(i => (i.id === updated.id) ? updated : i)

const deleteUsingId = (items, deleteId) => items.filter(i => i.id !== deleteId)

export default module