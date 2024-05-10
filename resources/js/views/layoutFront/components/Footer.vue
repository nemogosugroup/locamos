<template>
    <div class="footer">
        <!-- <el-row :gutter="20" justify="space-between">
            <el-col :span="8">
                <div class="logo-privacy">
                    <Logo/>
                    <FooterPrivacy/>
                </div>
                
            </el-col>
            <el-col :span="8">
                <FooterCenter />
            </el-col>
            <el-col :span="8">
                <FooterHotline />
            </el-col>
        </el-row> -->
        <div class="main-footer">
            <div class="logo-privacy">
                <Logo/>
                <FooterPrivacy/>
            </div>
            <FooterCenter />
            <FooterHotline />
        </div>
    </div>
</template>

<script>
import Logo from "@/components/Logo";
import FooterPrivacy from "./InfoFooter/FooterPrivacy.vue";
import FooterCenter from "./InfoFooter/FooterCenter.vue";
import FooterHotline from "./InfoFooter/FooterHotline.vue";

export default {
    name: 'Footer',
    components: {
        Logo,
        FooterPrivacy,
        FooterCenter,
        FooterHotline
    },
    data() {
        return {}
    }
}
</script>

<style lang="scss" scoped>

</style>