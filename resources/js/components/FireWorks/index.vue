<template>
    <div>
        <vue-damp-fireworks class="fireworks" ref="fireworks" v-if="show">
            <p class="slogan">Xin chúc mừng bạn lên level mới!</p>            
        </vue-damp-fireworks>
        <el-button type="danger" v-if="show" circle @click="hideFireWorks" class="close-fire-works"><i class="ri-close-fill"></i></el-button>
    </div>
</template>
<script>
    export default {
        name: 'Fireworks',
        props: {},
        data () {  
            return {
                show:false,
            }           
        },

        filters:{
            
        },

        created() {
            this.emitter.off("show-fire-work");
        },

        mounted() {
            this.emitter.on("show-fire-work", data => {
                this.show = data;
                let appMain = document.getElementById("wrap-app-main");
                if (this.show) {
                    appMain.classList.add("showFireWorks");
                }else{
                    appMain.classList.remove("showFireWorks");
                }
            });
        },

        destroyed() {
        },

        methods: {  
            hideFireWorks(){
                this.show = false;
                let appMain = document.getElementById("wrap-app-main");
                appMain.classList.remove("showFireWorks");
            },     
        }
    }

</script>
<style lang="scss" scoped>
.fireworks{
    position: fixed;
    left:0;
    top:0;
    width: 100% !important;
    height: 100% !important;
    background: rgba(0,0,0,0.9);
    z-index: 9991;
    :deep(canvas){
        width: 100% !important;
        height: 100% !important;
    }
    :deep(.canvasBox) {
        height: 100% !important;
    }
    .slogan {
        position: absolute;
        background: transparent;
        color: #fff;
        text-align: center;
        display: block;
        width: 100%;
        top: calc(50%);
        font-weight: bold;
        font-size: 30px;
        margin: 0;
    }
}
.close-fire-works {
    position: fixed;
    z-index: 9992;
    top:15px;
    right: 15px;
}
</style>