<template>
    <div class="app-container">
        <div v-if="infoUser">
            <el-row :gutter="20">
                <el-col :span="6" :xs="24">
                    <user-card :user="infoUser" @update-avatar="handleUpdateAvatar"/>
                    <button @click="facebookShare">Chia sẻ trên Facebook</button>
                </el-col>
                <el-col :span="18" :xs="24">
                    <el-card>
                        <el-tabs v-model="activeTab" @tab-click="handleTabs">
                            <el-tab-pane label="Thông tin" name="account">
                                <account
                                    :user="infoUser"
                                    :data-medal="dataMedal"
                                />
                            </el-tab-pane>
                            <el-tab-pane label="Khoá học" name="course">
                                <course :list="listCourse"/>
                            </el-tab-pane>
                            <!-- <el-tab-pane label="Activity" name="activity">
                                <activity />
                            </el-tab-pane>
                            <el-tab-pane label="Timeline" name="timeline">
                                <timeline />
                            </el-tab-pane> -->
                        </el-tabs>
                    </el-card>
                </el-col>
            </el-row>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import UserCard from "./components/UserCard";
import Activity from "./components/Activity";
import Timeline from "./components/Timeline";
import Account from "./components/Account";
import Course from "./components/Course";
import RepositoryFactory from '@/utils/RepositoryFactory';
import Helpers from '@/helper';
const UserRepository = RepositoryFactory.get('user');
export default {
    name: "Profile",
    components: { UserCard, Activity, Timeline, Account, Course },
    data() {
        return {
            infoUser: {},
            dataMedal: {},
            percent: 0,
            activeTab: "account",
            formdata:{
                file:false,
                dataUpdate:'',
                id:''
            },
            listCourse:[],
            listEquipment:[],
            urlAvatar: "https://camnanghanhtrinh.gosucorp.vn/images/logo/GOSU_icon.png"
        };
    },
    computed: {
        ...mapGetters(["user", "gold", "experience", "data_medal"]),
    },
    created() {
        this.getUser();
        this.emitter.off("is-update-user");
    },
    mounted() {
        this.emitter.on("is-update-user", dataMedal => {
            this.handleUpdateUser(dataMedal);
        });
        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v10.0&appId=1120630529380971&autoLogAppEvents=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        if (!this.checkMetaExist('og:url')) {
            this.addMeta('og:url', window.location.href);
        }
        if (!this.checkMetaExist('og:type')) {
            this.addMeta('og:type', 'website');
        }
        if (!this.checkMetaExist('og:title')) {
            this.addMeta('og:title', 'Tiêu đề của bài viết');
        }
        if (!this.checkMetaExist('og:description')) {
            this.addMeta('og:description', 'Mô tả ngắn về bài viết');
        }
        if (!this.checkMetaExist('og:image')) {
            this.addMeta('og:image', this.urlAvatar);
        }
    },
    methods: {
        getUser() {
            this.infoUser = this.user;
            this.infoUser['gold'] = this.gold;
            this.infoUser['experience'] = this.experience;
            this.infoUser['percentage'] =  Helpers.percentExp(this.user);
            this.dataMedal = this.data_medal;
        },
        // cập nhập thông tin user
        async handleUpdateUser(dataMedal){
            try {
                this.formdata.data = {
                    'first_name': this.user.first_name,
                    'last_name': this.user.last_name,
                    'achievements': dataMedal,
                };//=> sẽ bổ sung thông tin update
                this.formdata.id = this.user.id // user id
                let formData = new FormData();
                formData.append('data', JSON.stringify(this.formdata.data));
                formData.append('id',  this.formdata.id);
                if (this.formdata.file) {
                    formData.append('file', this.formdata.file);
                }
                const { data } = await UserRepository.update(formData); 
                if(data.success){
                    let results = data.data;
                    this.$store.dispatch("user/update", results);
                    this.$message({
                        type: 'success',
                        message: data.message,
                    })
                }else{
                    this.$message({
                        type: 'error',
                        message: data.message,
                    })
                }
                //var info = await store.dispatch("user/getInfo");
            } catch (error) {
                console.log('Cập nhập thông tin user lỗi', error)
            }
        },
        handleUpdateAvatar(file){
            this.formdata.file = file
        },
        // handleTabs
        async handleTabs(tab, event) {
            let tabName = event.target.id;
            tabName = tabName.replace("tab-", "");
            if (tabName != 'account') {
                let query = {
                    type: tabName
                }
                const { data } = await UserRepository.getData( query ); 
                if(data.success){
                    if (data.data) {
                        let results = data.data
                        if (tabName == 'course') {
                            this.listCourse = results.map(item => item)
                        }else{
                            this.listEquipment = results.map(item => item)
                        }
                    }
                }
            } 
        },
        facebookShare() {
            var facebookShareUrl = 'https://www.facebook.com/sharer/sharer.php';
            // Mở cửa sổ chia sẻ trên Facebook
            window.open(facebookShareUrl, '_blank', 'width=600,height=400');
        },
        
        checkMetaExist: (property) => {
            const metaElements = document.querySelectorAll(`meta[property="${property}"]`);
            return metaElements.length > 0;
        },

        addMeta: (property, content) => {
            const metaElement = document.createElement('meta');
            metaElement.setAttribute('property', property);
            metaElement.setAttribute('content', content);
            document.head.appendChild(metaElement);
        }
    },
};
</script>
