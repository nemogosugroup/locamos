<template>
    <el-table
        ref="dragTable"
        v-loading="isLoading"
        row-key="id"
        border
        fit
        highlight-current-row
        style="width: 100%"
        v-bind:data="list"
    >
        <!-- Check All -->
        <!--        <el-table-column-->
        <!--            type="selection"-->
        <!--            width="40"-->
        <!--            align="center">-->
        <!--        </el-table-column>  -->
        <el-table-column min-width="120px" label="Tên file">
            <template v-slot="item">
                <span>{{ item.row.file_name }}</span>
            </template>
        </el-table-column>
        <el-table-column min-width="80px" label="Danh mục">
            <template v-slot="item">
                <span>{{ item.row.category_name }}</span>
            </template>
        </el-table-column>
        <el-table-column min-width="80px" label="Số lượng">
            <template v-slot="item">
                <span>{{ item.row.items_count }}</span>
            </template>
        </el-table-column>
        <el-table-column min-width="80px" label="Import Lúc">
            <template v-slot="item">
                <span>{{ item.row.imported_at }}</span>
            </template>
        </el-table-column>
        <!-- Action -->
        <el-table-column align="center" min-width="80px" label="Chi tiết">
            <template v-slot="item">
                <span v-if="item.row.status === 1">
                    <el-button type="primary" size="small" @click="showDetail(item.row.id)">Chi tiết</el-button>
                    <el-button type="danger" size="small" @click="confirmRemoveAll(item.row.id)">Xoá tất cả ({{ item.row.items_count }})</el-button>
                </span>
                <el-button v-else type="warning" size="small" disabled>Đã xoá tất cả ({{ item.row.items_count }})</el-button>
            </template>
        </el-table-column>
    </el-table>

    <!-- Dialog Remove -->
    <dialog-remove></dialog-remove>
</template>
<script>
import DialogRemove from '../components/removeDialog';
export default {
    name: 'TableLog',
    components: {DialogRemove},
    props: {
        list: {
            type: Array,
            default: ''
        },
        isLoading: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            dialogVisibleRemove: false,
            deleteImportId: 0
        }
    },

    filters: {},

    created() {
    },

    methods: {
        showDetail(importId) {
            console.log('detail', importId);
        },
        confirmRemoveAll(importId) {
            this.emitter.emit('is-confirm-remove-log-items', importId);
        }
    }
}

</script>