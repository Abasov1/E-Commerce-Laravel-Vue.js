import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Surveys from '../views/Surveys.vue'
import Salam from '../views/Salam.vue'
import Sagol from '../views/Sagol.vue'
import NotFound from '../views/NotFound.vue'
import Loading from '../views/Loading.vue'
import Layout from '../components/Layout.vue'
import AuthLayout from '../components/AuthLayout.vue'
import AdminLayout from '../components/AdminLayout.vue'
import Testik from '../views/admin/Testik.vue'
import NewProduct from '../views/admin/NewProduct.vue'
import Brand from '../views/admin/Brand.vue'
import Merchant from '../views/admin/Merchant.vue'
import Category from '../views/admin/Category.vue'
import User from '../views/admin/User.vue'
import Slider from '../views/admin/Slider.vue'
import Order from '../views/admin/Order.vue'
import store from '../store/index.js'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
        path: '/admin',
        name:'Admin',
        meta:{isAdmin:true},
        redirect:'/brands',
        component: AdminLayout,
        children:[
          {
              path:'/brands',
              name:'Brand',
              component: Brand
          },
          {
            path:'/merchants',
            name:'Merchant',
            component: Merchant
          },
          {
            path:'/categories',
            name:'Category',
            component: Category
          },
          {
            path:'/product',
            name:'Product',
            component: NewProduct
          },
          {
            path:'/user',
            name:'User',
            component: User
          },
          {
            path:'/slider',
            name:'Slider',
            component: Slider
          },
          {
            path:'/order',
            name:'Order',
            component: Order
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
            }
        ]
    },
    {
        path:'/loading',
        name:'Loading',
        component:Loading
    },
    {
        path:'/:catchAll(.*)',
        redirect:'/loading'
    }
  ]
})
 router.beforeEach((to,from,next) => {
        if((to.meta.requiresAuth || to.meta.isAdmin || (to.meta.isGuest)) && store.state.loading){
            next({name:'Loading'})
        }else if((to.meta.requiresAuth || to.meta.isAdmin) && !store.state.user.admin && !store.state.user.token){
            next({name:'Salam'})
        }else if((to.meta.requiresAuth || (to.meta.isGuest)) && store.state.user.admin){
            next({name:'Product'})
        }
        else if(((to.meta.isGuest) || to.meta.isAdmin) && !store.state.user.admin && store.state.user.token){
            next({name:'Dashboard'})
        }
        else{
            next()
        }
    }

 )
export default router
