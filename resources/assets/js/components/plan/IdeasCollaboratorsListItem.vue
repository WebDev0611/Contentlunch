<template>
    <label class="checkbox-tag">
        <input type="checkbox" v-model='collaborator.is_collaborator'>
        <span>{{ collaborator.name }}</span>
    </label>
</template>

<script>
    export default {
        name: 'ideas-collaborators-list-item',

        props: ['collaborator'],
    }
</script>