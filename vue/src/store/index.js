import {createStore} from 'vuex';
import axiosClient from '../axios'
import axios from 'axios'
import router from '../router'
const store = createStore({
    state:{
        user:{
            data:null,
            token:null,
            admin:null
        },
        loading: true,
        search:{
            merchant:null
        },
        category:false
    },
    getters:{},
    mutations:{
        logout(state){
            state.user.data = {};
            state.user.token = null;
            localStorage.removeItem('TOKEN')
        },
        register(state,data){
            state.user.data = data.user
            state.user.token = localStorage.setItem('TOKEN',data.token)
            if(data.user.is_admin){
                state.user.admin = true
            }
            state.loading = false
        },
        loadik(state,data){
            state.user.data = data.user
            state.user.token = localStorage.getItem('TOKEN')
            if(data.user.role === 'admin' || data.user.role === 'moderator'){
                state.user.admin = true
            }
            state.loading = false
        },
        endload(state){
            localStorage.removeItem('TOKEN');
            state.loading = false
        },
        setCategory(state,category){
            state.category = category
        }
    },
    actions:{
        register: async ({commit},user) => {
                await axios.post('http://127.0.0.1:8000/api/register',user)
                .then((response) => {
                    commit('register',response.data);
                })
            },
        login: async ({commit},user) => {
                await axios.post('http://127.0.0.1:8000/api/login',user)
                .then((response) => {
                    commit('register',response.data);
                })
            },
        alogin: async ({commit},user) => {
                await axios.post('http://127.0.0.1:8000/api/alogin',user)
                .then((response) => {
                    commit('register',response.data);
                })
            },
        logout: async ({commit}) => {
                await axios.post('http://127.0.0.1:8000/api/logout',null,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response) => {
                    router.push('Salam')
                }).catch(err=>{
                    console.log(err.response.data.message)
                })
            },
        loadUser: async({commit,dispatch}) => {
            try{
               await axios.post('http://127.0.0.1:8000/api/yoxla',null,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    commit('loadik',response.data)
                    router.push({name:'Order'})
                })
            }catch(error){
                commit('endload')
                router.push({name:'Salam'})
            }
        },
        addproduct: async({commit},selected) => {
            try{
                await axios.post('http://127.0.0.1:8000/api/addproduct',selected,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    alert(response.data.message)
                })
            }catch(error){
                alert(error.response.data.message)
            }
        },
        addbrand: async({commit},selected) => {
                await axios.post('http://127.0.0.1:8000/api/addbrand',selected,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    alert(response.data.message)
                })
        },
        addmerchant: async({commit},selected) => {
            await axios.post('http://127.0.0.1:8000/api/addmerchant',selected,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                alert(response.data.message)
            })
        },
        addcategory: async({commit},selected) => {
            await axios.post('http://127.0.0.1:8000/api/addcategory',selected,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                commit('setCategory',response.data.category)
                alert(response.data.message)
            })
        },
        adduser: async({commit},selected) => {
            await axios.post('http://127.0.0.1:8000/api/adduser',selected,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                alert(response.data.message)
            }).catch((error)=>{
                alert(error.response.data.message)
            })
        }
    },
    modules:{}
})

export default store;
