<template>
    <li>
        <a @click='toggleBookmark' class="btn btn-fav" :class="{ 'active': data.bookmarked }">
            <i class="icon-star-outline"></i>
            <i class="icon-star"></i>
        </a>

        <div class="body">
            <div class="user-avatar">
                <img :src="data.image_url" :alt="data.name"/>
            </div>
            <p class="title">{{ data.name }}</p>
            <p class="desc">{{ data.description }}</p>
        </div>

        <div class="foot">
            <ul class="list-inline list-soc">
                <li>
                    <i class="icon-twitter2"></i> {{ twitterFollowers }}
                </li>
            </ul>
            <div class="btn-group">
                <a :href="twitterLink" target='_blank' class="button button-default details">PROFILE</a>
            </div>
        </div>
    </li>
</template>

<script>
    export default {
        name: 'influencer',

        props: ['data'],

        methods: {
            toggleBookmark() {
                this.data.bookmarked = !this.data.bookmarked;
                this.$emit('toggled', this);


                $.ajax({
                    method: 'post',
                    data: this.data,
                    url: '/influencers/bookmarks',
                });
            },
        },

        computed: {
            twitterFollowers() {
                let followerCount = this.data.twitter_followers_count;

                if (followerCount > 1000 && followerCount < 1000000) {
                    followerCount = Math.floor(followerCount / 1000) + 'k';
                } else if (followerCount > 1000000) {
                    followerCount = (Math.floor(followerCount / 100000) / 10) + 'm';
                }

                return followerCount;
            },

            twitterLink() {
                let twitterId = this.data.twitter_id_str;

                return twitterId ? `https://twitter.com/intent/user?user_id=${twitterId}` : null;
            }
        }
    }
</script>