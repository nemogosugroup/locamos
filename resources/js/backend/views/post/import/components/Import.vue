<template>
    <el-card>
        <h2>Import</h2>
        <el-form
            ref="formData"
            :model="formData"
            v-bind:rules="rules"
        >
            <el-row :gutter="20">
                <el-col :span="8">
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
                <el-col :span="6">
                    <el-form-item label="File" prop="file">
                        <div class="import_file_wrapper">
                            <el-input v-model="fileName" disabled></el-input>
                            <el-button class="btn_upload" type="success" @click="$refs.import_file.click()">
                                Chọn File
                            </el-button>
                            <input
                                id="fileImport"
                                type="file"
                                ref="import_file"
                                accept=".xlsx, .xls, .csv"
                                @change="uploadFile">
                        </div>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-button
                        size="default"
                        type="primary"
                        @click="submitImportForm"
                        v-bind:loading="isLoadingSave"
                    >
                        Import
                    </el-button>
                </el-col>
            </el-row>
        </el-form>
    </el-card>
</template>
<script>

import AdminRepositoryFactory from '@/backend/respository';

const ImportLogRepository = AdminRepositoryFactory.get('import');

export default {
    name: 'ImportLog',
    props: {
        isLoading: {
            type: Boolean,
            default: false
        },
        listCategories: {
            type: Array,
            default: []
        },
    },
    data() {
        return {
            formData: {
                category_id: '',
                file: null
            },
            fileName: '',
            rules: {
                category_id: [
                    {required: true, message: 'Vui lòng chọn danh mục', trigger: 'blur'},
                ],
                file: [
                    {required: true, message: 'Vui lòng chọn file', trigger: 'blur'},
                ],
            },
            isLoadingSave: false,
            isShowStat: false,
            statImport: {}
        }
    },
    created() {
    },
    methods: {
        uploadFile() {
            this.formData.file = this.$refs.import_file.files[0];
            this.fileName = this.formData.file?.name;
        },
        submitImportForm() {
            this.$refs['formData'].validate((valid) => {
                if (valid) {
                    this.isLoadingSave = true;
                    const formData = new FormData();
                    formData.append('category_id', this.formData.category_id);
                    formData.append('file', this.formData.file);
                    ImportLogRepository.import(formData).then(response => {
                        this.isLoadingSave = false;
                        const {data} = response;
                        if (data.success) {
                            this.$message({
                                message: data.message,
                                type: 'success'
                            });
                            this.fileName = null;
                            this.formData.category_id = '';
                            this.formData.file = null;
                            this.isLoadingSave = false;
                            this.emitter.emit('is-imported');

                            document.getElementById('fileImport').type = '';
                            document.getElementById('fileImport').type = 'file';
                        } else {
                            this.$message({
                                message: data[0].message,
                                type: 'error'
                            });
                        }
                    }).catch(e => {
                        this.isLoadingSave = false
                    });
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        }
    }
}

</script>
<style lang="scss" scoped>
.import_file_wrapper {
    position: relative;
    width: 100%;
    top: -2px;
    .btn_upload {
        position: absolute;
        right: 0;
        top: 2px;
    }
    #fileImport {
        position: absolute;
        left: 0;
        z-index: -1;
    }
}
</style>