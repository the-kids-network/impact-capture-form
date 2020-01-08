import _ from 'lodash'
import fileIconFor from "./fileicons";

const Component = {

    props: ['usertype','documents'],

    template: `
         <table class="table documents" 
                data-toggle="table" 
                data-search="true" 
                data-pagination="true">
            <thead>
                <tr>
                    <th data-sortable="true">Type</th>
                    <th data-sortable="true">Title</th>
                    <th data-sortable="false">Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(document) in documents">   
                     <td class="preview">
                        <span class="hidden">{{ document.extension }}</span>
                        <span :class="'file-icon far fa-2x ' + fileIconFor(document.extension)"
                            data-toggle="popover" data-trigger="hover" data-placement="top" :data-content="'File type: ' + document.extension">
                        </span>
                    </td>
                    <td class="title">
                        {{ document.title }}
                    </td>
                    <td class="actions">
                        <a class="item" 
                            :href="'document/' + document.id"
                            data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Download">
                            <span class="glyphicon glyphicon-download"></span>
                        </a>
                        <form 
                            v-if="isAdminUser && document.trashed"
                            :id="'restore-' + document.id"
                            class="item" 
                            :action="'document/' + document.id + '/restore'" 
                            method="post">
                            <slot name="csrf"></slot>
                            <a href="javascript:void(0);" :onclick="'document.getElementById(\\'restore-' + document.id + '\\').submit()'"
                                data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Restore">
                                <span class="glyphicon glyphicon-backward"></span>
                            </a>
                        </form>
                        <form 
                            v-if="isAdminUser && document.trashed"
                            :id="'delete-' + document.id"
                            class="item" 
                            :action="'document/' + document.id" 
                            method="post">
                            <slot name="csrf"></slot>
                            <input type="hidden" name="really_delete" value="1">
                            <input type="hidden" name="_method" value="DELETE">  
                            <a href="javascript:void(0);" :onclick="'document.getElementById(\\'delete-' + document.id + '\\').submit()'"
                                data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Permenantly Delete">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </form>
                        <form
                            v-if="isAdminUser && !document.trashed" 
                            :id="'trash-' + document.id"
                            class="item" 
                            :action="'document/' + document.id" 
                            method="post">
                            <slot name="csrf"></slot>
                            <input type="hidden" name="really_delete" value="0">
                            <input type="hidden" name="_method" value="DELETE">  
                            <a href="javascript:void(0);" :onclick="'document.getElementById(\\'trash-' + document.id + '\\').submit()'"
                                data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Trash">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>
                        </form>
                        <form 
                            v-if="!document.is_shared"
                            :id="'share-' + document.id"
                            class="item" 
                            :action="'document/' + document.id + '/share'" 
                            method="post">
                            <slot name="csrf"></slot>
                            <input type="hidden" name="share" value="true">
                            <a href="javascript:void(0);" :onclick="'document.getElementById(\\'share-' + document.id + '\\').submit()'"
                                data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Share">
                                <span class="glyphicon glyphicon-share-alt"></span>
                            </a>
                        </form>
                        <form 
                            v-else
                            :id="'unshare-' + document.id"
                            class="item" 
                            :action="'document/' + document.id + '/share'" 
                            method="post">
                            <slot name="csrf"></slot>
                            <input type="hidden" name="share" value="false">
                            <a href="javascript:void(0);" :onclick="'document.getElementById(\\'unshare-' + document.id + '\\').submit()'"
                                data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Unshare">
                                <span class="glyphicon glyphicon-share-alt icon-flipped"></span>
                            </a>
                        </form>
                     </td>
                </tr>
            </tbody>
        </table>
    `,

    data() {
        return {

        }
    },

    computed: {
        isAdminUser() {
            return this.usertype === 'manager' || this.usertype === 'admin';
        },
    },

    methods: {
        fileIconFor(extension) {
            return fileIconFor(extension);
        }
    },

    mounted() {

    }
};


export default Component;
