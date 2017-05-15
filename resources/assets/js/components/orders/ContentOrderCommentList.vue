<template>
    <div>
        <loading v-if='!loaded'></loading>

        <div class="alert alert-info" role="alert" v-if="!comments.length && loaded">
            No comments for this order yet.
        </div>

        <h3 v-if="comments.length" class="order-title">{{comments[0].order_title}}</h3>

        <content-order-comment
                v-for="comment in comments"
                :key="comment.id"
                :comment="comment"
        ></content-order-comment>
    </div>
</template>

<script>
    import ContentOrderComment from './ContentOrderComment.vue'

    export default {

        name: 'content-order-comment-list',
        props: ['orderId'],

        components: {
            ContentOrderComment,
        },

        data() {
            return {
                comments: [],
                loaded: false,
            }
        },

        created() {
            this.fetchComments(this.orderId).then(response => {
                this.comments = response;
                this.loaded = true;
            });
        },

        methods: {
            fetchComments(orderId) {
                return $.get('/api/content/orders/' + orderId + '/comments');
            }
        }
    }
</script>