import {createStore} from 'vuex';
import axios from 'axios'
const store = createStore({
    state:{
        category:{},
        ctype:null,
        categories:{},
        allcategories:{},
        prs:{
            pras:{
                products:{},
                count:false
            },
            bestpr1:{},
            bestpr2:{},
            bestpr3:{},
            recpr1:{},
            recpr2:{},
            recpr3:{}
        },
        brands:{
            bras:{},
            brand1:{},
            brand2:{},
            brand3:{}
        }
    },
    mutations:{
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
        setCount(state,count){
            state.prs.pras.count = count
        },
        bestPr1(state,pr1){
            state.prs.bestpr1 = pr1
        },
        bestPr2(state,pr2){
            state.prs.bestpr2 = pr2
        },
        bestPr3(state,pr3){
            state.prs.bestpr3 = pr3
        },
        recPr1(state,pr1){
            state.prs.recpr1 = pr1
        },
        recPr2(state,pr2){
            state.prs.recpr2 = pr2
        },
        recPr3(state,pr3){
            state.prs.recpr3 = pr3
        },
        br1(state,br1){
            state.brands.brand1 = br1
        },
        br2(state,br2){
            state.brands.brand2 = br2
        },
        br3(state,br3){
            state.brands.brand3 = br3
        },
        setFilterBrands(state,brands){
            state.brands.bras = brands
        }
    },
    actions:{
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
        loadproducts: async ({commit}) => {
            await axios.get('http://127.0.0.1:8000/api/loadprs').then((response)=>{
                commit('bestPr1',response.data.bestpr1.data)
                commit('bestPr2',response.data.bestpr2.data)
                commit('bestPr3',response.data.bestpr3.data)
                commit('recPr1',response.data.recpr1.data)
                commit('recPr2',response.data.recpr2.data)
                commit('recPr3',response.data.recpr3.data)
            });
        },
        loadpras: async ({commit},formData) => {
            await axios.post('http://127.0.0.1:8000/api/loadpras',formData).then((response)=>{
                commit('setPras',response.data.products.data)
                commit('setCount',response.data.count)
            });
        },
        loadbrands: async ({commit}) => {
            await axios.get('http://127.0.0.1:8000/api/loadbrs').then((response)=>{
                commit('br1',response.data.br1.data)
                commit('br2',response.data.br2.data)
                commit('br3',response.data.br3.data)
            });
        },
        loadbras: async ({commit}) => {
            await axios.get('http://127.0.0.1:8000/api/loadbras').then((response)=>{
                commit('setFilterBrands',response.data.brands)
            });
        },

    },
    getters:{},
    modules:{}
})
export default store;
