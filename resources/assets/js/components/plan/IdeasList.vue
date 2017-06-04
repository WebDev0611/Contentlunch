<template>
    <div>
        <ideas-list-item
            v-for='idea in ideas'
            :idea='idea'
            :key='idea.id'>
        </ideas-list-item>
    </div>
</template>

<script>
    import Loading from '../Loading.vue';
    import IdeasListItem from './IdeasListItem.vue';

    export default {
        name: 'ideas-list',

        components: { Loading, IdeasListItem },

        data() {
            return {
                allIdeas: [],
            };
        },

        created() {
            this.fetchIdeas();
        },

        methods: {
            fetchIdeas() {
                return $.get('/ideas').then(response => this.allIdeas = response);
            }
        },

        computed: {
            ideas() {
                return this.allIdeas.filter(idea => idea.status === 'active');
            },
        },
    }
</script>