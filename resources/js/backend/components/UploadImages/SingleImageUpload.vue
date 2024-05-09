<template>
    <div class="upload-container">       
        <div class="image-preview image-app-preview">
            <div v-show="iconUrl.length > 1" class="image-preview-wrapper">
                <img :src="iconUrl" />
                <div class="image-preview-action">
                    <i class="ri-close-fill" @click="rmImage"></i>
                </div>
            </div>
        </div>
        <div class="upload-container">
            <el-button style="width:100%" icon="el-icon-upload" size="large" type="primary" @click="handerCkfinder()">
                {{ `Hình ảnh` }}
            </el-button>
        </div>
    </div>
</template>

<script>
import { getAccessToken } from "@/api/qiniu";
import Ckfinder from '@/components/Ckfinder'
export default {
    name: "SingleImageUpload",
    props: {
        icon: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            iconUrl: this.icon
        };
    },
    computed: {
        imagesUrl() {
            // console.log('this.value', this.icon)
            // return this.value;
        },
    },
    methods: {
        rmImage() {
            this.$emit('input', "")
            this.iconUrl = ""
        },
        handerCkfinder(){
  			this.init()
  		},

  		init() {
			const _this = this
			
			var finder = new CKFinder();

			finder.selectActionFunction = function(fileUrl, data){
				_this.$emit('input', fileUrl)
                _this.iconUrl = fileUrl
			};

			finder.popup();

    	},
    },
};
</script>

<style lang="scss" scoped>
@import "~@style/mixin.scss";
.upload-container {
    width: 100%;
    position: relative;
    @include clearfix;
    .image-app-preview {
        width: 100%;
        min-height: 180px;
        position: relative;
        border: 1px dashed #d9d9d9;
        float: none;
        margin-left: 0px;
        margin-bottom: 20px;
        .app-fake-conver {
            height: 44px;
            position: absolute;
            width: 100%; // background: rgba(0, 0, 0, .1);
            text-align: center;
            line-height: 64px;
            color: #fff;
        }
        .image-preview-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            img {
                width: 100%;
                height: 100%;
            }
        }
        .image-preview-action {
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            cursor: default;
            text-align: center;
            color: #fff;
            opacity: 0;
            font-size: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s;
            cursor: pointer;
            text-align: center;
            line-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            i {
                font-size: 36px;
            }
        }
        &:hover {
            .image-preview-action {
                opacity: 1;
            }
        }
    }
}
</style>
