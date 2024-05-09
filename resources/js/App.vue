<script>
import { ElConfigProvider } from 'element-plus';
import { mapGetters } from "vuex";
export default {
    components: {
        ElConfigProvider
    },
    name: 'App',
    data() {
        return {
            //language: viLang,
        };       
    },
    computed: {
        ...mapGetters(["locale"]),
    },
    methods: {
    },
    created(){ 
        // console.log('name', this.$t('user.name'));
        // console.log('locale', this.$i18n.locale);
        //console.log('demo', this.$t('message.hello'));
    },
    mounted(){
    }
}
</script>
<template>
<div id="app">
    <el-config-provider :locale="locale">
        <router-view />
    </el-config-provider>
</div>
</template>