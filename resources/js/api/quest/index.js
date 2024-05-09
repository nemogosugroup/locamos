import request from "@/utils/request";

const resource = "/quest";

export default {
    list(query) {
        return request({
            url: `${resource}` + "/list",
            method: 'get',
            params: query
        })
    },
    create(data) {
        return request({
            url: `${resource}` + "/create",
            method: 'post',
            data
        })
    },
    update(data) {
        return request({
            url: `${resource}` + "/update",
            method: 'post',
            data
        })
    },
};