import request from "@/backend/respository/request";

const resource = "/type-equipment";

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

	update(query) {
        let data = query['data'];
        return request({
            url: `${resource}` + "/update/"+`${query.id}`,
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