<template>
    <div class="app-container">
        <search-category :list-query="listQuery.title" @showdialog="handleShowDialog"
                         @search="handleSearch"></search-category>
        <table-category @showdialog="handleShowDialog" :list="list" :isShowCategory="isShowCategory"></table-category>
        <pagination
            v-show="total>10"
            v-bind:total="total"
            v-model:page="listQuery.page"
            v-model:limit="listQuery.limit"
            layout="prev, pager, next"
            @pagination="fetch"/>
        <dialog-category :dialog-form-visible="dialogFormVisible" :dialog-status="dialogStatus" :formData="formData"
                         :title-dialog="titleDialog" @hidedialog="handleHideDialog"
                         @update-data="handleUpdateData"></dialog-category>

        <dialog-remove :title-dialog="titleDialog" :dialog-status="dialogStatus"
                       :dialog-visible-remove="dialogVisibleRemove" @hiddendialog="handleHideDialog" :title="title"
                       @delete="handleRemove"></dialog-remove>
    </div>
</template>

<script>
import Pagination from '@/components/Pagination'
import SearchCategory from '@/backend/components/SearchCategory'
import DialogRemove from '@/backend/components/RemovePost'
import TableCategory from '@/backend/components/DataCategories'
import DialogCategory from './components/dialog'
import AdminRepositoryFactory from '@/backend/respository';

const CategoryPostRepository = AdminRepositoryFactory.get('categoryPost');
const formData = {
    'vi': {
        title: ''
    },
    'en': {
        title: ''
    },
    'icon': ''
}
export default {
    name: 'CategoriesPost',
    components: {Pagination, TableCategory, SearchCategory, DialogCategory, DialogRemove},
    data() {
        return {
            list: [],
            listQuery: {
                page: 1,
                limit: 10,
                sort: 'desc',
                title: '',
            },
            total: 0,
            formData: formData,
            dialogStatus: '',
            dialogFormVisible: false,
            dialogVisibleRemove: false,
            titleDialog: '',
            id: '', // id category
            title: '',
            isShowCategory: false
        }
    },
    filters: {},
    created() {
        this.fetch();
    },

    mounted() {

    },

    methods: {
        async fetch() {
            this.isLoading = true;
            const {data} = await CategoryPostRepository.list(this.listQuery);
            this.isLoading = false;
            if (data.success) {
                const results = data.data.data;
                this.list = results.map(item => {
                    return item
                });
                this.total = data.data.total;
            }
        },
        // show dialog
        handleShowDialog(data) {
            this.dialogStatus = data.status ? data.status : 'create';
            this.titleDialog = this.dialogStatus === 'create' ? 'Tạo danh mục' : 'Chỉnh sửa danh mục';
            if (data.status === 'delete') {
                this.dialogVisibleRemove = true;
                this.id = data.id;
                this.title = data.title;
                this.titleDialog = 'Bạn có muốn xoá danh mục'
            } else {
                this.dialogFormVisible = true;
            }

            if (data.status === 'edit') {
                let item = data.item;
                let dataVi = item.translations.find(data => data.locale === 'vi');
                let dataEn = item.translations.find(data => data.locale === 'en');
                this.formData = {
                    vi: dataVi,
                    en: dataEn,
                    'id': item.id,
                    "icon": item.icon
                }
            }
        },
        // hide dialog
        handleHideDialog() {
            this.dialogStatus = '';
            this.dialogFormVisible = false;
            this.dialogVisibleRemove = false;
            this.titleDialog = '';
            this.formData = {
                'vi': {
                    title: ''
                },
                'en': {
                    title: ''
                },
                'icon': ''
            }
        },
        // update data
        handleUpdateData() {
            this.fetch();
            this.handleHideDialog();
        },
        // remove
        async handleRemove() {
            const {data} = await CategoryPostRepository.delete(this.id);
            if (data.success) {
                this.$message({
                    message: data.message,
                    type: 'success'
                })
                this.handleHideDialog();
                await this.fetch();
            } else {
                this.$message({
                    message: data.message,
                    type: 'error'
                })
            }
        },
        // search
        handleSearch(data) {
            this.listQuery = {
                page: 1,
                limit: 10,
                sort: 'desc',
                title: data.title,
            },
                this.fetch();
        },
    }
}

</script>

<style lang="scss" scoped>
</style>