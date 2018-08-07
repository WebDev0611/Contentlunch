<template>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="form-group">
                <div class="input-form-button prefixed">
                    <i class="icon-magnifier picto"></i>

                    <input type="text" id="influencer-topic-val"
                           v-model="keyword"
                           placeholder="Enter a topic, industry or keyword to find influencers to help with content projects (to co-create and/or promote your content)"
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
                this.$emit('searching');
                let topic = this.keyword;

                $.getJSON('/influencers', { topic })
                    .then(res => this.$emit('searched', res.results));
            }
        }
    }
</script>