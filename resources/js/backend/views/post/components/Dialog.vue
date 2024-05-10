<template>
    <!-- Dialog Update -->
    <el-dialog :title="titleDialog" :width="`90%`" style="margin-top: 50px" v-model="isShowDialog" @close="closeDialog">
        <el-form
            ref="formData"
            :model="formData"
            v-bind:rules="rules"
            label-width="160px"
        >
            <el-row :gutter="20">
                <el-col :span="12">
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
                </el-col>
                <el-col :span="12">
                    <el-form-item :label="`Người quản lý`" prop="manager">
                        <el-input
                            :placeholder="`Người quản lý`"
                            v-model="formData.manager"
                        ></el-input>
                    </el-form-item>
                </el-col>
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
                <el-col :span="12">
                    <el-form-item :label="`Miêu tả (Tiếng Việt)`" prop="vi.description">
                        <el-input
                            type="textarea"
                            :placeholder="`Miêu tả Tiếng Việt`"
                            v-model="formData.vi.description"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item :label="`Miêu tả (Tiếng Anh)`" prop="en.description">
                        <el-input
                            type="textarea"
                            :placeholder="`Miêu tả Tiếng Anh`"
                            v-model="formData.en.description"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item :label="`Nội dung (Tiếng Việt)`" prop="vi.content">
                        <el-input
                            type="textarea"
                            :placeholder="`Nội dung Tiếng Việt`"
                            v-model="formData.vi.content"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item :label="`Nội dung (Tiếng Anh)`" prop="en.content">
                        <el-input
                            type="textarea"
                            :placeholder="`Nội dung Tiếng Anh`"
                            v-model="formData.en.content"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item :label="`Latitude`" prop="lat">
                        <el-input
                            type="number"
                            :placeholder="`Latitude`"
                            v-model="formData.lat"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item :label="`Longitude`" prop="long">
                        <el-input
                            type="number"
                            :placeholder="`Longitude`"
                            v-model="formData.long"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-form-item label="Hình ảnh(bản đồ)" prop="feature_image">
                        <div class="el-image">
                            <img v-show="formData.feature_image" :src="formData.feature_image" alt="">
                        </div>
                        <el-input
                            placeholder="Hình ảnh(bản đồ)"
                            v-model="formData.feature_image"
                        >
                            <template #append>
                                <el-button type="primary" @click="handleCkfinder"><i class="ri-upload-cloud-fill"></i>
                                </el-button>
                            </template>
                        </el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-form-item :label="`Images`">
                        <el-col
                            :span="6"
                            class="el-image"
                            v-show="imagesTmp.length > 0"
                            v-for="(img, idx) in imagesTmp" :key="idx"
                        >
                            <img :src="img" alt="">
                        </el-col>
                        <el-input
                            placeholder="Images"
                            v-model="formData.images"
                        >
                            <template #append>
                                <el-button type="primary" @click="handleMultiCkfinder"><i
                                    class="ri-upload-cloud-fill"></i>
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

const PostRepository = AdminRepositoryFactory.get('post');
export default {
    name: 'DialogPost',
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
            default: 'Tạo vị trí mới'
        },
        formData: {
            type: Object,
            default: {
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
        listCategories: {
            type: Array,
            default: []
        }
    },
    data() {
        return {
            isLoading: true,
            imagesTmp: [],
            dialogVisible: false,
            isLoadingService: false,
            rules: {
                title: [
                    {required: true, message: 'Vui lòng nhập tiêu đề', trigger: 'blur'},
                ],
                category_id: [
                    {required: true, message: 'Vui lòng chọn danh mục', trigger: 'blur'},
                ],
                description: [
                    {required: true, message: 'Vui lòng nhập mô tả', trigger: 'blur'},
                ],
                content: [
                    {required: true, message: 'Vui lòng nhập nội dung', trigger: 'blur'},
                ],
                manager: [
                    {required: true, message: 'Vui lòng nhập người quản lý', trigger: 'blur'},
                ],
                lat: [
                    {required: true, message: 'Vui lòng nhập Latitude', trigger: 'blur'},
                ],
                long: [
                    {required: true, message: 'Vui lòng nhập Longitude', trigger: 'blur'},
                ],
                feature_image: [
                    {required: true, message: 'Vui lòng chọn hình ảnh(bản đồ)', trigger: 'blur'},
                ]
            },
            isLoadingSave: false,
            isShowDialog: this.dialogFormVisible,
        }
    },

    filters: {},

    created() {
    },
    watch: {
        dialogFormVisible(newValue) {
            this.isShowDialog = newValue;
            this.imagesTmp = this.formData.images;
        },
    },
    methods: {
        closeDialog() {
            this.isShowDialog = false
            this.$emit('hidedialog', {})
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
                        }
                        PostRepository.update(query).then(response => {
                            const {data} = response
                            this.isLoadingSave = false;
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
                        PostRepository.create(this.formData).then(response => {
                            this.isLoadingSave = false
                            const {data} = response;
                            if (data.success) {
                                this.$message({
                                    message: data.message,
                                    type: 'success'
                                })
                                this.isLoadingSave = false;
                                this.$emit('updateData');
                                this.closeDialog();
                            } else {
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

        handleCkfinder() {
            this.showCkfinder()
        },

        showCkfinder() {
            const _this = this
            const finder = new CKFinder();

            finder.selectActionFunction = function (fileUrl, data) {
                _this.formData.feature_image = fileUrl;
            };
            finder.popup();
        },

        handleMultiCkfinder() {
            this.showMultiCkfinder();
        },

        showMultiCkfinder() {
            const _this = this
            const finder = new CKFinder();

            finder.selectActionFunction = function (fileUrl, data) {
                const imagesPath = localStorage.getItem('images_path');
                _this.formData.images = imagesPath;
                _this.imagesTmp = imagesPath.split(',');
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

.el-image > img {
    width: 100%;
    max-height: 500px;
    object-fit: cover;
    border: 1px dotted red;
}
</style>