<template>
    <el-table
        ref="dragTable"
        v-loading="isLoading"        
        row-key="id"
        border
        fit
        highlight-current-row
        style="width: 100%"
        @selection-change="handleSelectionChange"
        v-bind:data="list"
    >
        <!-- Check All -->
<!--        <el-table-column-->
<!--            type="selection"-->
<!--            width="40"-->
<!--            align="center">-->
<!--        </el-table-column>-->

        <!-- tiêu đề -->
        <el-table-column min-width="120px" label="Tiêu đề">
            <template v-slot="item">
                <div v-for="(lang, idx) in item.row.translations" :key="idx">
                    <b>{{ lang.locale }} - </b><span>{{ lang.title }}</span><br>
                </div>
            </template>
        </el-table-column>

        <!-- icon -->
        <el-table-column min-width="120px" label="Icon">
            <template v-slot="item">
                <div class="el-image">
                    <img :src="item.row.icon" alt="">
                </div>
            </template>
        </el-table-column>

        <!-- Action -->
        <el-table-column align="center" min-width="50px" label="Sửa">
            <template v-slot="item">
                <!-- Nút sửa -->
                <el-button v-show="item.row.id != 1" type="danger" size="small" @click="showDialog({'status':'delete', 'id':item.row.id, 'title':item.row.title})">Xóa</el-button>
                <el-button type="primary" size="small" @click="showDialog({'status':'edit', 'item':item.row})">Sửa</el-button>
            </template>
        </el-table-column>
    </el-table>
</template>
<script>
    export default {
        name: 'TableCategory',
        props: {
            list: {
                type: Array,
                default: ''
            },
            isLoading:{
                type: Boolean,
                default: false
            },
            isShowCategory:{
                type: Boolean,
                default: false
            },
            search:{
                type: String,
                default: ''
            }
        },
        data () {  
            return {
                multipleSelection: [],
            }           
        },

        filters:{
            
        },

        created() {},

        methods: {
            showDialog(data){
                this.$emit('showdialog', data)
            },
            handleSelectionChange(val) {
                // this.multipleSelection = val;
                // this.listIds = [];
                // if (this.multipleSelection.length > 0) {
                //     let _this = this;
                //     this.multipleSelection.filter(item => {
                //         _this.listIds.push(item.id);
                //     });
                //     this.$emit('listIds', this.listIds);
                // }
            }
        }
    }

</script>
<style lang="scss" scoped>
.el-image > img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border: 1px dashed red;
    margin: 10px;
}
</style>