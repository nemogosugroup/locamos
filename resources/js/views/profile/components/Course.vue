<template>
    <el-row :gutter="20">
        <el-col  v-for="(item, index) in list" :key="index" :span="8">
            <el-card>
                <el-image style="width: 100%; height: 150px" :src="item.url_image" fit="cover" />
                <div class="content">
                    <el-text truncated tag="b" class="w-150px mb-1">{{ item.title }}</el-text>
                    <div class="bottom">
                        <el-button text class="button">
                            <strong><i class="ri-money-dollar-circle-fill"></i></strong>
                            <span>{{ item.gold }}</span>
                        </el-button>
                    </div>
                </div>
            </el-card>
        </el-col>
    </el-row>
</template>

<script>
export default {
    props: {
        list: {
            type: Array,
            default:[],
        },
    },
    data() {
        return {
        };
    },
};
</script>
