<template>

    <div>
        <div class="col-md-6" :class="{'pull-right' : comment.from_client}">

            <div class="panel panel-default single-comment"
                 :class="{'panel-warning' : comment.from_client, 'panel-info' : !comment.from_client}">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <strong v-if="comment.from_client">You</strong>

                        <span v-else-if="comment.writer_name">
                            <strong>{{comment.writer_name}}</strong> (Writer)
                        </span>

                        <span v-else-if="comment.editor_name">
                            <strong>{{comment.editor_name}}</strong> (Editor)
                        </span>

                        <span class="pull-right" v-text="formattedDate"></span>
                    </h3>
                </div>
                <div class="panel-body">{{comment.note}}</div>
            </div>

        </div>

        <div class="clearfix"></div>
    </div>

</template>

<script>
    export default {
        name: 'content-order-comment',

        props: ['comment'],

        created() {
            Number.prototype.padLeft = function (base, chr) {
                var len = (String(base || 10).length - String(this).length) + 1;
                return len > 0 ? new Array(len).join(chr || '0') + this : this;
            }
        },

        computed: {
            formattedDate() {
                let d = new Date(this.comment.timestamp);
                return [
                        (d.getMonth() + 1).padLeft(),
                        d.getDate().padLeft(),
                        d.getFullYear()
                    ].join('-') + ' ' +
                    [
                        d.getHours().padLeft(),
                        d.getMinutes().padLeft()
                    ].join(':');
            }
        }
    }
</script>