<template>
    <div class="filter-container f-left">
        <el-input placeholder="Tiêu đề" v-model="title" style="width: 400px;" class="filter-item"/>
        <el-button class="filter-item" type="primary" @click="handleSearch">
            <el-icon class="el-icon--left"><Search /></el-icon>Tìm kiếm
        </el-button>
        <el-button class="filter-item" type="primary" @click="handleSearch(null)">
            <el-icon class="el-icon--left"><Minus /></el-icon>Làm mới
        </el-button>
        <el-button class="filter-item" type="primary" @click="showDialog('create')">
            <el-icon class="el-icon--left"><Plus /></el-icon>Thêm
        </el-button>
    </div>
</template>
<script>
    export default {
        name: 'SearchCategory',
        props: {
            titleFilter: {
                type: String,
                default: ''
            }
        },
        data () {       
            return {
                title: this.titleFilter,
                category_id: ''
            }
        },
        computed: {
        },
        filters:{
            
        },

        created() {

        },

        mounted() {
        },

        methods: {
            showDialog(status){
                let data = { 'status': status }
                this.$emit('showdialog', data)
            },
            handleSearch(data) {
                if (!data) {
                    this.title = '';
                    this.category_id = '';
                }
                this.$emit('search', {
                    title: this.title
                })
            }
        }
    }

</script>
<style lang="scss" scoped>
    :deep(.el-input__wrapper) {
        width: calc(100% - 12px);
    }
</style>