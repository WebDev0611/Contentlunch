<template>
    <div class='plan-ideas-container'>
        <div class="plan-ideas-cell cell-size-5">
            <avatar :user='idea.user'></avatar>
        </div>
        <div class="plan-ideas-cell cell-size-35">
            <a :href="ideaLink">
                <h5 class="plan-ideas-title">
                    {{ idea.name }}
                </h5>
            </a>
            <span class="plan-ideas-text">{{ idea.text }}</span>
        </div>

        <div class="plan-ideas-cell cell-size-25">
            <span class="plan-ideas-text">{{ idea.created_diff.toUpperCase() }}</span>
        </div>
        <div class="plan-ideas-cell cell-size-25">
            <span class="plan-ideas-text">UPDATED: {{ idea.updated_diff.toUpperCase() }}</span>
        </div>

        <div class="plan-ideas-cell cell-size-10">
            <ideas-list-item-dropdown
                :idea='idea'
                :hover='true'
                actions='write,edit,park,socialize'>
            </ideas-list-item-dropdown>
        </div>
    </div>
</template>

<script>
    import IdeasListItemDropdown from './IdeasListItemDropdown.vue';
    import Avatar from '../Avatar.vue';

    export default {
        name: 'ideas-list-item',

        props: [ 'idea' ],

        components: {
            Avatar,
            IdeasListItemDropdown,
        },

        computed: {
            ideaLink() {
                return `/idea/${this.idea.id}`;
            },
        },
    }
</script>
