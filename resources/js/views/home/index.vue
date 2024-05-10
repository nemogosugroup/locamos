<template>
    <el-row>
        <el-col :span="8">
            <div class="homeListMap">
                <div class="search">
                    <el-input v-model="input1" size="large" :placeholder="$t('search')">
                        <template #append>
                            <el-button><i class="ri-search-line"></i></el-button>
                        </template>
                    </el-input>
                </div>
                <div class="wrap-listCard">
                    <el-scrollbar wrap-class="scrollbar-wrapper" height="calc(100vh - 225px)">
                        <div v-for="item in listData" :key="item.id" class="card">
                            <div class="images">
                                <img :src="item.images" :alt="item.title" />
                            </div>

                            <div class="info">
                                <h6>{{ excerpt(item.title, 6) }}</h6>
                                <p class="name"><strong>{{ $t('manager') }}</strong> {{ item.manager }}</p>
                                <p>Latitude: {{ item.lat }}</p>
                                <p>Longitude: {{ item.long }}</p>
                                <div class="d-flex">
                                    <el-button @click="hanldeMarker()" type="primary" plain><i
                                            class="ri-send-plane-fill"></i></el-button>
                                    <el-button class="button-more"><a href="#">{{ $t('readmore') }}</a></el-button>
                                </div>
                            </div>
                        </div>
                    </el-scrollbar>
                    <pagination v-show="total > 10" v-bind:total="total" v-model:page="listQuery.page"
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
import { dataDemo } from "./datademo";
import Pagination from '@/components/Pagination'
export default {
    components: { Pagination },
    data() {
        return {
            listData: dataDemo(),
            total: 30,
            listQuery: {
                page: 1,
                limit: 10,
            },
            markers: [],
            map: null,
            infowindow: null,
        }
    },
    mounted() {
        this.initMap();
    },

    methods: {
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
        fetch() {

        },
        // google maker crui
        initMap() {
            /**
             * Create new map
             */

            //var infowindow;
            var map;
            this.infowindow = new google.maps.InfoWindow();            
            var red_icon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
            var self = this;

            var locations = [
                ["name0", -31.56391, 147.154312], ["name1", -33.718234, 150.363181], ["name2", -33.727111, 150.371124], ["name3", -33.848588, 151.209834], ["name4", -33.851702, 151.216968], ["name5", -34.671264, 150.863657], ["name6", -35.304724, 148.662905], ["name7", -36.817685, 175.699196], ["name8", -36.828611, 175.790222], ["name9", -37.75, 145.116667], ["name10", -37.759859, 145.128708], ["name11", -37.765015, 145.133858], ["name12", -37.770104, 145.143299], ["name13", -37.7737, 145.145187], ["name14", -37.774785, 145.137978], ["name15", -37.819616, 144.968119], ["name16", -38.330766, 144.695692], ["name17", -39.927193, 175.053218], ["name18", -41.330162, 174.865694], ["name19", -42.734358, 147.439506], ["name20", -42.734358, 147.501315], ["name21", -42.735258, 147.438], ["name22", -43.999792, 170.463352]
            ]
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
            // var markers = [];
            //var seft = this.markers;
            /**
             * Concatenates given lat and lng with an underscore and returns it.
             * This id will be used as a key of marker to cache the marker in markers object.
             * @param {!number} lat Latitude.
             * @param {!number} lng Longitude.
             * @return {string} Concatenated marker id.
             */
            var getMarkerUniqueId = function (lat, lng) {
                return lat + '_' + lng;
            };

            /**
             * Creates an instance of google.maps.LatLng by given lat and lng values and returns it.
             * This function can be useful for getting new coordinates quickly.
             * @param {!number} lat Latitude.
             * @param {!number} lng Longitude.
             * @return {google.maps.LatLng} An instance of google.maps.LatLng object
             */
            var getLatLng = function (lat, lng) {
                return new google.maps.LatLng(lat, lng);
            };


            /**
             * Binds right click event to given marker and invokes a callback function that will remove the marker from map.
             * @param {!google.maps.Marker} marker A google.maps.Marker instance that the handler will binded.
             */
            var bindMarkerEvents = function (marker) {
                google.maps.event.addListener(marker, "rightclick", function (point) {
                    var markerId = getMarkerUniqueId(point.latLng.lat(), point.latLng.lng()); // get marker id by using clicked point's coordinate
                    var marker = this.markers[markerId]; // find marker
                    removeMarker(marker, markerId); // remove it
                });
            };


            /**
             * loop through (Mysql) dynamic locations to add markers to map.
             */
            var i;
            var confirmed = 0;
            var bounds = new google.maps.LatLngBounds();

            for (i = 0; i < locations.length; i++) {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    // icon: locations[i][4] === '1' ? red_icon : purple_icon,
                    html: "<div id='window_loc'>\n" +
                        "<form method='GET' action='question.php'>\n" +
                        "<table class=\"map1\">\n" +
                        "<tr>\n" +
                        "<td><input type='hidden'  id='manual_description'/>" + locations[i][3] + "</td></tr>\n" +
                        "<tr>\n" +
                        "<td><textarea disabled  id='question' placeholder='Question'>" + locations[i][5] + "</textarea></td></tr>\n" +
                        "<tr>\n" +
                        "<td><input type='hidden' name='location_id' id='location_id' value=" + locations[i][0] + " /></td></tr>\n" +
                        "<td><input id='button1' name='play' type='submit' value='play'/> </td></tr>\n" +
                        "</table>\n" +
                        "</form>\n" +
                        "</div>"
                });
                bounds.extend(marker.getPosition());

                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    //console.log('map', map)
                    //map.panTo(marker.getPosition());
                    return function () {
                        if (this.infowindow) {
                            this.infowindow.close();
                        }
                        confirmed = locations[i][4] === '1' ? 'checked' : 0;
                        $("#confirmed").prop(confirmed, locations[i][4]);
                        $("#location_id").val(locations[i][0]);
                        $("#description").val(locations[i][3]);
                        $("#form").show();
                        this.infowindow = new google.maps.InfoWindow();
                        this.infowindow.setContent(marker.html);
                        this.infowindow.open(map, marker)
                    }
                })(marker, i));
                this.markers.push(marker);
            }
            map.fitBounds(bounds);
            // Add a marker clusterer to manage the markers.
            new MarkerClusterer(map, this.markers, {
                imagePath: "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
            });
            this.map = map
        },
        hanldeMarker(){
            let markerIndex = 17; // Chỉ số của marker bạn muốn pan đến
            let marker = this.markers[markerIndex];
            if (marker) {
                let markerPosition = marker.getPosition();
                if (markerPosition) {
                    google.maps.event.trigger(this.markers[17], 'click');
                    this.map.panTo(markerPosition);
                    this.map.setZoom(9);
                } else {
                    console.error("Vị trí của marker không tồn tại.");
                }
            } else {
                console.error("Marker không tồn tại.");
            }
        },
    },
}
</script>


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
