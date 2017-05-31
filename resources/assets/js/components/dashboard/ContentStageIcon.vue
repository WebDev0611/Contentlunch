<template>
    <span>
        <i class="dashboard-tasks-list-icon primary icon-idea" title='Idea'></i>

        <i :class="{ 'primary': contentStage >= 1, 'tertiary': contentStage < 1 }"
            class="dashboard-tasks-list-icon icon-content"
            title='Content being written'></i>

        <i :class="{ 'primary': contentStage >= 2, 'tertiary': contentStage < 2 }"
            class="dashboard-tasks-list-icon icon-alert"
            title='Ready to Publish'></i>

        <i :class="{ 'primary': contentStage >= 3, 'tertiary': contentStage < 3 }"
            class="dashboard-tasks-list-icon icon-share"
            title='Published'></i>
    </span>
</template>

<script>
    export default {
        name: 'content-stage-icon',
        props: [ 'contentStage' ],
    }
</script>