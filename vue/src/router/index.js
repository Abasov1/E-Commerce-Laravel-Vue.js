import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Surveys from '../views/Surveys.vue'
import Salam from '../views/Salam.vue'
import Sagol from '../views/Sagol.vue'
import Layout from '../components/Layout.vue'
import AuthLayout from '../components/AuthLayout.vue'
import store from '../store/index.js'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect:'/dashboard',
      meta:{requiresAuth:true},
      component: Layout,
      children:[
        {
            path:'/dashboard',
            name:'Dashboard',
            component:Dashboard
        },
        {
            path:'/surveys',
            name:'Surveys',
            component:Surveys
        }
      ]

    },
    {
        path:'/auth',
        name: 'Auth',
        meta:{isGuest:true},
        redirect:'/salam',
        component:AuthLayout,
        children: [
            {
                path:'/salam',
                name:'Salam',
                component: Salam
            },
            {
                path:'/sagol',
                name:'Sagol',
                component: Sagol
            }
        ]
    }
  ]
})
router.beforeEach((to,from,next) => {
    if(to.meta.requiresAuth && !store.state.user.token){
        next({name:'Salam'})
    } else if(store.state.user.token && (to.meta.isGuest)){
        next({name:'Dashboard'})
    }
    else{
        next()
    }
}
)
export default router
