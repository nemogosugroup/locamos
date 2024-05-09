import { createApp } from 'vue';
import Cookies from 'js-cookie';
import { createI18n } from 'vue-i18n';
import 'normalize.css/normalize.css';
import 'remixicon/fonts/remixicon.css';
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';
import App from './App.vue';
import store from '@/store';
import router from '@/router';
//import '@/icons'; // icon
import '@/permission'; // permission control
import SvgIcon from '@/components/SvgIcon'; // svg component
import mitt from 'mitt';
const emitter = mitt();
let locale = store.getters.locale

// register globally
// messages
import messages from '@/i18n/index.js';
import { getLocale } from "@/utils/auth"; // get lang
const lang = getLocale() ?? 'vi';
const i18n = createI18n({
  locale: lang,
  fallbackLocale: 'en',
  messages:messages,
});
const app = createApp(App);
app.config.globalProperties.emitter = emitter;
app.component('svg-icon', SvgIcon); // Bạn có thể bật dòng này nếu bạn đang sử dụng component SvgIcon
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
  app.component(key, component)
}
// app.config.productionTip = false;
import RepositoryFactory from '@/utils/RepositoryFactory';
import AdminRepositoryFactory from '@/backend/respository/index';
app.provide('$RepositoryFactory', RepositoryFactory);
app.provide('$AdminRepositoryFactory', AdminRepositoryFactory);
import VueFireworks from 'vue3-damp-fireworks';
app.use(VueFireworks);

//use i18n
app.use(i18n);

app.use(ElementPlus, {
  size: Cookies.get('size') || 'default', // set element-plus default size
  locale: locale // Bạn có thể sử dụng locale nếu cần, hãy đảm bảo đã import và cấu hình nó
});
app.use(store).use(router).mount('#app');