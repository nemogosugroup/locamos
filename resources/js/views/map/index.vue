<template>
    <el-row>
        <el-col :span="12">
            <div class="map-detail">
            </div>
        </el-col>
    </el-row>
</template>
<script>
import RepositoryFactory from '@/utils/RepositoryFactory';
const mapRepository = RepositoryFactory.get('map');
import { ElLoading } from 'element-plus'
export default {
    name: 'DetailMap',
    components: { },
    data() {
        return {
            listQuery: {
                locale:this.$i18n.locale,
                id:''
            }
        }
    },
    computed: {
    },
    mounted() {
    },
    created() {
        const id = this.$route.params && this.$route.params.id;
        console.log('id', id);
        this.fetch(id);
    },

    methods: {
        async fetch(id) {
            // const loading = ElLoading.service({
            //     lock: true,
            //     background: 'rgba(0, 0, 0, 0.7)'
            // });
            this.listQuery.id = id;
            const { data } = await mapRepository.store(this.listQuery);
            console.log('data', data);
            //loading.close();
            // if (data.success) {
            //     const results = data.data.data;
            //     this.listData = results.map(item => {
            //         return item
            //     });
            //     this.total = data.data.total;
            // }
        },
    },
}
</script>

<style lang="scss" scoped>
@import "~@style/variables.scss";
</style>
