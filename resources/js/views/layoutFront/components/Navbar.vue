<template>
    <div class="navbar" :style="backgroundNav">
        <Logo/>

        <div class="right-menu">
            <Locale></Locale>
            <span v-if="$route.name === 'Home'">
                <span id="btnFilterMobile" class="btn_filter" @click="toggleFilterTab()">
                    <i class="ri-search-line"></i>
                </span>
            </span>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import Locale from "@/components/Locale";
import Logo from "@/components/Logo";
import avatarDefault from "@/assets/images/logo/avatar.gif";
import bgNav from "@/assets/images/bg/bg-navbar.png";
export default {
    components: {
        Locale,
        Logo
    },
    data(){
        return {
            bgNav: bgNav,
            isShowFilter: false
        }
    },
    computed: {
        ...mapGetters(["sidebar", "device", "user"]),
		avatar() {
            let avatar = this.user.avatar; 
			return avatar ? avatar : avatarDefault;
		},
        variables() {           
            return variables;
        },
        backgroundNav(){
            return `background-image: url(${bgNav})`;
        },
    },
    mounted() {
        this.emitter.on("is-toggle-filter-tab", () => {
            this.toggleFilterTab();
        });
        this.emitter.on("hide-filter-in-detail", () => {
            this.toggleFilterTab(true);
        });
    },
    methods: {
        toggleSideBar() {
            this.$store.dispatch("app/toggleSideBar");
        },
        async logout() {
            await this.$store.dispatch("user/logout");
            //this.$router.push(`/login?redirect=${this.$route.fullPath}`);
            this.$router.push(`/login`);
        },
        toggleFilterTab(fromDetail = false) {
            this.isShowFilter = fromDetail ? false : !this.isShowFilter;

            const filterTab = document.getElementById('filterTab');
            const filterTabShow = document.getElementById('filterTabShow');
            const showingMap = document.getElementById('showingMap');
            const showingMapShow = document.getElementById('showingMapShow');

            if (this.isShowFilter) {
                filterTab?.setAttribute('id', 'filterTabShow');
                showingMap?.setAttribute('id', 'showingMapShow');
            } else {
                filterTabShow?.setAttribute('id', 'filterTab');
                showingMapShow?.setAttribute('id', 'showingMap');
            }
        }
    },
};
</script>

<style lang="scss" scoped>
.navbar {
    overflow: hidden;
    position: relative;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);
    height: 80px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 100px;
    background-repeat: no-repeat;
    background-size: cover;    

    .right-menu {
        float: right;
        height: 100%;
        line-height: 50px;

        &:focus {
            outline: none;
        }

        .right-menu-item {
            display: inline-block;
            padding: 0 8px;
            height: 100%;
            font-size: 18px;
            color: #5a5e66;
            vertical-align: text-bottom;

            &.hover-effect {
                cursor: pointer;
                transition: background 0.3s;

                &:hover {
                    background: rgba(0, 0, 0, 0.025);
                }
            }
        }

        .avatar-container {
            margin-right: 30px;

            .avatar-wrapper {
                margin-top: 5px;
                position: relative;

                .user-avatar {
                    cursor: pointer;
                    width: 40px;
                    height: 40px;
                    border-radius: 10px;
                }

                .el-icon-caret-bottom {
                    cursor: pointer;
                    position: absolute;
                    right: -20px;
                    top: 25px;
                    font-size: 12px;
                }
            }
        }
        .btn_filter {
            display: none;
        }
    }
}
@media screen and (max-width: 1199px) {
    .navbar {
        height: 55px !important;
        .btn_filter {
            display: block !important;
            color: #fff;
            position: absolute;
            font-size: 24px;
            bottom: 1px;
            right: 35px;
        }
    }
}
@media screen and (max-width: 600px) {
    .navbar {
        padding: 10px 50px 10px 10px;
        .right-menu {
            .btn_filter {
                right: 15px;
            }
        }
    }
}
</style>
