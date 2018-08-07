<template>
    <div class="panel-container-options white">
        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-2">
                        <label class="select-horizontal-label">{{ordersCount + (ordersCount === 1 ? " Order"
                            : " Orders")}}</label>
                    </div>
                    <!--<div class="col-md-4">
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <label class="select-horizontal-label">By:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="select select-small extend">
                                    <select name="#" id="#">
                                        <option value="#">Any one</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="col-md-2 text-right">
                <div class="select select-small extend">
                    <select name="statusFilter" @change="changeFilter($event)">
                        <option value="all">All</option>
                        <option value="Approved">Approved</option>
                        <option value="Pending Approval">Pending Approval</option>
                        <option value="Open">Open</option>
                        <option value="In Progress">In Progress</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'content-orders-filter',

        props: ['ordersCount'],

        methods: {
            changeFilter(event) {
                this.$parent.$emit('changeFilter', event.target.value)
            }
        }
    }
</script>