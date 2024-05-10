<template>
    <div class="app-container">
        <search-post :title-filter="listQuery.title" :list-categories="listCategories" @showdialog="handleShowDialog" @search="handleSearch"></search-post>
        <table-data @showdialog="handleShowDialog" :list="list" :isLoading="isLoading"></table-data>
        <pagination
            v-show="total>10"
            v-bind:total="total"
            v-model:page="listQuery.page"
            v-model:limit="listQuery.limit"
            layout="prev, pager, next"
            @pagination="fetch"/>
        <dialog-post :dialog-form-visible="dialogFormVisible" :dialog-status="dialogStatus" :formData="formData"
                     :title-dialog="titleDialog" @hidedialog="handleHideDialog" :list-categories="listCategories"
                     @update-data="handleUpdateData"></dialog-post>
        <dialog-remove :title-dialog="titleDialog" :title="title" :dialog-status="dialogStatus"
                       :dialog-visible-remove="dialogVisibleRemove" @hiddendialog="handleHideDialog"
                       @delete="handleRemove"></dialog-remove>
    </div>
</template>

<script>
import Pagination from '@/components/Pagination'
import SearchPost from '@/backend/components/SearchPost';
import TableData from './components/Data';
import DialogPost from './components/Dialog';
import DialogRemove from '@/backend/components/RemovePost';
import AdminRepositoryFactory from '@/backend/respository';

const PostRepository = AdminRepositoryFactory.get('post');

export default {
    name: 'Posts',
    components: {Pagination, SearchPost, TableData, DialogPost, DialogRemove},
    data() {
        return {
            list: [],
            listQuery: {
                page: 1,
                limit: 10,
                sort: 'desc',
                title: '',
            },
            listCategories: [],
            isLoading: true,
            dialogStatus: '',
            dialogFormVisible: false,
            dialogVisibleRemove: false,
            titleDialog: '',
            total: 0,
            formData: {
                vi: {
                    title: '',
                    description: '',
                    content: '',
                },
                en: {
                    title: '',
                    description: '',
                    content: '',
                },
                category_id: '',
                manager: '',
                lat: '',
                long: '',
                feature_image: '',
                images: ''
            },
            id: false,
            title: '',
        }
    },

    filters: {},

    created() {
        this.fetch();
    },

    methods: {
        async fetch() {
            this.isLoading = true;
            const {data} = await PostRepository.list(this.listQuery);
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
        // show dialog
        handleShowDialog(data) {
            this.dialogStatus = data.status ? data.status : 'create';
            this.titleDialog = this.dialogStatus === 'create' ? 'Tạo vị trí mới' : 'Chỉnh sửa vị trí';
            if (data.status === 'delete') {
                this.dialogVisibleRemove = true;
                this.id = data.id;
                this.title = data.title;
                this.titleDialog = 'Bạn có muốn xoá vị trí'
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
                    'category_id': item.category_id,
                    'manager': item.manager,
                    'lat': item.lat,
                    'long': item.long,
                    'feature_image': item.feature_image,
                    'images': item.images
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
                vi: {
                    title: '',
                    description: '',
                    content: '',
                },
                en: {
                    title: '',
                    description: '',
                    content: '',
                },
                category_id: '',
                manager: '',
                lat: '',
                long: '',
                feature_image: '',
                images: ''
            }
        },
        // update data
        handleUpdateData() {
            this.fetch();
            this.handleHideDialog();
        },
        // remove
        async handleRemove() {
            const {data} = await PostRepository.delete(this.id);
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
                category_id: data.category_id,
            },
                this.fetch();
        },
    }
}

</script>

<style lang="scss" scoped>
</style>