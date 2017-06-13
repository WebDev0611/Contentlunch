<template>
    <div :class="!shouldShow ? 'hide' : ''">

        <div :title="order.title"
             :data-original-title="order.title"
             :data-status="order.status"
             :data-writer="order.writer ? order.writer.name : ''"
             class="create-panel-table border-left order-list-row"
             :class="borderColor"
        >
            <div class="create-panel-table-cell avatar">
                <img :src="order.writer ? order.writer.photo : '/images/cl-avatar2.png'" alt=""
                     class="create-image"><br>
            </div>

            <div class="create-panel-table-cell title-cell">
                <h5 class="dashboard-tasks-title"> {{order.title}} </h5>

                <ul class="dashboard-tasks-list">
                    <li>STATUS: <strong>{{order.status}}</strong></li>
                    <li>WRITER: <strong>{{order.writer ? order.writer.name : 'None'}}</strong></li>
                </ul>
            </div>

            <div class="create-panel-table-cell text-right">
                <span v-if="order.status === 'In Progress'" class="label label-primary">Order in progress</span>
                <span v-else-if="order.status === 'Open'" class="label label-info">Order Open</span>
                <span v-else-if="order.status === 'Pending Approval'" class="label label-warning">Order Pending Approval</span>
                <span v-else-if="order.status === 'Approved'" class="label label-success">Order Approved</span>
            </div>

            <div class="create-panel-table-cell text-right title-cell">
                <a v-if="order.status === 'Approved' || order.status === 'Pending Approval' || order.status === 'In Progress'"
                   :href="'/content/orders/' + order.order_id" class="order-link">
                    <i class="icon-edit large"></i>
                </a>
            </div>


            <!--<div class="create-panel-table-cell text-right">
                <span class="dashboard-performing-text small">
                    LAUNCHED: <strong>05/05/2016</strong>
                </span>
                </div>
                <div class="create-panel-table-cell text-right">
                    <i class="create-panel-spaceship icon-spaceship-circle"></i>
                </div>
              -->
        </div>

    </div>
</template>

<script>
    export default {
        name: 'content-order-item',

        props: ['order', 'shouldShow'],

        computed: {
            borderColor() {
                return (this.order.status === 'Approved' || this.order.status === 'Pending Approval') ? 'border-green' : ''
            }
        }
    }
</script>

<style scoped>
    .order-link {
        display: inline-block;
        vertical-align: middle;
        margin-right: 10px;
    }
</style>