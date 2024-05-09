import { createStore } from 'vuex';
import getters from './getters';

const modulesFiles = require.context('./modules', false, /\.js$/);
const modules = modulesFiles.keys().reduce((acc, modulePath) => {
  const moduleName = modulePath.replace(/^\.\/(.*)\.\w+$/, '$1');
  const value = modulesFiles(modulePath);
  acc[moduleName] = value.default;
  return acc;
}, {});
const store = createStore({
  modules,
  getters,
});

export default store;