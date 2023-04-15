import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import { vue3Debounce } from 'vue-debounce'
import './vondor.js'
import './assets/vendor/font-awesome/css/fontawesome-all.min.css'
import { createI18n } from 'vue-i18n'
const messages = {
  en:{
    message:{
      welcome:'welcome'
    }
  },
  az:{
    message:{
      welcome:'xos gelmisen'
    }
  }
}
const i18n = createI18n({
    locale: 'az',
    legacy: false,
  })
const app = createApp(App)
app.use(i18n)
app.use(router)
app.use(store)
app.directive('debounce', vue3Debounce({ lock: true }))
app.mount('#app')
