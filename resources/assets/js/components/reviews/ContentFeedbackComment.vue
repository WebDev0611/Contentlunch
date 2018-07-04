<template>
     <div class="review-feedback-inline">
        <div class="reviewer-comments">
            <blockquote>
                <p>{{comment.text}}</p>
                <footer>{{ formattedDate }}</footer>
            </blockquote>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'content-order-comment',

        props: ['comment'],

        computed: {
            formattedDate() {
                return moment(this.comment.created_at).calendar();
            }
        }
    }
</script>