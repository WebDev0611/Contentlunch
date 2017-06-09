<template>
    <div>
        <freemium-alert restriction='launch 5 content pieces'></freemium-alert>

        <div class="create-panel-container">
            <h4 class="create-panel-heading">
                <i class="icon-connect"></i>
                PUBLISHED
            </h4>

            <content-list stage='published'></content-list>

        </div>
    </div>
</template>

<script>
import ContentList from './ContentList.vue';

    export default {
        name: 'content-dashboard',

        components: {
            ContentList,
        },
    }
</script>