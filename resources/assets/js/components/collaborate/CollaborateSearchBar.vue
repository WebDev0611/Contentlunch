<template>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="form-group">
                <div class="input-form-button prefixed">
                    <i class="icon-magnifier picto"></i>

                    <input type="text" id="influencer-topic-val"
                           v-model="keyword"
                           placeholder="Search influencers to work on projects..."
                           @keyup.enter="search"
                           class="input-search-icon">

                    <div class="input-form-button-action">
                        <button class="button"
                                type="submit"
                                @click="search"
                                id="influencer-search">SEARCH</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'collaborate-search-bar',

        data() {
            return {
                keyword: null,
            };
        },

        methods: {
            search() {
                this.$dispatch('searching');
                let topic = this.keyword;

                $.getJSON('/influencers', { topic })
                    .then(res => this.$dispatch('searched', res.results));
            }
        }
    }
</script>