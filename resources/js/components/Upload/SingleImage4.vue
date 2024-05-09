<template>
    <div class="upload-container">       
        <div class="image-preview image-app-preview">
            <div v-show="imageUrl.length > 1" class="image-preview-wrapper">
                <img :src="imageUrl" />
                <div class="image-preview-action">
                    <i class="el-icon-delete" @click="rmImage" />
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
    name: "SingleImageUpload4",
    props: {
        value: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            tempUrl: "",
            dataObj: { token: "", key: "" },
        };
    },
    computed: {
        imageUrl() {
            return this.value;
        },
    },
    methods: {
        rmImage() {
            this.emitInput("");
        },
        emitInput(val) {
            this.$emit("input", val);
        },
        handleImageSuccess(file) {
            this.emitInput(file.files.file);
        },
        beforeUpload() {
            const _self = this;
            return new Promise((resolve, reject) => {
                getAccessToken()
                    .then((response) => {
                        const key = response.data.qiniu_key;
                        const token = response.data.qiniu_token;
                        _self._data.dataObj.token = token;
                        _self._data.dataObj.key = key;
                        this.tempUrl = response.data.qiniu_url;
                        resolve(true);
                    })
                    .catch((err) => {
                        console.log(err);
                        reject(false);
                    });
            });
        },
        handerCkfinder(){
  			this.init()
  		},

  		init() {
			const _this = this
			
			var finder = new CKFinder();

			finder.selectActionFunction = function(fileUrl, data){
				_this.$emit('input', fileUrl)
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
            .el-icon-delete {
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
