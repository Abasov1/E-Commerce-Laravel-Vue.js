import { createRouter, createWebHistory } from 'vue-router'
import HomeLayout from '../components/HomeLayout.vue'
import Home from '../views/Home.vue'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'HomeLayout',
      redirect:'/home',
      component: HomeLayout,
      children:[
        {
            path: '/home',
            name: 'Home',
            component: Home,
        }
      ]
    },
  ]
})

export default router
