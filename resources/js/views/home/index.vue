<template>
    <el-row>
        <el-col :id="`filterTab`" :span="12" :xl="8">
            <div class="homeListMap">
                <div class="search">
                    <el-row>
                        <el-col :span="8">
                            <el-select-v2 filterable v-model="listQuery.category_id" style="width: calc(100%);"
                                :placeholder="$t('choose_category')" :options="listCategories" :props="optionProps"
                                @change="handleCategory()" clearable>
                            </el-select-v2>
                        </el-col>
                        <el-col :span="16">
                            <el-input v-model="listQuery.title" size="large" :placeholder="$t('search')">
                                <template #append>
                                    <el-button @click="handleSearch()"><i class="ri-search-line"></i></el-button>
                                </template>
                            </el-input>
                        </el-col>
                    </el-row>
                </div>
                <div class="wrap-listCard">
                    <el-scrollbar wrap-class="scrollbar-wrapper" height="calc(100vh - 225px)">
                        <div v-for="item in listData" :key="item.id" class="card">
                            <div class="images">
                                <img :src="item.feature_image" :alt="item.title" />
                            </div>

                            <div class="info">
                                <h6>{{ excerpt(item.title, 6) }}</h6>
                                <p class="name"><strong>{{ $t('manager') }}</strong> {{ item.manager }}</p>
                                <p>Latitude: {{ item.lat }}</p>
                                <p>Longitude: {{ item.long }}</p>
                                <div class="d-flex">
                                    <el-button @click="handleButtonClick(item)" type="primary" plain>
                                        <i class="ri-send-plane-fill"></i>
                                    </el-button>
                                    <el-button class="button-more">
                                        <router-link :to="'/map/' + item.id">
                                            <span>{{ $t('readmore') }}</span>
                                        </router-link>
                                    </el-button>
                                </div>
                            </div>
                        </div>
                    </el-scrollbar>
                    <pagination v-show="total > 10" v-bind:total="total" v-model:page="listQuery.page"
                        v-model:limit="listQuery.limit" layout="prev, pager, next" @pagination="fetch" />
                </div>
            </div>
        </el-col>
        <el-col :id="`showingMap`" :span="12" :xl="16">
            <!--  -->
            <GoogleMap :api-key="GOOGLE_MAP_KEY" style="width: 100%; height: 100%;" :center="center"
                :mapTypeId="`roadmap`" :gestureHandling="`greedy`" :zoom="zoom" @zoom_changed="handleChangeZoom"
                ref="mapRef">
                <MarkerCluster>
                    <Marker v-for="(location, i) in listAlls" :key="i" :options="{
            position: {
                lat: parseFloat(location.lat), lng: parseFloat(location.lng)
            },
            label: {
                text: setIcon(location.category.id),
                fontFamily: 'Material Icons',
                color: setColorIcon(location.category.id),
                fontSize: '16px',
                //fillColor: '#ed9534'
                className: 'marker-position',
            },
            icon: {
                //url: location.category.icon,
                path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z',
                // anchor: { x: 35, y: 35 },
                //scaledSize: { height: 40, width: 40 },
                fillColor: setColor(location.category.id),
                fillOpacity: 1,
                strokeWeight: 0,
                rotation: 0,
                scale: 1.2,
            }
        }" @click="handleMarkerClick(location)" ref="markers">
                        <InfoWindow v-model="infoWindowVisible[i]">
                            <div class='locale'>
                                <div class='images'>
                                    <img :src="location.feature_image" :alt="location.title">
                                </div>
                                <div class='info-locale'>
                                    <h5>{{ location.title }}</h5>
                                    <p class="name"><strong>{{ $t('manager') }}: </strong>{{ location.manager }}</p>
                                    <p>Latitude: {{ location.lat }}</p>
                                    <p>Longitude: {{ location.lng }}</p>
                                    <div class="d-flex">
                                        <el-button class="button-more">
                                            <router-link :to="'/map/' + location.id">
                                                <span>{{ $t('readmore') }}</span>
                                            </router-link>
                                        </el-button>
                                    </div>
                                </div>
                            </div>
                        </InfoWindow>
                    </Marker>
                </MarkerCluster>
            </GoogleMap>
        </el-col>
    </el-row>

</template>
<script>
import Pagination from '@/components/Pagination'
import RepositoryFactory from '@/utils/RepositoryFactory';
const mapRepository = RepositoryFactory.get('map');
import { ElLoading } from 'element-plus';
import { mapState } from "vuex";
import { GoogleMap, Marker, MarkerCluster, InfoWindow } from 'vue3-google-map';

export default {
    name: 'Home',
    components: { Pagination, GoogleMap, Marker, MarkerCluster, InfoWindow },
    data() {
        return {
            // center:{ lat: -28.024, lng: 140.887 },
            center: { lat: 15.9030623, lng: 105.8066925 },
            listData: [],
            listAlls: [],
            zoom: 4,
            mapZoom: 12,
            listQuery: {
                page: 1,
                limit: 10,
                sort: 'desc',
                title: '',
                category_id: null,
                locale: this.$i18n.locale
            },
            listQueryAll: {
                sort: 'desc',
                title: '',
                locale: this.$i18n.locale
            },
            total: 0,
            infoWindowVisible: [],
            optionProps: {
                label: 'title',
                value: 'category_id',
            },
            listCategories: []
        }
    },
    computed: {},
    mounted() {
        this.emitter.on("change-locale", data => {
            this.markers = [],
                this.map = null,
                this.infowindow = null,
                this.listQuery = {
                    page: 1,
                    limit: 20,
                    sort: 'desc',
                    title: '',
                    category_id: null,
                    locale: this.$i18n.locale
                },
                this.fetch();
            this.fetchAll();
        });
    },
    async created() {
        this.emitter.off("change-locale");
        this.fetch();
        this.fetchAll();
    },

    methods: {
        handleChangeZoom() {
            this.zoom = this.$refs.mapRef.map.zoom;
        },
        async fetch() {
            const loading = ElLoading.service({
                lock: true,
                background: 'rgba(0, 0, 0, 0.7)'
            });
            this.listQuery.locale = this.$i18n.locale;
            const { data } = await mapRepository.list(this.listQuery);
            loading.close();
            if (data.success) {
                const results = data.data.data;
                this.listData = results.map(item => {
                    return item
                });
                this.total = data.data.total;
                this.listCategories = data.data.categories;
            }
        },
        async fetchAll() {
            this.listQueryAll.locale = this.$i18n.locale;
            const { data } = await mapRepository.all(this.listQueryAll);
            if (data.success) {
                const results = data.data;
                this.listAlls = results.map((item, index) => {
                    return item;
                });
            }
        },
        excerpt(desc, number) {
            if (desc && desc.length > 0) {
                desc = desc.split(' ');
                if (desc.length > number) {
                    desc = desc.slice(0, number).join(' ') + "...";
                } else {
                    desc = desc.join(' ')
                }
            }
            return desc;
        },
        handleButtonClick(location) {
            this.emitter.emit("is-toggle-filter-tab");
            let markerIndex = this.listAlls.findIndex(item => item.id === location.id);// Chỉ số của marker bạn muốn pan đến;
            this.infoWindowVisible = [];
            if (markerIndex !== -1) {
                this.handleMarkerClick(location);
            }
        },
        handleMarkerClick(location) {
            this.infoWindowVisible = []
            this.center = { lat: parseFloat(location['lat']), lng: parseFloat(location['lng']) };
            this.zoom = 17;
            const index = this.listAlls.findIndex(loc => loc.id === location.id);
            this.infoWindowVisible[index] = true;
        },
        //search
        handleSearch(data) {
            this.listQuery.page = 1;
            this.listQuery.limit = 10;
            this.fetch();
        },
        handleCategory() {
            this.listQuery.page = 1;
            this.listQuery.limit = 10;
            this.fetch();
        },
        //icon
        setIcon(id) {
            return id == 1 ? "\ue558" : (id == 2 ? "\ue546" : "\uea0b");
        },
        setColorIcon(id) {
            return id == 2 ? "orange" : "white";
        },
        setColor(id) {
            return id == 1 ? "orange" : (id == 2 ? "blue" : "green");
        }
    },
}
</script>
<style>
.marker-position {
    bottom: 35px;
    left: 0;
    position: relative;
}
</style>
<style lang="scss" scoped>
@import "~@style/variables.scss";

.search {
    width: calc(100% - 60px);
    margin: 30px 0 10px 40px;

    :deep(.el-select__wrapper) {
        height: 40px;
    }
}

.locale {
    img {
        width: 220px;
        height: 150px;
        object-fit: cover;
    }

    .info-locale {
        h5 {
            text-transform: capitalize;
        }
    }
}

.wrap-listCard {

    // padding: 0 30px;
    .card {
        display: flex;
        flex-direction: column;
        // width: 50%;
        border-radius: $size16;
        height: -webkit-max-content;
        height: max-content;
        box-shadow: 0 6px 6px #0000001c;

        .images {
            width: 100%;
            height: 210px;
            flex-shrink: 0;

            img {
                width: 100%;
                object-fit: cover;
                height: 100%;
                border-top-left-radius: 16px;
                border-top-right-radius: 16px;
            }
        }

        .info {
            padding: 24px;
            border-bottom-left-radius: $size16;
            border-bottom-right-radius: $size16;

            h6 {
                font-size: $size16;
                margin: 0 0 $size10;
            }

            p {
                font-size: $size14;
                color: $color-gray-5;
                margin: 0 0 8px;

                &.name {
                    strong {
                        color: $colorBlack;
                    }

                    color: $color-accent-5;
                }
            }

            .button-more {
                color: $colorBlack;
                background-color: $orange;
                width: 100%;
                transition: all .3s ease-in-out;
                font-weight: 700;

                &:hover {
                    transition: all .3s ease-in-out;
                    background: $bgWhite;
                }
            }
        }
    }
}

:deep(.el-scrollbar__view) {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 20px;
    margin: 0 0px 0 20px;
    //height: calc(100vh - 225px);
    overflow-y: auto;
    padding: 20px;
}

.pagination-container {
    margin: 0;
    padding: 20px 40px
}

@media screen and (max-width: 1199px) {
    #filterTab {
        display: none;
    }

    #filterTabShow {
        max-width: 100%;
        flex: 0 0 100%;
    }

    #showingMap {
        width: 100% !important;

        >div {
            min-height: calc(100vh - 55px);
            min-width: 100vw;
        }
    }

    :deep(.el-scrollbar__view) {
        grid-template-columns: repeat(3, 1fr);
        margin: 0;
    }

    .wrap-listCard .card .info h6 {
        min-height: 37px;
    }

    .search {
        width: 95%;
        margin: 1% 2.5%;
    }
}

@media screen and (max-width: 768px) {
    :deep(.el-scrollbar__view) {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media screen and (max-width: 480px) {
    :deep(.el-scrollbar__view) {
        grid-template-columns: repeat(1, 1fr);
    }
}
</style>