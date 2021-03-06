<template>
    <div class="panel">
        <div class="panel-header">
            <ul class="panel-tabs withborder text-center">
                <li :class="{ 'active': tab === 'influencers' }" @click="toggleTab('influencers')">
                    <a href="javascript:;">Search for Influencers</a>
                </li>
                <li :class="{ 'active': tab === 'bookmarks' }" @click="toggleTab('bookmarks')">
                    <a href="javascript:;">Bookmarked Influencers</a>
                </li>
            </ul>
        </div>

        <div class="panel-container bottompadded" v-show="tab === 'influencers'">
            <collaborate-search-bar
                @searched='updateListResults'
                @searching='showLoadingGif'>
            </collaborate-search-bar>

            <div class="panel-separator"></div>

            <div class="panel-contenthead">
                <p v-show="results.length">{{ results.length }} influencers found <a data-toggle="popover" tabindex="0" role="button" data-trigger="focus" title="" data-content="Click on the star icon in the top right corner to bookmak an influencer." data-placement="top" class="popover-icon icon-question"><span class="sr-only">How to bookmark influencer?</span></a></p>
            </div>

            <div class="inner wide">
                <loading v-show="loading"></loading>
                <ul class="list-inline list-influencers" id="influencer-results">
                    <influencer @toggled="updateBookmarkedList" v-for="result in results" :data="result" :key='result.id'></influencer>
                </ul>
            </div>
        </div>

        <div class="panel-container bottompadded" v-show="tab === 'bookmarks'">
            <div class="panel-contenthead">
                <p v-show='bookmarks.length'>
                    {{ bookmarks.length }} bookmarked influencers.
                </p>
                <p v-show='!bookmarks.length'>
                    No influencers bookmarked.
                </p>
            </div>

            <div class="inner wide">
                <loading v-show="loading"></loading>
                <ul class="list-inline list-influencers">
                    <influencer
                        v-for="bookmark in bookmarks"
                        :data="bookmark"
                        :key='bookmark.id'
                        @toggled="updateSearchList">
                    </influencer>
                </ul>
            </div>
        </div>
    </div>

</template>

<script>
    import CollaborateSearchBar from './CollaborateSearchBar.vue';
    import Influencer from './Influencer.vue';
    import Loading from '../Loading.vue';

    export default {
        name: 'collaborate-module',
        components: {
            CollaborateSearchBar,
            Influencer,
        },

        data() {
            return {
                tab: 'influencers',
                results: [],
                bookmarks: [],
                loading: false,
            }
        },

        created() {
            this.fetchBookmarks();
        },

        methods: {
            toggleTab(tab) {
                if (this.tab !== tab) {
                    this.tab = tab;
                }
            },

            updateBookmarkedList(influencer) {
                const twitterId = influencer.data.twitter_id_str,
                    bookmarkState = influencer.data.bookmarked;
                let influencerIndex = this.bookmarks.findIndex(thisInfluencer => {
                    return thisInfluencer.twitter_id_str == twitterId;
                });
                if (influencerIndex > -1) {
                    // remove boomark from array
                    this.bookmarks.splice(influencerIndex, 1);
                } else {
                    // Add bookmark to array
                    if (bookmarkState) this.bookmarks.push(influencer.data);
                }
            },
            updateSearchList(influencer) {
                const twitterId = influencer.data.twitter_id_str;
                let resultsIndex = this.results.findIndex(thisInfluencer => {
                    return thisInfluencer.twitter_id_str == twitterId;
                });
                let bookmarksIndex = this.bookmarks.findIndex(thisInfluencer => {
                    return thisInfluencer.twitter_id_str == twitterId;
                });

                if (resultsIndex > -1) {
                    // uncheck star on results
                    this.results[resultsIndex].bookmarked = false;
                }
                // remove bookmark from array
                this.bookmarks.splice(bookmarksIndex, 1);
            },

            fetchBookmarks() {
                return $.get('/influencers/bookmarks').then(response => {
                        this.bookmarks = response.data.map(element => {
                            element.bookmarked = true;
                            return element;
                        });
                    });
            },

            updateListResults(data) {
                this.results = data.map(element => {
                    let twitter_id_str = element.twitter_id_str;

                    if (_.findWhere(this.bookmarks, { twitter_id_str })) {
                        element.bookmarked = true;
                    } else {
                        element.bookmarked = false;
                    }

                    return element;
                });
                this.loading = false;
            },

            showLoadingGif() {
                this.loading = true;
            }
        }
    }
</script>