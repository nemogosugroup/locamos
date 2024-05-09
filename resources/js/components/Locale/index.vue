<template>
    <el-dropdown class="avatar-container right-menu-item hover-effect"
                trigger="click" @command="handleChangeLocale">
        <span class="el-dropdown-link">
        Lang
        <el-icon class="el-icon--right">
            <arrow-down />
        </el-icon>
        </span>
        <template #dropdown>
            <el-dropdown-menu>
                <el-dropdown-item 
                    v-for="item in languages"
                    :key="item.key"
                    :command="item.key"
                >{{ item.value }}</el-dropdown-item>
            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>

<script>
import { mapGetters } from "vuex";
import Helpers from '@/helper';
import { setLocale } from "@/utils/auth"; // get lang
export default {
    name: 'Locale',
    data() {
        return {
            languages : Helpers.languages()
        }
    },
    computed: {
        ...mapGetters(["locale"]),
    },
    watch: {
    },
    created() {
    },
    methods: {
        handleChangeLocale(command){
            this.$i18n.locale = command;
            setLocale(command)            
            this.$store.dispatch("locale/changeLocale", command)
            //this.$router.go()
        },
    }
}
</script>
<style lang="scss" scoped></style>
