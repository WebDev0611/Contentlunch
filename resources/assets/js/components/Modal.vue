<template>
    <div v-show='visible' transition='modal'>
        <div class="modal" tabindex="-1" role="dialog" @click.self='closeModal'>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click='closeModal'>
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">{{ title }}</h4>
                    </div>
                    <div class="modal-body">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop in"></div>
    </div>
</template>

<script>
    export default {
        name: 'modal',

        props: {
            show: {
                type: Boolean,
                twoWay: true,
                default: false
            },

            title: {
                type: String,
                default: 'Modal',
            }
        },

        data() {
            return {
                visible: false,
            }
        },

        created() {
            if (this.show) {
                document.body.className += ' modal-open';
            }

            this.visible = this.show;
        },

        beforeDestroy() {
            document.body.className = document.body.className.replace(/\s?modal-open/, '');
        },

        methods: {
            closeModal() {
                this.visible = false;
                this.changeBodyClass();
                this.$emit('closed');
            },

            changeBodyClass() {
                if (this.visible) {
                    document.body.className += ' modal-open';
                } else {
                    document.body.className = document.body.className.replace(/\s?modal-open/, '');
                }
            }
        },

        watch: {
            show(value) {
                this.visible = this.show;
                this.changeBodyClass();
            }
        },
    }
</script>

<style scoped>
    .modal {
        display: block;
    }
    .modal-transition {
        transition: all .6s ease;
    }
    .modal-leave {
        border-radius: 1px !important;
    }
    .modal-transition .modal-dialog, .modal-transition .modal-backdrop {
        transition: all .5s ease;
    }
    .modal-enter .modal-dialog, .modal-leave .modal-dialog {
        opacity: 0;
        transform: translateY(-30%);
    }
    .modal-enter .modal-backdrop, .modal-leave .modal-backdrop {
        opacity: 0;
    }
</style>