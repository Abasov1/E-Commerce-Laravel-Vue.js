import {createStore} from 'vuex';
import axios from 'axios'
import router from '../router'
import az from '../lang/az.json'
import en from '../lang/en.json'
import ru from '../lang/ru.json'
const store = createStore({
    state:{
        user:{
            data:{},
            isLoggedIn:false,
            review:false,
            language:az
        },
        loading:true,
        category:{},
        ctype:null,
        categories:{},
        allcategories:false,
        prs:{
            pras:{
                products:{},
                count:false
            },
            recentlyviewed:{}
        },
        brand:{},
        brands:{
            bras:{},
            carousel:false,
        },
        merchant:{},
        merchants:{
            mras:{},
            carousel:false,
        },
        results:false,
        allresults:false,
        soldData:false
    },
    mutations:{
        setUser(state,user){
            state.user.data = user
            state.user.isLoggedIn = true
            state.loading = false
        },
        setCat(state,categories){
            state.category = categories
        },
        setCategory(state,categories){
            state.categories = categories
        },
        setType(state,type){
            state.ctype = type
        },
        setAllCategories(state,categories){
            state.allcategories = categories
        },
        setPras(state,products){
            state.prs.pras.products = products
        },
        setProduct(state,product){
            state.prs.product = product
        },
        setCount(state,count){
            state.prs.pras.count = count
        },
        setMerchant(state,merchant){
            state.merchant = merchant
        },
        setBrand(state,brand){
            state.brand = brand
        },
        recentlyViewed(state,products){
            state.prs.recentlyviewed = products
        },
        brandCarousel(state,brands){
            state.brands.carousel = brands
        },
        merchantCarousel(state,merchants){
            state.merchants.carousel = merchants
        },
        setFilterBrands(state,brands){
            state.brands.bras = brands
        },
        setFilterMerchants(state,merchants){
            state.merchants.mras = merchants
        },
        endload(state){
            state.user.data = {}
            state.user.isLoggedIn = false
            state.loading = false
        },
        setResult(state,data){
            state.results = data
        },
        setAllResults(state,data){
            state.allresults = data
        },
        setReview(state,review){
            state.user.review = review
        },
        changeLang(state,lang){
            if(lang === 'en'){
                state.user.language = en
                localStorage.setItem('lang',lang)
            }else if(lang === 'az'){
                state.user.language = az
                localStorage.setItem('lang',lang)
            }else if(lang === 'ru'){
                state.user.language = ru
                localStorage.setItem('lang',lang)
            }
        },
        setSoldData(state,data){
            state.soldData = data
        },
        clearCart(state){
            state.user.data.cart = []
        }
    },
    actions:{
        register: async ({commit},user) => {
            await axios.post('http://127.0.0.1:8000/api/register',user).then((response)=>{
                commit('setUser',response.data.user)
                localStorage.setItem('TOKEN',response.data.token)
            })
        },
        login: async ({commit},user) => {
            await axios.post('http://127.0.0.1:8000/api/login',user)
            .then((response) => {
                commit('setUser',response.data.user);
                commit()
                localStorage.setItem('TOKEN',response.data.token)
            })
        },
        logout: async ({commit}) => {
            await axios.post('http://127.0.0.1:8000/api/logout',null,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response) => {
                window.location.reload()
            })
        },
        addWish: async ({commit},id) => {
            await axios.post('http://127.0.0.1:8000/api/addwish/'+id,null,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response) => {
                commit('setUser',response.data.user);
            })
        },
        addCart: async ({commit},id) => {
            await axios.post('http://127.0.0.1:8000/api/addcart/'+id,null,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response) => {
                commit('setUser',response.data.user);
            })
        },
        changeQuantity: async ({commit},form) => {
            await axios.post('http://127.0.0.1:8000/api/changeq',form,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response) => {
                commit('setUser',response.data.user);
            })
        },
        loadUser: async({commit}) => {
            try{
               await axios.post('http://127.0.0.1:8000/api/fryoxla',null,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    commit('setUser',response.data.user)
                })
            }catch(error){
                commit('endload')
            }
        },
        brandCarousel: async ({commit}) => {
            await axios.get('http://127.0.0.1:8000/api/brandcarousel').then((response)=>{
                commit('brandCarousel',response.data.brands.data)
            });
        },
        merchantCarousel: async ({commit}) => {
            await axios.get('http://127.0.0.1:8000/api/merchantcarousel').then((response)=>{
                commit('merchantCarousel',response.data.merchants.data)
            });
        },
        recentlyViewed: async ({commit}) => {
            await axios.get('http://127.0.0.1:8000/api/recentlyviewed').then((response)=>{
                commit('recentlyViewed',response.data.rec.data)
            });
        },
        loadcategory: async ({commit},slug) => {
            await axios.get('http://127.0.0.1:8000/api/loadcat/'+slug).then((response)=>{
                commit('setCat',response.data.categories)
                commit('setType',response.data.type)
            });
        },
        loadallcategories: async ({commit}) => {
            await axios.get('http://127.0.0.1:8000/api/loadallcats').then((response)=>{
                commit('setAllCategories',response.data.categories)
            });
        },
        loadcategories: async ({commit}) => {
            await axios.get('http://127.0.0.1:8000/api/loadcats').then((response)=>{
                commit('setCategory',response.data.categories)
            });
        },
        loadfiltcats: async ({commit},id) => {
            await axios.get('http://127.0.0.1:8000/api/loadfiltcats/'+id).then((response)=>{
                commit('setFiltCat',response.data.categories)
            });
        },
        loadproduct: async ({commit},slug) => {
            await axios.get('http://127.0.0.1:8000/api/loadpr/'+slug).then((response)=>{
                commit('setProduct',response.data.product)
            });
        },
        loadpras: async ({commit},formData) => {
            await axios.post('http://127.0.0.1:8000/api/loadpras',formData).then((response)=>{
                commit('setPras',response.data.products.data)
                commit('setCount',response.data.count)
            });
        },
        loadmrpras: async ({commit},formData) => {
            await axios.post('http://127.0.0.1:8000/api/loadmrpras',formData).then((response)=>{
                commit('setPras',response.data.products.data)
                commit('setCount',response.data.count)
            });
        },
        loadbrpras: async ({commit},formData) => {
            await axios.post('http://127.0.0.1:8000/api/loadbrpras',formData).then((response)=>{
                commit('setPras',response.data.products.data)
                commit('setCount',response.data.count)
            });
        },
        loadbrand: async ({commit},slug) => {
            await axios.get('http://127.0.0.1:8000/api/loadbr/'+slug).then((response)=>{
                commit('setBrand',response.data.brand)
                commit('setCategory',response.data.parents)
                commit('setFilterMerchants',response.data.merchants)
            });
        },
        loadbras: async ({commit},id) => {
            await axios.get('http://127.0.0.1:8000/api/loadbras/'+id).then((response)=>{
                commit('setFilterBrands',response.data.brands)
            });
        },
        loadmerchant: async ({commit},slug) => {
            await axios.get('http://127.0.0.1:8000/api/loadmer/'+slug).then((response)=>{
                commit('setMerchant',response.data.merchant)
                commit('setCategory',response.data.categories)
                commit('setFilterBrands',response.data.brands)
            });
        },
        loadmras: async ({commit},id) => {
            await axios.get('http://127.0.0.1:8000/api/loadmras/'+id).then((response)=>{
                commit('setFilterMerchants',response.data.merchants)
            });
        },
        search: async ({commit},data) => {
            await axios.post('http://127.0.0.1:8000/api/search',data).then((response)=>{
                commit('setResult',response.data.data)
            });
        },
        searchAll: async ({commit},data) => {
            await axios.post('http://127.0.0.1:8000/api/searchall',data).then((response)=>{
                commit('setAllResults',response.data.data)
            });
        },
        addReview: async ({commit},formData) => {
            await axios.post('http://127.0.0.1:8000/api/addreview',formData,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                commit('setProduct',response.data.product)
            });
        },
        loadReview: async ({commit},kartof) => {
            await axios.post('http://127.0.0.1:8000/api/loadreview',kartof,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                commit('setReview',response.data.review)
            });
        },
        soldProducts: async ({commit},formData) => {
            await axios.post('http://127.0.0.1:8000/api/soldprs',formData,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response) => {
                commit('setSoldData',response.data.data);
            })
        },
    },
    getters:{},
    modules:{}
})
export default store;
