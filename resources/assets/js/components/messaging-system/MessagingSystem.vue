<template>
    <div id="message-modal" class="sidemodal">
        <div class="sidemodal-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="sidemodal-header-title large">Create Task</h4>
                    <div id="task-status-alert" class="alert hidden">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div id="task-status-text"></div>
                    </div>
                </div>
                <div class="col-md-6 text-right" id="task-menu">
                    <button class="button button-primary button-small text-uppercase" id="add-task-button">Add Task</button>
                    <button class="sidemodal-close normal-flow" data-dismiss="modal">
                        <i class="icon-remove"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="sidemodal-container">

        </div>
    </div>
</template>

<script>
    export default {
        name: 'messaging-system',

        created() {
            this.$on('messaging:opening', () => {
                console.log('hehehe');
                $('#message-modal').modal('show');
            });
        }
    }
</script>