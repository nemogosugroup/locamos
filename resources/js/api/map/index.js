import request from "@/utils/request";

const resource = "/map";

export default {
    
    list(query){
        return request({
            url: `${resource}` + "/list",
            method: 'get',
            params: query
        })
    },
    all(query){
        return request({
            url: `${resource}` + "/all",
            method: 'get',
            params: query
        })
    },
    store(query){
        return request({
            url: `${resource}`+`/${query.id}`,
            method: 'get',
            params: query
        })
    },
};
