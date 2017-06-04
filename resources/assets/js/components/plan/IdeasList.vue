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

        props: [ 'status' ],

        computed: {
            ideas() {
                return this.$store.state.ideas.filter(idea => idea.status === this.status);
            },
        },
    }
</script>