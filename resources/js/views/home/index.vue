<template>
    <el-row>
        <el-col :span="8">
            <div class="homeListMap">
                <div class="search">
                    <el-input v-model="listQuery.title" size="large" :placeholder="$t('search')">
                        <template #append>
                            <el-button @click="handleSearch()"><i class="ri-search-line"></i></el-button>
                        </template>
                    </el-input>
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
                                    <el-button @click="hanldeMarker(item.id)" type="primary" plain><i
                                            class="ri-send-plane-fill"></i></el-button>
                                    <el-button class="button-more"><a href="#">{{ $t('readmore') }}</a></el-button>
                                </div>
                            </div>
                        </div>
                    </el-scrollbar>
                    <pagination v-show="total > 20" v-bind:total="total" v-model:page="listQuery.page"
                        v-model:limit="listQuery.limit" layout="prev, pager, next" @pagination="fetch" />
                </div>
            </div>
        </el-col>
        <el-col :span="16">
            <div id="map"></div>
        </el-col>
    </el-row>

</template>
<script>
//import { mapGetters } from "vuex";
import { dataDemo } from "./datademo";
import Pagination from '@/components/Pagination'
import RepositoryFactory from '@/utils/RepositoryFactory';
const mapRepository = RepositoryFactory.get('map');
import { ElLoading } from 'element-plus'
export default {
    components: { Pagination },
    data() {
        return {
            listData: dataDemo(),
            listAlls: [],
            total: 30,
            listQuery: {
                page: 1,
                limit: 20,
                sort: 'desc',
                title: '',
                locale: this.$i18n.locale
            },
            listQueryAll: {
                sort: 'desc',
                title: '',
                locale: this.$i18n.locale
            },
            total: 0,
            markers: [],
            map: null,
            infowindow: null,
            isLoading: true,
        }
    },
    computed: {
        //...mapGetters(["locale"]),
    },
    mounted() {
        //this.initMap();
        this.emitter.on("change-locale", data => {
            this.markers = [],
                this.map = null,
                this.infowindow = null,
                this.listQuery = {
                    page: 1,
                    limit: 20,
                    sort: 'desc',
                    title: '',
                    locale: this.$i18n.locale
                },
                this.fetch();
            Promise.resolve(this.fetchAll()).then(() => {
                //this.initMap();
            }).catch(error => {
                console.error('Error fetching all data:', error);
            });
        });
    },
    created() {
        this.emitter.off("change-locale");
        this.fetch();
        Promise.resolve(this.fetchAll()).then(() => {
            this.initMap();
        }).catch(error => {
            console.error('Error fetching all data:', error);
        });
    },

    methods: {
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
            }
        },
        async fetchAll() {
            this.listQueryAll.locale = this.$i18n.locale;
            const { data } = await mapRepository.all(this.listQueryAll);
            if (data.success) {
                const results = data.data;
                this.listAlls = results.map(item => {
                    return item
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
        // google maker crui
        initMap() {
            /**
             * Create new map
             */
            var locations = this.listAlls.map(item => {
                return item
            });
            var map;
            this.infowindow = new google.maps.InfoWindow();
            var red_icon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';

            var myOptions = {
                // zoom: 10,
                center: new google.maps.LatLng(31.87916, 35.32910),
                mapTypeId: 'roadmap',
                gestureHandling: 'greedy'
            };
            map = new google.maps.Map(document.getElementById('map'), myOptions);

            /**
             * Global marker object that holds all markers.
             * @type {Object.<string, google.maps.LatLng>}
             */
            var markers = [];

            /**
             * loop through (Mysql) dynamic locations to add markers to map.
             */
            var i;
            var bounds = new google.maps.LatLngBounds();

            for (i = 0; i < locations.length; i++) {
                var marker = new google.maps.Marker({
                    //position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    position: new google.maps.LatLng(locations[i]['lat'], locations[i]['long']),
                    map: map,
                    // icon: locations[i][4] === '1' ? red_icon : purple_icon,
                    html: `<div class='locale' id='window_loc'>
                            <div class='images'>
                                <img src="${locations[i]['feature_image']}" alt="${locations[i]['title']}">
                            </div>
                            <div class='info-locale'>
                                <h5>${locations[i]['title']}</h5>
                                <p class="name"><strong>${this.$i18n.t('manager')}: </strong>${locations[i]['manager']}</p>
                                <p>Latitude: ${locations[i]['lat']}</p>
                                <p>Longitude: ${locations[i]['long']}</p>
                                <div class="d-flex">
                                    <el-button class="button-more"><a href="#">${this.$i18n.t('readmore')}</a></el-button>
                                </div>
                            </div>
                        </div>`
                });
                bounds.extend(marker.getPosition());

                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        if (this.infowindow) {
                            this.infowindow.close();
                        }
                        this.infowindow = new google.maps.InfoWindow();
                        this.infowindow.setContent(marker.html);
                        this.infowindow.open(map, marker)
                    }
                })(marker, i));
                markers.push(marker);
            }
            map.fitBounds(bounds);
            // Add a marker clusterer to manage the markers.
            new MarkerClusterer(map, markers, {
                imagePath: "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
            });
            this.map = map;
            this.markers = markers;
        },
        hanldeMarker(id) {
            let markerIndex = this.listAlls.findIndex(item => item.id === id);// Chỉ số của marker bạn muốn pan đến;
            let marker = this.markers[markerIndex];
            if (marker) {
                let markerPosition = marker.getPosition();
                if (markerPosition) {
                    google.maps.event.trigger(this.markers[markerIndex], 'click');
                    this.map.panTo(markerPosition);
                    this.map.setZoom(17);
                } else {
                    console.error("Vị trí của marker không tồn tại.");
                }
            } else {
                console.error("Marker không tồn tại.");
            }
        },
        //search
        handleSearch(data) {
            this.listQuery.page = 1;
            this.listQuery.limit = 20;
            this.fetch();
        },
    },
}
</script>

<style lang="scss">
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
</style>

<style lang="scss" scoped>
@import "~@style/variables.scss";

#map {
    height: 100%;

}


/* Optional: Makes the sample page fill the window. */

html,
body {
    height: 100%;
    margin: 0;
    padding: 0;
}

.search {
    width: calc(100% - 60px);
    margin: 30px 0 10px 40px;
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
</style>
