import { createRouter, createWebHistory } from 'vue-router'
import ProductLayout from '../components/ProductLayout.vue'
import RecentlyViewed from '../components/RecentlyViewed.vue'
import Test from '../components/Test.vue'
import Home from '../views/Home.vue'
import Search from '../views/Search.vue'
import WishList from '../views/WishList.vue'
import Cart from '../views/Cart.vue'
import Checkout from '../views/Checkout.vue'
import Sold from '../views/Sold.vue'
import Merchant from '../views/Merchant.vue'
import Brand from '../views/Brand.vue'
import Product from '../views/Product.vue'
import Categories from '../views/Categories.vue'
import Category from '../views/Category.vue'
import SubCategory from '../views/SubCategory.vue'
import SubSubCategory from '../views/SubSubCategory.vue'
import store from '../store'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/:catchAll(.*)',
      redirect:'/',
    },
    {
      path: '/test',
      name: 'Test',
      component: Test,
    },
    {
      path: '/home',
      name: 'ProductLayout',
      component: ProductLayout,
      redirect:'/',
      children:[
        {
          path: '/',
          name: 'Home',
          component: Home,
        },
        {
          path: '/search/:index',
          name: 'Search',
          component: Search,
          props:true
        },
        {
          path: '/wishlist',
          name: 'WishList',
          component: WishList,
        },
        {
          path: '/cart',
          name: 'Cart',
          component: Cart,
        },
        {
          path: '/checkout',
          name: 'Checkout',
          component: Checkout,
        },
        {
          path: '/sold',
          name: 'Sold',
          component: Sold,
        },
        {
          path: '/product/:slug',
          name: 'Product',
          component: Product,
          props:true
        },
        {
          path: '/merchant/:slug',
          name: 'Merchant',
          component: Merchant,
          props:true
        },
        {
          path: '/brand/:slug',
          name: 'Brand',
          component: Brand,
          props:true
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
