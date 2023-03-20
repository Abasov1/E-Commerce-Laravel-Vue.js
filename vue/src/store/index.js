import {createStore} from 'vuex';
import axiosClient from '../axios'
import axios from 'axios'
const store = createStore({
    state:{
        user:{
            data:{},
            token:sessionStorage.getItem('TOKEN')
        }
    },
    getters:{},
    mutations:{
        logout(state){
            state.user.data = {};
            state.user.token = null;
            sessionStorage.removeItem('TOKEN')
        },
        register(state,user){
            state.user.data = user.data
            state.user.token = user.token
            sessionStorage.setItem('TOKEN',user.token)
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
                        Authorization: 'Bearer '+sessionStorage.getItem('TOKEN')
                    }
                })
                .then(() => {
                    commit('logout');
                })
            }
    },
    modules:{}
})

export default store;
