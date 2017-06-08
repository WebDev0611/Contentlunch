<template>
    <div class="create-panel-table" v-show='totalLeft && totalLeft > 0'>
        <div class="create-panel-table-cell text-center">
            <a style='cursor:pointer'>
                {{ totalLeft }} left - Show More
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'load-more-button',

        props: ['totalLeft'],
    }
</script>