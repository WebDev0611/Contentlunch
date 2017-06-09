<template>
    <div class="panel" id="recent-ideas">
        <div class="panel-header">
            <h4 class="panel-sidebar-title-secondary">
                Recent Ideas
                <a href="/plan/ideas">
                    See All
                    <i class="icon-arrow-right"></i>
                </a>
            </h4>
        </div>

        <div class="panel-container nopadding">
            <div class="dashboard-ideas-container idea-empty" v-show='!ideas.length'>
                <div class="dashboard-ideas-cell">
                    No ideas: <a href="/plan">Create One</a>
                </div>
            </div>

            <recent-ideas-list-item
                v-for='idea in ideas'
                :idea='idea'
                :key='idea.id'>
            </recent-ideas-list-item>

            <loading v-if='!loaded'></loading>

            <load-more-button
                v-if='loaded'
                @click.native='fetchIdeas'
                :total-left='totalIdeasLeft'>
            </load-more-button>
        </div>
    </div>
</template>

<script>
    import Loading from '../Loading.vue';
    import RecentIdeasListItem from './RecentIdeasListItem.vue';
    import LoadMoreButton from '../LoadMoreButton.vue';

    export default {
        name: 'recent-ideas-list',

        components: {
            Loading,
            LoadMoreButton,
            RecentIdeasListItem,
        },

        data() {
            return {
                ideas: [],
                loaded: false,
                page: 1,
                totalIdeas: 0,
            };
        },

        created() {
            this.fetchIdeas();
        },

        methods: {
            request() {
                let payload = {
                    page: this.page++,
                    include: 'user',
                }

                this.loaded = false;

                return $.get('/api/ideas', payload).then(response => {
                    this.loaded = true;
                    return response;
                });
            },

            fetchIdeas() {
                return this.request().then(response => {
                    this.ideas = this.ideas.concat(response.data);
                    this.totalIdeas = response.meta.total;
                });
            },
        },

        computed: {
            totalIdeasLeft() {
                return this.totalIdeas - this.ideas.length;
            }
        }
    }
</script>