<template>

    <div class="panel-body">
        <ul class="comment">

            <li class="clearfix" :class="liClass">

                <span v-if="comment.from_client" class="comment-img pull-left">
                    <img :src="comment.user.profile_image" alt="User Avatar" class="img-circle"/>
                </span>

                <span v-else-if="comment.writer" class="comment-img pull-right">
                    <img :src="comment.writer.photo" alt="Writer Avatar" class="img-circle"/>
                </span>

                <span v-else-if="comment.editor" class="comment-img pull-right">
                    <img :src="comment.editor.photo" alt="Editor Avatar" class="img-circle"/>
                </span>

                <div class="comment-body clearfix">
                    <div class="header">

                        <strong v-if="comment.writer" class="primary-font pull-right">{{comment.writer.name}}</strong>
                        <strong v-else-if="comment.editor" class="primary-font pull-right">{{comment.editor.name}}</strong>
                        <strong v-else-if="comment.user.is_guest" class="primary-font">{{comment.user.name}}</strong>

                        <small class="text-muted" :class="timeClass">
                            <span class="glyphicon glyphicon-time"></span> {{ formattedDate }}
                        </small>

                    </div>
                    <br v-if="!comment.user.is_guest">

                    <p> {{comment.text}} </p>
                </div>
            </li>

        </ul>
    </div>

</template>

<script>
    export default {
        name: 'content-order-comment',

        props: ['comment'],

        computed: {
            formattedDate() {
                console.log("hey");
                return moment(this.comment.created_at).calendar();
            },

            liClass() {
                return this.comment.user.is_guest ? 'left' : 'right'
            },

            timeClass() {
                return this.comment.user.is_guest ? 'pull-right' : 'pull-left'
            }
        }
    }
</script>

<style scoped>
    .img-circle {
        width: 44px;
        height: 44px;
    }
</style>