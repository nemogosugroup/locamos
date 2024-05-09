<template>
    <el-card style="margin-bottom: 20px">
        <template #header>
            <div class="card-header clearfix">
                <span>About me</span>
            </div>
        </template>
        <div class="user-profile">
            <div class="box-center">
				<div class="avatar">
					<el-avatar shape="circle" :size="150" :src="avatar"></el-avatar>
					<input
						type="file"
						id="avatar"
						name="avatar"
						accept="image/png, image/jpeg, image/gif"
						@change="handleBeforeUpload"
					/>
					<span class="changeImages">
						<i class="ri-camera-fill"></i>
					</span>
				</div>
            </div>
            <div class="box-center">
                <div class="user-name text-center">{{ user.fullname }}</div>				
            </div>
        </div>

        <div class="user-bio">
            <div class="user-education user-bio-section">
                <div class="user-bio-section-body">
					<div class="flex-center">
						<span class="level"><strong>Level {{ user.level }}</strong></span>
						<div class="progress"> 
							<label for="">{{ user.experience }} EXP</label>
							<el-progress :text-inside="false" :show-text="false" :stroke-width="10" :percentage="user.percentage" status="success"></el-progress>
						</div>
					</div>
					<div class="flex-center flex-align-center">
						<span class="gold"><strong><i class="ri-money-dollar-circle-fill"></i></strong></span>
						<div class="progress"> 
							<label for="">{{ user.gold }}</label>
						</div>
					</div>
                </div>
            </div>
        </div>
    </el-card>
</template>

<script>

export default {
    components: {},
    props: {
        user: {
            type: Object,
            default:{},
            // default: () => {
            //     return {
            //         name: "",
            //         email: "",
            //         avatar: "",
            //         gold: "",
            //         level: "",
            //         percentage: "",
            //         experience: "",
            //     };
            // },
        },
    },
	data () {   
		return { 
			avatar: this.user.avatar,
		}            
	},
	// watch: {
	// 	avatarUrl(newValue) {
	// 		this.user.avatar = newValue;
	// 	},
	// },
	methods: {
		handleBeforeUpload(e) {
			let file =  e.target.files[0];			
			const allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif']; // Các loại file hình ảnh được chấp nhận
			if (!allowedImageTypes.includes(file.type)) {
				this.$message.error('Chỉ được phép tải lên các hình ảnh!');
				return;
			}
			// Kiểm tra kích thước file
			const maxSizeInMB = 4;
			const isLt4M = file.size / 1024 / 1024 < maxSizeInMB;
			if (!isLt4M) {
				this.$message.error(`Kích thước file không được vượt quá ${maxSizeInMB}MB!`);
				return;
			}
			this.$emit('updateAvatar', file)
			this.avatar = URL.createObjectURL(file);
		}
	}
};
</script>

<style lang="scss" scoped>
@import '@style/variables.scss';
//$yellow:#ffca2c;
.box-center {
    margin: 0 auto;
    display: table;
}

.text-muted {
    color: #777;
}

.user-profile {
    .user-name {
        font-weight: bold;
    }

    .box-center {
        padding-top: 10px;
    }

    .user-role {
        padding-top: 10px;
        font-weight: 400;
        font-size: 14px;
    }

    .box-social {
        padding-top: 30px;

        .el-table {
            border-top: 1px solid #dfe6ec;
        }
    }

    .user-follow {
        padding-top: 20px;
    }
}
.flex-center {
    display: flex;
	flex-direction: row;
	flex-wrap: nowrap;
	justify-content: center;
	align-content: stretch;
    align-items: flex-end;
	&.flex-align-center{
		align-items: center;
	}
}

.user-bio {
    margin-top: 20px;
    color: #606266;
	span {
		margin-right: 10px;
		display: block;
		text-align: right;
		min-width: 100px;
	}
	
	.gold {
		color:$yellow;
		font-size: 50px;
	}
	.progress {
		width: calc(100% - 100px);
		label{
			font-size: 12px;
			text-align: left;
			display: block;
			color: #625f5f;
			padding-left: 0;
		}
		
	}
    .user-bio-section {
        font-size: 14px;
        padding: 15px 0;
    }
}
.avatar :deep(.el-card__body) {
    padding: 0;
    display: flex;
    position: relative;
    cursor: pointer;
}
#avatar {
    position: absolute;
    z-index: 8;
    cursor: pointer;
    opacity: 0;
    width: 100%;
    height: 100%;
	top: 0;
	left: 0;
	border-radius: 100%;
}
.avatar {
    width: 150px;
    height: 150px;
	position: relative;
    .changeImages {
        position: absolute;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0,0,0,.5);
        color: #fff;
        font-size: 40px;
        transition: all .3s ease-in-out;
        opacity: 0;
		top: 0;
		left: 0;
		border-radius: 100%;
    }
    &:hover{
        .changeImages {
            transition: all .3s ease-in-out;
            opacity: 1;
        }
    }
}
</style>
