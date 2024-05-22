<template>
    <!-- Dialog Remove -->
    <el-dialog :title="titleDialog" v-model="isShowRemoveDialog" width="30%" @close="closeRemoveDialog">
        <template #footer>
            <div class="dialog-footer">
                <el-button
                    @click="closeRemoveDialog"
                    v-bind:loading="isLoadingSave"
                > Hủy</el-button>
                <el-button
                    type="primary"
                    @click="handleRemoveAll"
                    v-bind:loading="isLoadingSave"
                >
                    Xóa
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>
<script>
export default {
    name: 'DialogRemoveImportLog',
    data() {
        return {
            isLoading: true,
            isLoadingSave: false,
            isShowRemoveDialog: false,
            titleDialog: 'Bạn có muốn xoá tất cả vị trí trong file này ?',
            importId: 0
        }
    },

    filters: {},

    created() {
        this.emitter.off('is-confirm-remove-log-items');
        this.emitter.off('is-close-remove-log');
    },
    mounted() {
        this.emitter.on('is-confirm-remove-log-items', importId => {
            this.importId = importId;
            this.isShowRemoveDialog = true;
        });
        this.emitter.on('is-close-remove-log', () => {
            this.isShowRemoveDialog = false;
            this.isLoadingSave = false;
        });
    },
    methods: {
        closeRemoveDialog() {
            this.isShowRemoveDialog = false;
        },
        handleRemoveAll() {
            this.isLoadingSave = true;
            this.isShowRemoveDialog = true;
            this.emitter.emit('handle-delete-all-log-by-id', this.importId);
        }
    }
}

</script>