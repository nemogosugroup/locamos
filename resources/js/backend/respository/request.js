import axios from "axios";
import { getAccessToken } from "@/utils/auth";
import { ElMessage } from 'element-plus'
import store from "@/store";
import router from '@/router';

const baseDomain = process.env.BASE_URL; //'http://jsonplaceholder.typicode.com';
const baseUrl = "/api/admin";
const service = axios.create({
    baseURL: baseUrl,
});

// Request
service.interceptors.request.use(
    (config) => {
        var token = getAccessToken();
        if (token) {
            config.headers["Authorization"] = "Bearer " + token;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response
service.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        const originalRequest = error.config;
        // token expired
        if (
            error.response.status === 401 &&
            error.response.data.message == "Expired Token"
        ) {
            originalRequest._retry = true;
            store
                .dispatch("user/refreshToken")
                .then((response) => {
                    originalRequest._retry = true;

                    const hasToken = getAccessToken();
                    const hasAuth = getAuth();

                    location.reload();
                })
                .catch((error) => {
                    store.dispatch("auth/logout");
                    router.push(`/login`);
                });
        } else if (
            error.response.status === 401 &&
            error.response.data.message ==
                "An authentication exception occurred."
        ) {
            store.dispatch("auth/logout");
            router.push(`/login`);
        } else if (
            error.response.status === 401 &&
            error.response.data.message == "Account is locked."
        ) {
            store.dispatch("auth/logout");
            router.push(`/login`);
        } else if (
            error.response.status === 401 &&
            error.response.data.message == "Account is disabled."
        ) {
            store.dispatch("auth/logout");
            router.push(`/login`);
        } else if (error.response.status === 403) {
            ElMessage.error("Không có quyền truy cập!!!");
        }
        return Promise.reject(error);
    }
);
export default service;
