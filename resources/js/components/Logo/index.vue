<template>
    <div class="logo">
        <transition name="sidebarLogoFade">
            <!-- <router-link v-if="collapse" key="collapse" class="sidebar-logo-link" to="/">
                <img v-if="icon" :src="icon" class="sidebar-logo">
            </router-link> -->
            <router-link class="sidebar-logo-link" to="/">
                <img v-if="logo" :src="logo" class="sidebar-logo">
            </router-link>
        </transition>
    </div>
</template>

<script>
import imagesLogo from "@/assets/images/logo/GOSU_full.png";
import imagesIcon from "@/assets/images/logo/GOSU_icon.png";
export default {
    name: 'Logo',
    // props: {
    //     collapse: {
    //         type: Boolean,
    //         required: true
    //     }
    // },
    data() {
        return {
            logo: imagesLogo,
            icon: imagesIcon
        }
    }
}
</script>

<style lang="scss" scoped>
.sidebarLogoFade-enter-active {
    transition: opacity 1.5s;
}

.sidebarLogoFade-enter,
.sidebarLogoFade-leave-to {
    opacity: 0;
}

.logo {
    position: relative;
    width: auto;
    display: inline-block;
    // height: 50px;
    // line-height: 50px;
    //background: #2b2f3a;
    text-align: center;
    // overflow: hidden;

    & .sidebar-logo-link {
        height: 100%;
        width: 100%;

        & .sidebar-logo {
            max-width: 180px;
            vertical-align: middle;
            margin-right: 12px;
        }

        & .sidebar-title {
            display: inline-block;
            margin: 0;
            color: #fff;
            font-weight: 600;
            line-height: 50px;
            font-size: 14px;
            font-family: Avenir, Helvetica Neue, Arial, Helvetica, sans-serif;
            vertical-align: middle;
        }
    }

    &.collapse {
        .sidebar-logo {
            margin-right: 0px;
        }
    }
}
</style>