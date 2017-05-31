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
        </div>
    </div>
</template>

<script>
    import Loading from '../Loading.vue';
    import RecentIdeasListItem from './RecentIdeasListItem.vue';

    export default {
        name: 'recent-ideas-list',

        components: {
            Loading,
            RecentIdeasListItem,
        },

        data() {
            return {
                ideas: [],
            };
        },

        created() {
            this.fetchIdeas();
        },

        methods: {
            fetchIdeas() {
                return $.get('/ideas').then(response => this.ideas = response);
            },
        },
    }
</script>