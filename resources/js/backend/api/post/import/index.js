import request from "@/backend/respository/request";
const resource = "/import";

export default {
    list(query) {
        return request({
            url: `${resource}` + "/logs/list",
            method: 'get',
            params: query
        })
    },
    import(data) {
        return request({
            url: `${resource}` + "/import",
            method: 'post',
            data
        })
    },
    delete(id) {
        return request({
            url: `${resource}` + "/delete/"+`${id}`,
            method: 'post'
        })
    },
}