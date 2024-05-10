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
                    <el-form-item :label="`Tiêu đề (Tiếng Việt)`" prop="vi.title">
                        <el-input
                            :placeholder="`Tiêu đề Tiếng Việt`"
                            v-model="formData.vi.title"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item :label="`Tiêu đề (Tiếng Anh)`" prop="en.title">
                        <el-input
                            :placeholder="`Tiêu đề Tiếng Anh`"
                            v-model="formData.en.title"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-form-item label="Icon" prop="icon">
                        <div class="el-image">
                            <img v-show="formData.icon" :src="formData.icon" alt="">
                        </div>
                        <el-input
                            placeholder="Icon"
                            v-model="formData.icon"
                        >
                            <template #append>
                                <el-button type="primary" @click="handerCkfinder"><i class="ri-upload-cloud-fill"></i>
                                </el-button>
                            </template>
                        </el-input>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <template v-slot:footer>
            <!-- <div class="dialog-footer"> -->
            <el-button @click="closeDialog" size="default"> Hủy</el-button>
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

export default {
    name: 'DialogCategory',
    components: {},
    props: {
        dialogFormVisible: {
            type: Boolean,
            default: false
        },
        dialogStatus: {
            type: String,
            default: 'create'
        },
        titleDialog: {
            type: String,
            default: 'Tạo danh mục mới'
        },
        formData: {
            type: Object,
            default: {
                vi: {
                    title: '',
                },
                en: {
                    title: '',
                },
                icon: ''
            }
        }
    },
    data() {
        return {
            activeName: 'vi',
            isLoading: true,
            dialogVisible: false,
            rules: {
                vi: {
                    title: [
                        {required: true, message: 'Vui lòng nhập tiêu đề', trigger: 'blur'},
                    ]
                },
                en: {
                    title: [
                        {required: true, message: 'Vui lòng nhập tiêu đề', trigger: 'blur'},
                    ]
                },
                icon: {required: true, message: 'Vui lòng chọn icon', trigger: 'blur'}
            },
            isLoadingSave: false,
            isShowDialog: this.dialogFormVisible
        }
    },
    filters: {},
    created() {
    },
    watch: {
        dialogFormVisible(newValue) {
            this.isShowDialog = newValue;
        },
    },
    methods: {
        closeDialog() {
            this.isShowDialog = false;
            this.$emit('hidedialog', {});
        },

        //Save
        saveForm() {
            this.$refs['formData'].validate((valid) => {
                if (valid) {
                    this.isLoadingSave = true;
                    if (this.dialogStatus === 'edit') {
                        const query = {
                            "data": this.formData,
                            "id": this.formData.id,
                        };
                        CategoryPostRepository.update(query).then(response => {
                            const {data} = response
                            if (data.success) {
                                this.$message({
                                    message: data.message,
                                    type: 'success'
                                });
                                this.isLoadingSave = false;
                                this.$emit('updateData');
                            } else {
                                this.$message({
                                    message: data.message,
                                    type: 'error'
                                })
                                this.closeDialog();
                            }
                        }).catch(e => {
                            this.isLoadingSave = false
                        })
                    } else {
                        CategoryPostRepository.create(this.formData).then(response => {
                            const {data} = response;
                            if (data.success) {
                                this.$message({
                                    message: data.message,
                                    type: 'success'
                                });
                                this.$emit('updateData');
                                this.isLoadingSave = false;
                                this.closeDialog();
                            } else {
                                this.$message({
                                    message: data.message,
                                    type: 'error'
                                });
                                this.closeDialog();
                            }
                        }).catch(e => {
                            this.isLoadingSave = false;
                        })
                    }
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        },
        handerCkfinder() {
            this.showCkfinder()
        },
        showCkfinder() {
            const _this = this
            var finder = new CKFinder();

            finder.selectActionFunction = function (fileUrl, data) {
                _this.formData.icon = fileUrl;
            };
            finder.popup();
        }
    }
}

</script>
<style lang="scss" scoped>
:deep(.el-select) {
    width: 100%;
}

.el-image > img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 1px dashed red;
    margin: 10px;
}
</style>