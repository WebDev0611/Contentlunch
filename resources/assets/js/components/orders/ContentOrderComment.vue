<template>

    <div class="panel-body">
        <ul class="comment">

            <li class="clearfix left" :class="{'right': !comment.from_client}">

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

                        <strong v-if="comment.writer" class="primary-font">{{comment.writer.name}}</strong>
                        <strong v-else-if="comment.from_client" class="primary-font">{{comment.user.name}}</strong>
                        <strong v-else-if="comment.editor" class="primary-font">{{comment.editor.name}}</strong>

                        <small class="pull-right text-muted">
                            <span class="glyphicon glyphicon-time"></span> {{ formattedDate }}

                        </small>
                    </div>
                    <p> {{comment.note}} </p>
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
                return moment(this.comment.timestamp).calendar();
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