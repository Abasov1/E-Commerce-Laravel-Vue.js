<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Users</h4>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4 position-relative">
                    <div v-if="loading" style="position:absolute;z-index:99999;width:100%;height:100%;background-color:white;opacity:50%;"></div>
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Product input</h5>
                </div>
                <div class="card-body">
                    <form @submit.prevent="makeUser">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name<b v-if="selected.id && !v$.name.$error" style="color:yellow;"> - updating</b><b v-if="v$.name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                                <input v-model="selected.name" type="text" class="form-control" :class="[v$.name.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                                <span v-if="v$.name.$error" style="color:red;">{{v$.name.$errors[0].$message}}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Email<b v-if="selected.id && !v$.email.$error" style="color:yellow;"> - updating</b><b v-if="v$.email.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                                <input v-model="selected.email" type="text" class="form-control" :class="[v$.email.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                                <span v-if="v$.email.$error" style="color:red;">{{v$.email.$errors[0].$message}}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Password<b v-if="selected.id && !v$.password.$error" style="color:yellow;"> - updating</b><b v-if="v$.password.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                                <input v-model="selected.password" type="text" class="form-control" :class="[v$.password.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                                <span v-if="v$.password.$error" style="color:red;">{{v$.password.$errors[0].$message}}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Is Moderator</label>
                            <div class="col-sm-10">
                                <input v-model="selected.is_moderator" type="checkbox" class="dt-checkboxes form-check-input">
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                            <button class="btn btn-primary">Send</button>
                            <button @click="reset" type="button" class="btn btn-primary ms-2">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <div class="card pt-4" v-if="users">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_length" id="DataTables_Table_0_length">
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            <label>Show</label>
                                        </div>
                                        <div class="col-4 d-flex justify-content-center align-items-center">
                                            <select v-model="page.perPage" name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                                                <option value="1">1</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="75">75</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="col-6 d-flex justify-content-start" >
                                            entries
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                                <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            <label>Search:</label>
                                        </div>
                                        <div class="col-10">
                                            <input v-model="page.search" v-debounce:400ms="searchUsers" type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="datatables-basic table border-top">
                        <thead>
                            <tr>
                            <th>id</th>
                            <th @click="order" class="d-flex justify-content-between">Name <i style="color:rgb(187,195,204)" :class="[page.orderBy ? 'bx bxs-chevron-up':'bx bxs-chevron-down']"></i></th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id">
                                <td class="dt-checkboxes-cell">
                                    <input type="checkbox" class="dt-checkboxes form-check-input">
                                </td>
                                <td style="cursor:pointer;overflow:hidden;">
                                    <div class="d-flex justify-content-start align-items-center user-name">
                                        <div class="d-flex flex-column" style="overflow:hidden;max-width:100px;">
                                            <span class="emp_name text-truncate">{{user.name}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{user.email}}</td>
                                <td>{{user.role}}</td>
                                <td>
                                    <a @click.prevent="deletE(user.id)" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-trash"></i></a>
                                    <a @click.prevent="editE(user)" href="javascript:;" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>
                                </td>
                            </tr>
                        </tbody>
                        </table>
                        <div class="row" style="padding-left:40px;padding-top:20px;">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing {{page.page}} to {{page.perPage}} of {{userCount}} products</div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    <ul class="pagination">
                                        <li class="paginate_button page-item previous" :class="[page.page === 1 ? 'disabled' : '']" id="DataTables_Table_0_previous">
                                            <a @click.prevent="previousPage" href="#" aria-controls="DataTables_Table_0" data-dt-idx="previous" tabindex="0" class="page-link">Previous</a>
                                        </li>
                                        <li v-for="n in pagesCount" :key="n"  class="paginate_button page-item active">
                                            <a @click.prevent="changePage(n)" v-show="n < page.page + 5 && page.page < n+2 && n != pagesCount" href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" class="page-link" :style="[page.page == n ? 'background-color:rgb(105,108,255);color:white;' : 'background-color:rgb(240,242,244);color:rgb(105,122,141);']">{{n}}</a>
                                        </li>
                                        <li v-if='pagesCount - page.page > 5' class="paginate_button page-item disabled" id="DataTables_Table_0_ellipsis">
                                            <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="ellipsis" tabindex="0" class="page-link">…</a>
                                        </li>
                                        <li class="paginate_button page-item ">
                                            <a @click.prevent="changePage(pagesCount)" href="#" aria-controls="DataTables_Table_0" data-dt-idx="14" tabindex="0" class="page-link" :style="[page.page === pagesCount ? 'background-color:rgb(105,108,255);color:white;' : 'background-color:rgb(240,242,244);color:rgb(105,122,141);']">{{pagesCount}}</a>
                                        </li>
                                        <li class="paginate_button page-item next" :class="[page.page === pagesCount ? 'disabled' : '']" id="DataTables_Table_0_next">
                                            <a @click.prevent="nextPage" href="#" aria-controls="DataTables_Table_0" data-dt-idx="next" tabindex="0" class="page-link">Next</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>


<script setup>
import { ref,reactive,watch,onMounted } from 'vue'
import store from '../../store'
import router from '../../router'
import axios from 'axios'
import { useVuelidate } from '@vuelidate/core'
import { required,email } from '@vuelidate/validators'
const loading = ref(false)
const users = ref(false)
const pagesCount = ref('')
const userCount = ref('')
const page = reactive({
    page:1,
    search:'',
    perPage:'10',
    order:false,
    orderBy:true
})
watch(()=>page.perPage,()=>{
    page.page = 1
    loadUsers()
})
const selected = reactive({
    name:'',
    email:'',
    password:'',
    is_moderator:false,
    id:null
})
const rules = {
    name:{required},
    email:{required,email},
    password:{required}
}
const v$ = useVuelidate(rules,selected)
onMounted(()=>{
    if(store.state.user.data.length != 0){
        if(store.state.user.data.role === 'moderator'){
            router.push({name:'Product'})
        }
    }
    loadUsers()
})
const makeUser = () => {
    if(loading.value){
        return
    }
    v$.value.$validate()
    if(v$.value.$error){
        return
    }
    loading.value = true
    store.dispatch('adduser',selected).then(async()=>{
            page.order = false
            await loadUsers()
            loading.value = false
        }).catch(()=>{
            loading.value = false
        })
}
const loadUsers = async() => {
    await axios.post('http://127.0.0.1:8000/api/loadusers',page,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                users.value = response.data.users.data
                pagesCount.value = response.data.last
                userCount.value = response.data.count
            })
}
const reset = () => {
    selected.name = ''
    selected.email = ''
    selected.password = ''
    selected.id = false
    selected.is_moderator = false
    v$.value.$reset()
}
const deletE = async(id) => {
    if(window.confirm('Are you sure?')){
        await axios.post('http://127.0.0.1:8000/api/deleteuser/'+id,null,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((repsonse)=>{
                alert(repsonse.data.message)
                loadUsers()
            }).catch((error)=>{
                alert(error.response.data.message)
            })
    }
}
const editE = (user) => {
    reset()
    selected.id = user.id
    selected.name = user.name
    selected.email = user.email
    if(user.role === 'moderator'){
        selected.is_moderator = true
    }
}
const changePage = (n) => {
    page.page = n
    loadUsers()
}
const searchUsers = () => {
    page.page = 1
    loadUsers()
}
</script>

<style scoped>
    
</style>
