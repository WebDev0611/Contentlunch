<template>
    <div class="panel-container bottompadded">

        <collaborate-search-bar></collaborate-search-bar>

        <div class="panel-separator"></div>

        <div class="panel-contenthead">
            <p v-show="results.length">
                {{ results.length }} influencers found.
            </p>
        </div>

        <div class="inner wide">
            <loading v-show="loading"></loading>
            <ul class="list-inline list-influencers" id="influencer-results">
                <influencer v-for="result in results" :data="result"></influencer>
            </ul>
        </div>

    </div>
</template>

<script>
    import CollaborateSearchBar from './CollaborateSearchBar.vue';
    import Loading from '../Loading.vue';

    export default {
        name: 'collaborate-module',

        data() {
            return {
                results: [],
                loading: false,
            }
        },

        created() {
            this.$on('searched', data => {
                this.results = data;
                this.loading = false;
            });

            this.$on('searching', () => this.loading = true);
        }
    }
</script>