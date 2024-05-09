import request from "@/utils/request";

const resource = "/project";

export default {
    create(data) {
        return request.post(`${resource}` + "/create", data);
    },
    update(data) {
        return request.get(`${resource}` + "/update", data);
    },
    delete(id) {
        return request.post(`${resource}` + "/detete/"+`${id}`);
    },
    list(query) {
        return request.get(`${resource}` + "/list", { param: query });
    },
    apiProject() {
        return request.get(`${resource}` + "/api-project");
    },
};