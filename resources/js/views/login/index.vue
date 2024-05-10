<template>
     <div class="app-wrapper">
        <el-container>
            <el-aside class="sidebar-container">
                <div class="login-container">
                    <el-form
                        ref="loginForm"
                        :model="loginForm"
                        :rules="loginRules"
                        class="login-form"
                        autocomplete="on"
                        label-position="left"
                    >
                        <div class="title-container">
                            <h3 class="title"><img v-if="logo" :src="logo" class="sidebar-logo"></h3>
                        </div>

                        <el-form-item prop="email">
                            <span class="svg-container">
                                <i class="ri-user-line"></i>
                            </span>
                            <el-input
                                ref="email"
                                v-model="loginForm.email"
                                placeholder="Email"
                                name="email"
                                type="email"
                                tabindex="1"
                                autocomplete="on"
                            />
                        </el-form-item>

                        <el-tooltip
                            v-model="capsTooltip"
                            content="Caps lock is On"
                            placement="right"
                            manual
                        >
                            <el-form-item prop="password">
                                <span class="svg-container">
                                    <i class="ri-lock-line"></i>
                                </span>
                                <el-input
                                    :key="passwordType"
                                    ref="password"
                                    v-model="loginForm.password"
                                    :type="passwordType"
                                    placeholder="Password"
                                    name="password"
                                    tabindex="2"
                                    autocomplete="on"
                                    @keyup="checkCapslock"
                                    @blur="capsTooltip = false"
                                    @keyup.enter="handleLogin"
                                />
                            </el-form-item>
                        </el-tooltip>
                        <el-button
                            :loading="loading"
                            type="primary"
                            style="width: 100%; margin-bottom: 30px"
                            @click.prevent="handleLogin"
                            >Login</el-button
                        >
                    </el-form>
                </div>
            </el-aside>
            <el-main class="main-login" :style="backgroundLogin"></el-main>
        </el-container>
    </div>
    <!--  -->
</template>

<script>
import { validUsername } from "@/utils/validate";
// import SocialSign from './components/SocialSignin'
import bgLogin from "@/assets/images/bg/authentication-bg.jpg";
import imagesLogo from "@/assets/images/logo/GOSU_full.png";
import { getAccessToken } from "@/utils/auth";
export default {
    name: "Login",
    data() {
        return {
            loginForm: {
                email: "",
                password: "",
            },
            loginRules: {
                email: [
                    {
                        required: true,
                        type: 'email',
                        trigger: "blur",
                        message: "Vui lòng nhập địa chỉ email!",
                    },
                ],
                password: [
                    {
                        required: true,
                        trigger: "blur",
                        message: "Vui lòng nhập mật khẩu!",
                    },
                ],
            },
            passwordType: "password",
            capsTooltip: false,
            loading: false,
            showDialog: false,
            redirect: undefined,
            redirectUri: undefined,
            oAuthState: undefined,
            otherQuery: {},
            bgLogin: bgLogin,
            logo: imagesLogo,
        };
    },
    watch: {
        $route: {
            handler: function (route) {
                const query = route.query;
                // redirect_uri
                if (query) {
                    this.redirect = query.redirect;
                    this.redirectUri = query.redirect_uri;
                    this.oAuthState = query.state;
                    this.otherQuery = this.getOtherQuery(query);
                }
            },
            immediate: true,
        },
    },
    created() {
    },
    mounted() {
    },
    destroyed() {
    },
    computed: {
        backgroundLogin(){
            return `background-image: url(${this.bgLogin})`;
        },
    },
    methods: {
        checkCapslock(e) {
            const { key } = e;
            this.capsTooltip =
                key && key.length === 1 && key >= "A" && key <= "Z";
        },
        handleLogin() {
            this.$refs.loginForm.validate((valid) => {
                if (valid) {
                    this.loading = true;
                    this.$store
                        .dispatch("user/login", this.loginForm)
                        .then((response) => {
                            const { data } = response;
                            if (data.success) {
                                this.$message({
                                    message: data.message,
                                    type: 'success'
                                })
                                
                                //console.log("check this.redirectUri",this.redirectUri);
                                if(this.redirectUri){
                                    
                                    //console.log("check this.redirectUri 2",this.redirectUri);
                                    window.location.href = this.redirectUri+'?token='+getAccessToken()+'&state='+this.oAuthState;
                                }else{
                                    //console.log("check this.redirect",this.redirect);
                                    window.location.href = "/admin/post/post";
                                }
                                
                                
                            }else{
                                this.$message({
                                    message: data.message,
                                    type: 'error'
                                })
                            }                            
                            this.loading = false;
                        })
                        .catch(() => {
                            this.loading = false;
                        });
                } else {
                    console.log("error submit!!");
                    return false;
                }
            });
        },
        getOtherQuery(query) {
            return Object.keys(query).reduce((acc, cur) => {
                if (cur !== "redirect") {
                    acc[cur] = query[cur];
                }
                return acc;
            }, {});
        },
    },
};
</script>

<style lang="scss">
$bg: hsl(213, 25%, 21%);
// $bg: #432828;
$light_gray: #fff;
$cursor: #fff;

@supports (-webkit-mask: none) and (not (cater-color: $cursor)) {
    .login-container .el-input input {
        color: $cursor;
    }
}

/* reset element-ui css */
.login-container {
    .el-input {
        display: inline-block;
        height: 47px;
        width: calc(100% - 50px);
        input {
            background: transparent;
            border: 0px;
            -webkit-appearance: none;
            border-radius: 0px;
            padding: 12px 5px 12px 0px;
            color: $light_gray;
            height: 47px;
            caret-color: $cursor;

            &:-webkit-autofill {
                box-shadow: 0 0 0px 1000px $bg inset !important;
                -webkit-text-fill-color: $cursor !important;
            }
        }
    }

    .el-form-item {
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        color: #454545;
        margin-bottom: 20px;
        &.is-error {
            .el-input__wrapper {
                box-shadow: none;
            }
        }
        .el-input__wrapper {
            width: 100%;
            background: transparent;
            box-shadow: none;
            padding: 0;
            border-radius: 0 5px 5px 0;
            padding-right: 5px;
        }
    }
}
</style>

<style lang="scss" scoped>
$bg: #2d3a4b;
$bgOverlay : #292626b3;
$dark_gray: #889aa4;
$light_gray: #eee;
$width_main: calc(100% - 30%);
$width_siderbar: calc(30%);
$height_logo: 70px;
#app {
    .sidebar-container {
        width: $width_siderbar !important;
        padding: 0;
        .sidebar-logo {
            max-height: $height_logo;
        }
    }
}
.login-container {
    min-height: 100%;
    width: 100%;
    background-color: $bg;
    overflow: hidden;
    display: flex;
    align-items: center;
    .login-form {
        position: relative;
        width:100%;
        max-width: 500px;
        padding: 0px 35px;
        margin: 0 auto;
        overflow: hidden;
    }

    .tips {
        font-size: 14px;
        color: #fff;
        margin-bottom: 10px;

        span {
            &:first-of-type {
                margin-right: 16px;
            }
        }
    }

    .svg-container {
        color: $dark_gray;
        vertical-align: middle;
        width: 50px;
        display: inline-block;
        text-align: center;
    }

    .title-container {
        position: relative;

        .title {
            font-size: 26px;
            color: $light_gray;
            margin: 0px auto 40px auto;
            text-align: center;
            font-weight: bold;
        }
    }

    .show-pwd {
        position: absolute;
        right: 10px;
        top: 7px;
        font-size: 16px;
        color: $dark_gray;
        cursor: pointer;
        user-select: none;
    }

    .thirdparty-button {
        position: absolute;
        right: 0;
        bottom: 6px;
    }

    @media only screen and (max-width: 470px) {
        .thirdparty-button {
            display: none;
        }
    }
}
.main-login {
    width: $width_main;
    margin-left: $width_siderbar;
    height: 100vh;
    position: relative;
    &:before{
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        background: $bgOverlay;
        content:''
    }
}
</style>
