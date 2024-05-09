import request from "@/utils/request";

const resource = '/handbook'

export default {

	// info(query) {	
    //     return Repository.get(`${resource}`+'/info', { params: query })
	// },
	// search(query) {	
    //     return Repository.get(`${resource}`+'/search', { params: query })
	// },

	info(query) {
        return request({
            url: `${resource}` + "/info",
            method: 'get',
            params: { query }
        })
    },
	search(query) {
        return request({
            url: `${resource}` + "/search",
            method: 'get',
            params: { query }
        })
    },
}