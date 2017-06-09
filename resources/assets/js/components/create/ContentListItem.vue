<template>
    <div class="create-panel-table">
        <div class="create-panel-table-cell cell-size-5">
            <avatar :user='content.user'></avatar>
        </div>
        <div class="create-panel-table-cell cell-size-65">
            <h5 class="dashboard-tasks-title">
                <a :href="content.link">{{ content.title }}</a>
            </h5>
            <span class="dashboard-members-text small">
                {{ content.created_at_diff.toUpperCase() }}
            </span>
        </div>
        <div class="create-panel-table-cell text-center cell-size-5" :title="content.content_type">
            <i class="tooltip-icon large" :class='[ content.content_icon ]'></i>
        </div>
        <div class="create-panel-table-cell text-right cell-size-15">
            <span class="dashboard-performing-text small">
                UPDATED: <strong>{{ content.updated_at_format }}</strong>
            </span>
        </div>
        <!-- @can('guests-denied') -->
        <!--
        <div class="create-panel-table-cell text-right cell-size-10">
            <div class="create-dropdown">
                <button type="button" class="button button-action" data-toggle="dropdown">
                    <i class="icon-add-circle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="{{ route('archived_contents.update', $content) }}">Archive it</a>
                    </li>
                </ul>
            </div>
        </div>
        -->
        <!-- @endcan -->
    </div>
</template>

<script>
    export default {
        name: 'content-list-item',

        props: ['content'],
    }
</script>