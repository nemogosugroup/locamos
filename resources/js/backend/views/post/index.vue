<template>    
    <div class="app-container">  
        <search-post :list-query="listQuery.title" @showdialog="handleShowDialog" @search="handleSearch"></search-post>
        <table-data @showdialog="handleShowDialog" :list="list" :isLoading="isLoading"></table-data>
        <pagination 
            v-show="total>10" 
            v-bind:total="total" 
            v-model:page="listQuery.page" 
            v-model:limit="listQuery.limit"
            layout="prev, pager, next"  
        @pagination="fetch" />
        <dialog-course :dialog-form-visible="dialogFormVisible" :dialog-status="dialogStatus" :formData="formData" :title-dialog="titleDialog" @hidedialog="handleHideDialog" :list-categories="listCategories" @update-data="handleUpdateData"></dialog-course>
        <dialog-remove :title-dialog="titleDialog" :title="title" :dialog-status="dialogStatus" :dialog-visible-remove="dialogVisibleRemove" @hiddendialog="handleHideDialog" @delete="handleRemove"></dialog-remove>
    </div>
</template>

<script>
    import Pagination from '@/components/Pagination'
    import SearchPost from '@/backend/components/SearchPost';
    import TableData from './components/Data';
    import DialogCourse from './components/Dialog';
    import DialogRemove from '@/backend/components/RemovePost';
    import AdminRepositoryFactory from '@/backend/respository';
    const CourseRepository = AdminRepositoryFactory.get('post');
    import { Helper } from '@/backend/helper';
    export default {
        name: 'Courses',
        components: { Pagination, SearchPost, TableData, DialogCourse, DialogRemove},
        data () {        
            return {
                list: [],
                listQuery: {
                    page: 1,
                    limit: 10,
                    sort: 'desc',
                    title:'',
                },  
                listCategories:[],            
                isLoading: true,
                dialogStatus:'',
                dialogFormVisible:false,
                dialogVisibleRemove:false,
                titleDialog:'',
                total:0,
                formData:{
                    title: '',
                    category_id: '',
                    description: '',
                    content: '',
                    url_image: '',
                    gold: 0,
                },
                id:false,
                title:'',
            }            
        },

        filters:{
            
        },

        created() {
            this.fetch();
        },

        methods: {
            async fetch(){
                this.isLoading = true;     
                const { data } = await CourseRepository.list( this.listQuery );
                this.isLoading = false;                  
                if(data.success) {
                    const results = data.data.data;
                    this.listCategories = data.categories;                    
                    this.list = results.map(item => {
                        item.edit = false;
                        item.isLoadingSave = false;
                        item.isLoadingDelete = false;
                        item.excerpt = Helper.excerpt(item.description, 15)
                        return item
                    });
                    this.total = data.data.total;
                }
            },
            // show dialog
            handleShowDialog(data){             
                this.dialogStatus = data.status ? data.status : 'create';
                this.titleDialog = this.dialogStatus == 'create' ? 'Tạo khoá học mới' : 'Chỉnh sửa khoá học';
                if (data.status == 'delete') {
                    this.dialogVisibleRemove = true;
                    this.id = data.id;
                    this.title = data.title;
                    this.titleDialog = 'Bạn có muốn xoá khoá học'
                }else{
                    this.dialogFormVisible = true;
                }
                
                if (data.status == 'edit') {
                    let item = data.item;
                    this.formData = {
                        'title':item.title,
                        'category_id':item.category_id,
                        'id':item.id,
                        'description':item.description,
                        'content':item.content,
                        'url_image':item.url_image,
                        'gold':item.gold
                    }
                }
            },
            // hide dialog
            handleHideDialog(){
                this.dialogStatus = '';
                this.dialogFormVisible = false;
                this.dialogVisibleRemove = false;
                this.titleDialog = '';
                this.formData = {
                    title: '',
                    category_id: '',
                    description: '',
                    content: '',
                    url_image: '',
                    gold: 0,
                }
            },
            // update data
            handleUpdateData(data){
                switch (data.status) {
                    case 'edit':
                        this.list.filter( (item) => {
                            if (item.id == data.item.id) {
                                let results = data.item;
                                item.edit = false;
                                item.isLoadingSave = false;
                                item.isLoadingDelete = false;
                                item.excerpt = Helper.excerpt(item.description, 15);
                                item = Object.assign(item, results);
                            }
                        })
                        break;
                    default:
                        data.item.excerpt = Helper.excerpt(data.item.description, 15);
                        this.list.unshift(data.item);
                        break;
                }
                this.handleHideDialog();
            },
            // remove
            async handleRemove(){
                const { data } = await CourseRepository.delete( this.id );
                if (data.success) {
                    this.$message({
                        message: data.message,
                        type: 'success'
                    })
                    this.handleHideDialog();
                    this.fetch();
                }else{
                    this.$message({
                        message: data.message,
                        type: 'error'
                    })
                }
            },
            // search 
            handleSearch(data){
                this.listQuery = {
                    page: 1,
                    limit: 10,
                    sort: 'desc',
                    title:data,
                }, 
                this.fetch();
            },
        }
    }

</script>

<style lang="scss" scoped>
</style>