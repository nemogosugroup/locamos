<template>
    <div class="wrap-details" :style="background">
        <el-row justify="center">
            <el-col :span="12">
                <div class="map-detail">
                    <div class="wrap-slider">
                        <swiper
                            :style="{
                            '--swiper-navigation-color': '#fff',
                            '--swiper-pagination-color': '#fff',
                            }"
                            :spaceBetween="0"
                            :navigation="true"
                            :thumbs="{ swiper: thumbsSwiper }"
                            :modules="modules"
                            class="mySwiperFeature"
                        >
                            <swiper-slide v-for="(images, i) in detailData.images" :key="i">
                                <img :src="images" />
                            </swiper-slide>                            
                        </swiper>
                        <swiper
                            @swiper="setThumbsSwiper"
                            :spaceBetween="0"
                            :slidesPerView="4"
                            :freeMode="true"
                            :watchSlidesProgress="true"
                            :modules="modules"
                            class="mySwiperThump"
                        >
                            <swiper-slide v-for="(images, i) in detailData.images" :key="i">
                                <img :src="images" />
                            </swiper-slide> 
                        </swiper>
                    </div>
                    <div class="info">
                        <el-table :data="tableData" border style="width: 100%" class="table-info">
                            <el-table-column align="center" prop="key" :label="$t('name')" width="230" />
                            <el-table-column align="center" prop="value" :label="$t('value')" />
                        </el-table>
                    </div>
                </div>
            </el-col>
        </el-row>
    </div>
</template>
<script>
import { ref } from 'vue';
import RepositoryFactory from '@/utils/RepositoryFactory';
const mapRepository = RepositoryFactory.get('map');
import bgDetail from "@/assets/images/bg/bg_detail.png";
import { Swiper, SwiperSlide } from 'swiper/vue';

// Import Swiper styles
import 'swiper/css';

import 'swiper/css/free-mode';
import 'swiper/css/navigation';
import 'swiper/css/thumbs';
// import required modules
import { FreeMode, Navigation, Thumbs } from 'swiper/modules';

export default {
    name: 'DetailMap',
    components: {
      Swiper,
      SwiperSlide,
    },
    setup() {
      const thumbsSwiper = ref(null);

      const setThumbsSwiper = (swiper) => {
        thumbsSwiper.value = swiper;
      };

      return {
        thumbsSwiper,
        setThumbsSwiper,
        modules: [FreeMode, Navigation, Thumbs],
      };
    },
    data() {
        return {
            listQuery: {
                locale:this.$i18n.locale,
                id:''
            },
            detailData:{},
            tableData:[],
        }
    },
    computed: {
        background(){
            return `background-image: url(${bgDetail})`;
        },
    },
    mounted() {
        this.emitter.on("change-locale", data => {
            const id = this.$route.params && this.$route.params.id;
            this.fetch(id);
        });
    },
    created() {
        this.emitter.off("change-locale");
        const id = this.$route.params && this.$route.params.id;
        this.fetch(id);
    },

    methods: {
        async fetch(id) {
            this.listQuery.id = id;
            const { data } = await mapRepository.store(this.listQuery);
            if (data.success) {
                this.detailData = data.data
                this.detailData.images.push(this.detailData.feature_image);
                this.tableData = [
                    {
                        key: this.$i18n.t('address'),
                        value: this.detailData.title,
                    },
                    {
                        key: this.$i18n.t('manager'),
                        value: this.detailData.manager,
                    },
                    {
                        key: this.$i18n.t('lat'),
                        value: this.detailData.lat,
                    },
                    {
                        key: this.$i18n.t('lng'),
                        value: this.detailData.lng,
                    },
                ]
            }
        },
    },
}
</script>

<style lang="scss" scoped>
@import "~@style/variables.scss";
.wrap-details {
    padding:$size40 0;
    background-repeat: no-repeat;
    background-size:cover;
    min-height: 100vh;
    .wrap-slider {
        .mySwiperThump {
            .swiper-slide {
                width: 100%;
                height: 100px;
                cursor: pointer;
                &::before {
                    position: absolute;
                    content: '';
                    width: 100%;
                    height: 100%;
                    background-color: rgba($color: #000000, $alpha: 0.7);
                    left: 0;
                    top: 0;
                    transition: all .3s ease-in-out;
                }
                &.swiper-slide-thumb-active {
                    &::before {
                        background-color: rgba($color: #000000, $alpha: 0);
                    }
                }
                &:hover{
                    &::before {
                        background-color: rgba($color: #000000, $alpha: 0);
                        transition: all .3s ease-in-out;
                    }
                }
                img {
                    width: 100%;
                    object-fit: cover;
                }
            }
        }
    }    
    .table-info {
        margin-top:$size40;
        width: 100%;
        border-spacing: 0;
        color: #fff;
        border-radius: 15px;
        border-style: hidden;
        box-shadow: 0 0 0 1px hsla(0, 0%, 98.8%, .4);
        background-color: transparent;
        text-align: center !important;
        :deep(tr) {
            background-color: transparent !important;
            font-size: 18px;
        }
        :deep(td) {
            background-color: transparent !important;
            padding: 15px;
        }
        :deep(th) {
            background-color: transparent !important;
            padding: 15px;
            color: #fff;
        }
    }
}

</style>
