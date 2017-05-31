<template>
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-sidebar-title-secondary">
                Recent Content
            </h4>
        </div>
        <div class="panel-container nopadding">
            <loading v-show='loading'></loading>
            <content-list-item
                v-for='content in contents'
                :content='content'
                :key='content.id'>
            </content-list-item>
        </div>
    </div>
</template>

<script>
    import Loading from '../Loading.vue';
    import ContentListItem from './ContentListItem.vue';

    export default {
        name: 'content-list',

        components: {
            Loading,
            ContentListItem,
        },

        data() {
            return {
                contents: [],
                loading: true,
            };
        },

        created() {
            this.fetchContents().then(response => this.loading = false);
        },

        methods: {
            fetchContents() {
                return $.get('/api/contents').then(response => {
                    this.contents = response.data;

                    return response;
                });
            },
        },
    }
</script>