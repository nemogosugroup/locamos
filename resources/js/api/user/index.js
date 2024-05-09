import request from "@/utils/request";

const resource = "/user";

export default {
    login(data) {
        return request({
            url: `${resource}` + "/login",
            method: 'post',
            data
        })
    },
    getInfo(token) {
        return request({
            url: `${resource}` + "/info",
            method: 'get',
            params: { token }
        })
    },
    getTrainingInfo(query) {
        return request({
            url: `${resource}` + "/training-info",
            method: 'get',
            params: query
        })
    },
    getTrainingDetailInfo(query) {
        return request({
            url: `${resource}` + "/training-detail-info",
            method: 'get',
            params: query
        })
    },
    getRoles(token) {
        return request({
            url: `${resource}` + "/roles",
            method: 'get',
            params: { token }
        })
    },
    logout() {
        return request({
            url: `${resource}` + "/logout",
            method: 'post',
        })
    },
    list(query){
        return request({
            url: `${resource}` + "/roles",
            method: 'get',
            params: { query }
        })
    },
    create(data){
        return request({
            url: `${resource}` + "/create",
            method: 'post',
            data
        })
    },
    update(data){
        let id = data.get('id');
        return request({
            url: `${resource}` + "/update/"+`${id}`,
            method: 'post',
            data,
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    },
    export(){
        return request({
            url: `${resource}` + "/export",
            method: 'post'
        })
    },
    training(){
        return request({
            url: `${resource}` + "/training",
            method: 'post'
        })
    },
    listEmployee(){
        return request({
            url: `${resource}` + "/list-employee",
            method: 'get',
        })
    },
    store(query){
        return request({
            url: `${resource}` + "/store",
            method: 'get',
            params: query
        })
    },
    // đổi khoá học hoặc đổi trang bị
    add(data){
        return request({
            url: `${resource}` + "/add",
            method: 'post',
            data
        })
    },
    // lấy khoá học hoặc trang bị
    getData(query){
        return request({
            url: `${resource}` + "/get",
            method: 'get',
            params: query
        })
    }
};
