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
        }
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
            if(data.user.is_admin){
                state.user.admin = true
            }
            state.loading = false
        },
        endload(state){
            localStorage.removeItem('TOKEN');
            state.loading = false
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
                    router.push({name:'Category'})
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
                console.log(error)
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
                alert(response.data.message)
            })
        }
    },
    modules:{}
})

export default store;
