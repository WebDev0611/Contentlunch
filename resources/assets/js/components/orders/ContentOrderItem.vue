<template>
    <div :class="index >= itemsToShow ? 'hidden' : ''"
         v-if="passesFilter">

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
                <a :href="'/content/orders/' + order.id">
                    <h5 class="dashboard-tasks-title"> {{order.title}} </h5>
                </a>
                <ul class="dashboard-tasks-list">
                    <li>STATUS: <strong>{{order.status}}</strong></li>
                    <li>WRITER: <strong>{{order.writer ? order.writer.name : 'None'}}</strong></li>
                </ul>
            </div>

            <div class="create-panel-table-cell text-right">
                <a v-if="order.status === 'Approved' || order.status === 'Pending Approval'"
                   :href="'/content/orders/' + order.id">
                    <i class="icon-edit large"></i>
                </a>
                <span v-else class="red small">Order in progress</span>
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

        props: ['order', 'index', 'itemsToShow', 'filter'],

        computed: {
            borderColor() {
                return (this.order.status === 'Approved' || this.order.status === 'Pending Approval') ? 'border-green' : ''
            },

            passesFilter() {
                return this.filter === 'all' || this.order.status === this.filter
            }
        }
    }
</script>