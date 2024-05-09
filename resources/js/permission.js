import router from "./router";
import store from "./store";
import { ElMessage } from 'element-plus'
import NProgress from "nprogress"; // progress bar
import "nprogress/nprogress.css"; // progress bar style
import { getAccessToken, removeToken, setLayout } from "@/utils/auth"; // get token from cookie
import getPageTitle from "@/utils/get-page-title";

NProgress.configure({ showSpinner: false }); // NProgress Configuration

const whiteList = ["/login","/logout", "/auth-redirect"]; // no redirect whitelist
router.beforeEach(async (to, from, next) => {
    // start progress bar
    NProgress.start();    
    // set page title
    document.title = getPageTitle(to.meta.title);
    // determine whether the user has logged in
    //removeToken();
    const hasToken = getAccessToken();
    //const hasToken = null;
    if (hasToken && hasToken != "undefined") {
        const hasRoles = store.getters.roles && store.getters.roles.length > 0;
        setLayout(to.meta.active);
        if (hasRoles) {
            let roles = store.getters.roles
            const accessRoutes = await store.dispatch(
                "permission/generateRoutes",
                roles
            );
            accessRoutes.forEach(route => {
                router.addRoute(route);
            });
            next();
        } else {
            var roles = await store.dispatch("user/getRoles");
            if (store.getters.roles.length <= 0) {
                store.dispatch("user/logout");
                router.push(`/login`);
            }
            var info = await store.dispatch("user/getInfo");
            // generate accessible routes map based on roles
            const accessRoutes = await store.dispatch(
                "permission/generateRoutes",
                roles
            );
            accessRoutes.forEach(route => {
                router.addRoute(route);
            });
            if (to.path === "/login") {
                next();
                NProgress.done();
            } else {
                next({ ...to, replace: true });
            }
        }
    } else {
        /* has no token*/

        if (whiteList.indexOf(to.path) !== -1) {
            // in the free login whitelist, go directly
            next();
        } else {
            // other pages that do not have permission to access are redirected to the login page.
            //next(`/login?redirect=${to.path}`);
            next();
            //next();
            NProgress.done();
        }
    }
});

router.afterEach(() => {
    // finish progress bar
    NProgress.done();
});
