<template>
    <!-- Dialog Update -->
    <el-dialog :title="titleDialog" v-model="isShowDialog" @close="closeDialog">
        <el-form
            ref="formData"
            :model="formData"
            v-bind:rules="rules"
            label-width="160px"
        >
            <el-form-item label="Tiêu đề" prop="title">
                <el-input
                    placeholder="Tiêu đề khoá học"
                    v-model="formData.title"
                ></el-input>
            </el-form-item>
            <el-form-item label="Danh mục" prop="category_id">
                <el-select v-model="formData.category_id" class="m-2" placeholder="Danh mục">
                    <el-option
                    v-for="item in listCategories"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="Mô tả" prop="description">
                <el-input
                    class="m-2"
                    v-model="formData.description"
                    autosize
                    type="textarea"
                    placeholder="Mô tả"
                />
            </el-form-item>
            <!-- <el-form-item label="Nột dung" prop="description">
                <ckeditor :editor="editor" v-model="formData.content" :config="editorConfig"></ckeditor>
            </el-form-item> -->
            <el-form-item label="Hình ảnh" prop="url_image">
                <el-input
                    placeholder="Hình ảnh"
                    v-model="formData.url_image"
                >
                <template #append>
                    <el-button type="primary" @click="handerCkfinder"><i class="ri-upload-cloud-fill"></i></el-button>
                </template>
                </el-input>                
            </el-form-item>
            <el-form-item label="gold" prop="gold">
                <el-input-number :min="1" v-model="formData.gold" :step="1" />
            </el-form-item>
        </el-form>
        <template v-slot:footer>
            <!-- <div class="dialog-footer"> -->
                <el-button @click="closeDialog" size="default"> Hủy </el-button>
                <el-button
                    size="default"
                    type="primary"
                    @click="saveForm()"
                    v-bind:loading="isLoadingSave"
                >
                    Lưu
                </el-button>
            <!-- </div> -->
        </template>
    </el-dialog>
</template>
<script>
    //import CKEditor from '@ckeditor/ckeditor5-vue';
    //import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import AdminRepositoryFactory from '@/backend/respository';
    const CourseRepository = AdminRepositoryFactory.get('course');
    export default {
        name: 'DialogCourse',
        //components: { ckeditor: CKEditor.component},
        props:{
            dialogFormVisible:{
                type: Boolean,
                default: false
            },
            dialogStatus:{
                type: String,
                default: 'create'
            },
            titleDialog:{
                type: String,
                default: 'Tạo khoá học mới'
            },
            formData:{
                type:Object,
                default:{
                    title: '',
                    category_id: '',
                    description: '',
                    content: '',
                    url_image: '',
                    gold: 0,
                }
            },
            listCategories:{
                type:Array,
                default:[]
            }
        },
        data () {            
            return {
                isLoading: true,
                list: [],           
                isLoading: true,
                dialogVisible: false,
                isLoadingService: false,
                rules: {
                    title: [
                        { required: true, message: 'Vui lòng nhập tiêu đề', trigger: 'blur' },
                    ],
                    category_id: [
                        { required: true, message: 'Vui lòng chọn danh mục', trigger: 'blur' },
                    ],
                    description: [
                        { required: true, message: 'Vui lòng nhập mô tả', trigger: 'blur' },
                    ],
                    url_image: [
                        { required: true, message: 'Vui lòng chọn hình ảnh', trigger: 'blur' },
                    ],
                    gold: [
                        { required: true, message: 'Vui lòng nhập giá', trigger: 'blur' },
                    ],
                },
                isLoadingSave:false,
                isShowDialog: this.dialogFormVisible,
                // editor: ClassicEditor,
                // editorData: '',
                // editorConfig: {
                //     // The configuration of the editor.
                // }
            }            
        },

        filters:{
            
        },

        created() {
        },
        watch: {
            dialogFormVisible(newValue) {
                this.isShowDialog = newValue;
            },

        },
        methods: {
            closeDialog() {
                this.isShowDialog = false
                this.$emit('hidedialog', {})
            },

            //Save
            saveForm(){
                this.$refs['formData'].validate((valid) => {
                    if (valid) {
                        //this.isLoadingSave = true
                        let status = this.dialogStatus;
                        if(this.dialogStatus == 'edit'){ 
                            let dataUpdate = {
                                'title' : this.formData.title,
                                'category_id' : this.formData.category_id,
                                'description' : this.formData.description,
                                'gold' : this.formData.gold,
                                'content' : this.formData.content,
                                'url_image' : this.formData.url_image,
                            }                                           
                            const query = {
                                "data": dataUpdate,
                                "id": this.formData.id,
                            }
                            CourseRepository.update(query).then( response => {
                                const { data } = response
                                this.isLoadingSave = false;
                                if(data.success){
                                    this.$message({
                                        message: data.message,
                                        type: 'success'
                                    });
                                    let item = data.data;
                                    item.edit = false;
                                    item.isLoadingSave = false;
                                    item.isLoadingDelete = false;
                                    this.$emit('updateData', {'item':item, 'status':status});
                                }else{
                                    this.$message({
                                        message: data.message,
                                        type: 'error'
                                    })
                                    this.closeDialog();
                                }
                            }).catch( e => {
                                this.isLoadingSave = false
                            })
                        }else{
                            CourseRepository.create(this.formData).then( response => {
                                this.isLoadingSave = false
                                const { data } = response;
                                if(data.success){
                                    this.$message({
                                        message: data.message,
                                        type: 'success'
                                    })
                                    let item = data.data;
                                    item.edit = false;
                                    item.isLoadingSave = false;
                                    item.isLoadingDelete = false;
                                    this.$emit('updateData', {'item':item, 'status':status});
                                }else{
                                    this.$message({
                                        message: data.message,
                                        type: 'error'
                                    });
                                    this.closeDialog();
                                }
                            }).catch(e => {
                                this.isLoadingSave = false
                            })
                        }
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },

            // 
            handerCkfinder(){
                this.showCkfinder()
            },

            showCkfinder() {
                const _this = this                
                var finder = new CKFinder();

                finder.selectActionFunction = function(fileUrl, data){
                    _this.formData.url_image = fileUrl;
                };
                finder.popup();
            },
        }
    }

</script>
<style lang="scss" scoped>
    :deep(.el-select) {
        width: 100%;
    }
</style>