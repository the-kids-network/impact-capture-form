import _ from 'lodash'
import { List } from 'immutable';

const Component = { 

    props: {
        itemsToPaginate: {
            default: () => List()
        },
        initialPageSize: {
            default: () => 1
        },
        pageSizes: {
            default: () => [10, 25, 50, 100]
        },

        resetCurrentPage: {
            // increment to reset current page
            default: () => 1
        },
    },

    components: {
 
    },

    //

    template: `
        <div>
            <slot :itemsToDisplay="itemsForCurrentPage"></slot>

            <div class="pagination-bar">
                <div class="page-size-list">
                    <span class="btn-group dropdown dropup">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                            <span class="page-size">{{currentPageSize}}</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a  v-for="size in pageSizes"
                                :class="'dropdown-item page-size ' + ((size === currentPageSize) ? 'active' : '')" 
                                @click.prevent="setCurrentPageSize(size)"
                                role="menuitem"
                                href="#">{{size}}</a>
                        </div>
                    </span> rows per page
                </div>
                <div class="page-selector" v-if="pages.length > 1">
                    <ul class="pagination pages-list justify-content-end">
                        <li class="page-item" 
                            v-if="currentPage != 1" 
                            @click="currentPage--">
                            <a @click.prevent class="page-link"  href="#"> &lt; </a>
                        </li>
                        <li :class="'page-item ' + ((page === currentPage) ? 'active' : '')" 
                            v-for="page in pages" 
                            @click="currentPage = page">
                            <a @click.prevent class="page-link"  href="#"> {{page}} </a>
                        </li>
                        <li class="page-item" 
                            @click="currentPage++" 
                            v-if="currentPage < pages.length">
                            <a @click.prevent class="page-link" href="#"> &gt; </a>
                        </li>
                    </ul>
                </div>	
            </div>
        </div>
    `,

    data() {
        return {
             currentPage: 1,
             currentPageSize: 25
        }
    },

    computed: {
        itemsForCurrentPage() {
            return this.itemsForPage(this.currentPage)
        },
        pages() {
            return this.getPages()
        },
    },

    watch: {
        resetCurrentPage() {
            // Requested by parent component to reset current page
            this.currentPage = 1
        },
        itemsToPaginate() {
            // handle being off the visible/allowable pages on list changee
            if (this.currentPage <= 0 && this.pages.length > 0) {
                this.currentPage = 1
            } else if (this.currentPage > this.pages.length) {
                this.currentPage = 1
            } 
        }
    },

    created() {
        this.setCurrentPageSize(this.initialPageSize)
    },

    mounted() {
    },

    methods: { 
        // If current page size change, reset current page to first page
        setCurrentPageSize(size) {
            this.currentPageSize = size
            this.currentPage = 1
        },

        getPages() {
            const numberOfPages = Math.ceil(this.itemsToPaginate.size / this.currentPageSize);
            const range = (start, end) => [...Array(end - start + 1)].map((_, i) => start + i);
            const allPages = range(1, numberOfPages)
            return allPages;
        },

        itemsForPage(page) {
            let from = (page * this.currentPageSize) - this.currentPageSize;
            let to = (page * this.currentPageSize);
            return this.itemsToPaginate.slice(from, to);
        }
    }
};

export default Component;