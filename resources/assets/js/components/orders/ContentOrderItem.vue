<template>
    <div>
        <div :title="order.title"
             :data-original-title="order.title"
             :data-status="order.status"
             :data-writer="order.writer ? order.writer.name : ''"
             class="create-panel-table border-left border-green order-list-row">

            <div class="create-panel-table-cell avatar">
                <img :src="order.writer ? order.writer.photo : '/images/cl-avatar2.png'" alt="" class="create-image"><br>
            </div>

            <div class="create-panel-table-cell title-cell"><h5 class="dashboard-tasks-title">
                {{order.title}}
        </h5>
                <ul class="dashboard-tasks-list">
                    <li>STATUS: <strong>{{order.status}}</strong></li>
                    <li>WRITER: <strong>{{order.writer ? order.writer.name : ''}}</strong></li>
                </ul>
            </div>

            <div class="create-panel-table-cell text-right  ">
                <a :href="'/content/orders/' + order.order_id">
                    <i class="icon-edit large"></i>
                </a>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        name: 'content-order-item',

        props: ['order']
    }
</script>