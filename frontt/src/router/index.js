import { createRouter, createWebHistory } from 'vue-router'
import HomeLayout from '../components/HomeLayout.vue'
import ProductLayout from '../components/ProductLayout.vue'
import Home from '../views/Home.vue'
import Categories from '../views/Categories.vue'
import Category from '../views/Category.vue'
import SubCategory from '../views/SubCategory.vue'
import SubSubCategory from '../views/SubSubCategory.vue'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'ProductLayout',
      component: ProductLayout,
      redirect:'/home',
      children:[
        {
          path: '/home',
          name: 'Home',
          component: Home,
        },
        {
          path: '/categories/:fparent/:sparent/:slug',
          name: 'SubSubCategory',
          component: SubSubCategory,
          props:true
        },
        {
          path: '/categories/:parent/:slug',
          name: 'SubCategory',
          component: SubCategory,
          props:true
        },
        {
          path: '/categories/:slug',
          name: 'Category',
          component: Category,
          props:true
        },  
        {
            path: '/categories',
            name: 'Categories',
            component: Categories,
        },
      ]
    },
  ]
})

export default router
