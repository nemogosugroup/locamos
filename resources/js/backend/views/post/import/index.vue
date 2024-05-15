<template>
    <div class="app-container">
        <import :list-categories="listCategories"/>
        <el-divider/>
        <h2>Import-Logs List</h2>
        <table-data :list="list" :isLoading="isLoading"></table-data>
        <pagination
            v-show="total>10"
            v-bind:total="total"
            v-model:page="listQuery.page"
            v-model:limit="listQuery.limit"
            layout="prev, pager, next"
            @pagination="fetch"/>
    </div>
</template>

<script>
import Pagination from '@/components/Pagination'
import TableData from './components/Data';
import Import from './components/Import';
import AdminRepositoryFactory from '@/backend/respository';

const ImportLogRepository = AdminRepositoryFactory.get('import');

export default {
    name: 'ImportLogs',
    components: {Pagination, TableData, Import},
    data() {
        return {
            list: [],
            listQuery: {
                page: 1,
                limit: 10,
                sort: 'desc'
            },
            listCategories: [],
            isLoading: true,
            dialogFormVisible: false,
            titleDialog: '',
            total: 0,
            formData: {
                file_name: '',
                items_count: '',
                imported_at: '',
                posts: []
            },
            id: false,
        }
    },

    filters: {},

    created() {
        this.fetch();
        this.emitter.off('is-imported');
        this.emitter.off('handle-delete-all-log-by-id');
    },

    mounted() {
        this.emitter.on('is-imported', () => {
            this.fetch();
        });
        this.emitter.on('handle-delete-all-log-by-id', importId => {
            this.handleRemoveAllLogItems(importId);
        });
    },

    methods: {
        async fetch() {
            this.isLoading = true;
            const {data} = await ImportLogRepository.list(this.listQuery);
            this.isLoading = false;
            if (data.success) {
                const results = data.data.data;
                this.listCategories = data.categories;
                this.list = results.map(item => {
                    return item
                });
                this.total = data.data.total;
            }
        },
        async handleRemoveAllLogItems(importId) {
            const {data} = await ImportLogRepository.delete(importId);
            if (data.success) {
                this.$message({
                    message: data.message,
                    type: 'success'
                })
                await this.fetch();
                this.emitter.emit('is-close-remove-log');
            } else {
                this.$message({
                    message: data.message,
                    type: 'error'
                })
            }
        }
    }
}

</script>

<style lang="scss" scoped>
</style>