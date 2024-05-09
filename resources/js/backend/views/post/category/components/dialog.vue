<template>
    <!-- Dialog Update -->
    <el-dialog :title="titleDialog" v-model="isShowDialog" @close="closeDialog">
        <el-form
            ref="formData"
            :model="formData"
            v-bind:rules="rules"
            label-width="160px"
        >
            <el-row :gutter="20">
                <el-col :span="12">
                    <span class="label-form">Tiếng Việt</span>
                    <el-form-item :label="`Tiêu đề`" prop="vi.title">
                        <el-input
                            :placeholder="`Tiêu đề`"
                            v-model="formData.vi.title"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <span class="label-form">English</span>
                    <el-form-item :label="$t('title')" prop="en.title">
                        <el-input
                            :placeholder="$t('title')"
                            v-model="formData.en.title"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-form-item label="Hình ảnh" prop="icon">
                        <!-- <el-input
                            placeholder="Hình ảnh"
                            v-model="formData.icon"
                        >
                        </el-input> -->
                        <SingleImageUpload :icon="formData.icon" @input="handleIcon"></SingleImageUpload>
                    </el-form-item>
                </el-col>
            </el-row>
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
    import AdminRepositoryFactory from '@/backend/respository';
    const CategoryPostRepository = AdminRepositoryFactory.get('categoryPost');
    import SingleImageUpload from '@/backend/components/UploadImages/SingleImageUpload'
    
    export default {
        name: 'DialogCategory',
        components: { SingleImageUpload },
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
                default: 'Tạo danh mục mới'
            },
            formData:{
                type:Object,
                default:{
                    vi: {
                        title: '',
                    },
                    en: {
                        title: '',
                    },
                    icon:''
                }
            },
            listCategories:{
                type:Array,
                default:[]
            }
        },
        data () {            
            return {
                activeName:'vi',
                isLoading: true,
                list: [],           
                isLoading: true,
                dialogVisible: false,
                isLoadingService: false,
                //formData:this.dataForm,
                rules: {
                    vi:{
                        title: [
                            { required: true, message: 'Vui lòng nhập tiêu đề', trigger: 'blur' },
                        ]
                    },
                    en:{
                        title: [
                            { required: true, message: 'Vui lòng nhập tiêu đề', trigger: 'blur' },
                        ]
                    },
                },
                isLoadingSave:false,
                isShowDialog: this.dialogFormVisible
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
                            }                                           
                            const query = {
                                "data": dataUpdate,
                                "id": this.formData.id,
                            }
                            CategoryPostRepository.update(query).then( response => {
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
                            CategoryPostRepository.create(this.formData).then( response => {                                                             
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

            handleIcon(data){
                this.formData.icon = data
            },
        }
    }

</script>
<style lang="scss" scoped>
    :deep(.el-select) {
        width: 100%;
    }
</style>