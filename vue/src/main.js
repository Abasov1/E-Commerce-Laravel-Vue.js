import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store/index.js'
import { vue3Debounce } from 'vue-debounce'
import { assetMixin } from './assetMixin.js'
import './vondor.js'
const app = createApp(App)
app.mixin(assetMixin)
app.use(router)
app.directive('debounce', vue3Debounce({ lock: true }))
app.use(store)
app.mount('#app')
