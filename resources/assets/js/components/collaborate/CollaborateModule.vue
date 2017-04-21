<template>
    <div class="panel-container bottompadded">

        <collaborate-search-bar></collaborate-search-bar>

        <div class="panel-separator">
            <div class="panel-contenthead">
                <p id="influencer-alert"></p>
            </div>
        </div>

        <div class="inner wide">
            <ul class="list-inline list-influencers" id="influencer-results"></ul>
        </div>

    </div>
</template>

<script>
    import CollaborateSearchBar from './CollaborateSearchBar.vue';

    export default {
        name: 'collaborate-module',

        data() {
            return {
                results: [],
            }
        },

        created() {
            this.$on('searched', data => this.results = data);
        }
    }
</script>