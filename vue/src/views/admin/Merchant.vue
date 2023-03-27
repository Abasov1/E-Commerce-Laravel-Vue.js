<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Horizontal Layouts</h4>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Merchant input</h5> <small class="text-muted float-end">netersen gulenver</small>
                </div>
                <div class="card-body">
                    <form @submit.prevent="makeMerchant">
                        <div v-if="previewUrl" class="row mb-3">
                            <label class="col-sm-2 mt-auto mb-auto col-form-label" for="basic-default-name">Preview</label>
                            <div class="col-sm-10">
                                <img :src="previewUrl">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="formFile" class="col-sm-2 col-form-label" >Brand Image</label>
                            <div class="col-sm-10">
                                <input @change="setImage" class="form-control" type="file" id="formFile">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name  <b v-if="selected.id && !v$.name.$error" style="color:yellow;"> - updating</b><b v-if="v$.name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.name" type="text" class="form-control" :class="[v$.name.$error ? 'is-invalid' : '',merchanterror ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            <span v-if='merchanterror' style="color:rgb(255,62,29)">{{merchanterror}}</span>
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
        <div class="card pt-4">
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
                                        <input v-model="page.search" v-debounce:400ms="searchMerchants" type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="datatables-basic table border-top">
                    <thead>
                        <tr>
                        <th>id</th>
                        <th @click="order" class="d-flex justify-content-between">Name <i style="solor:rgb(187,195,204)" :class="[page.orderBy ? 'bx bxs-chevron-up':'bx bxs-chevron-down']"></i></th>
                        <th>Products count</th>
                        <th>Date</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="merchant in merchants" :key="merchant.id">
                            <td class="dt-checkboxes-cell">
                                <input type="checkbox" class="dt-checkboxes form-check-input">
                            </td>
                            <td>
                                <div class="d-flex justify-content-start align-items-center user-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar me-2">
                                            <span v-if="merchant.image === 'default.png'" class="avatar-initial rounded-circle bg-label-warning">{{merchant.name.charAt(0)}}</span>
                                            <img v-else :src="'http://127.0.0.1:8000/api/images/'+merchant.image" alt="Avatar" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column" style="overflow:hidden;max-width:100px;">
                                        <span class="emp_name text-truncate">{{merchant.name}}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{merchant.prcount}}</td>
                            <td>{{merchant.date}}</td>
                            <td>
                                <a @click.prevent="deletE(merchant.id)" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-trash"></i></a>
                                <a @click.prevent="editE(merchant.id,merchant.name)" href="javascript:;" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>

                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <div class="row" style="padding-left:40px;padding-top:20px;">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing {{page.perPage}} of {{merchantCount}} merchants</div>
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
    import axios from 'axios'
    import { useVuelidate } from '@vuelidate/core'
    import { required } from '@vuelidate/validators'
    const merchanterror = ref('')
    const merchants = ref([])
    const pagesCount = ref('')
    const previewUrl = ref('')
    const merchantCount = ref('')
    const page = reactive({
        page:1,
        search:'',
        perPage:'10',
        order:false,
        orderBy:true
    })
    const currentPage = parseInt(page.page)
    const selected = reactive({
        name:'',
        image:null,
        id:''
    })
    const rules = {
        name:{required},
    }

    watch(()=>selected.name,()=>{
        if(merchanterror){
            merchanterror.value = ''
        }
    })

    watch(()=>page.perPage,()=>{
        page.page = 1
        loadMerchants()
    })

    const v$ = useVuelidate(rules,selected)
    const setImage = (event) => {
        selected.image = event.target.files[0]
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target.result;
        };
        reader.readAsDataURL(selected.image);
    }
    const makeMerchant = () => {
        v$.value.$validate()
        if(v$.value.$error){
            return
        }
        const formData = new FormData()
        formData.append("image",selected.image)
        formData.append("name",selected.name)
        formData.append("id",selected.id)
        store.dispatch('addmerchant',formData).then(async()=>{
            page.order = false
            await loadMerchants()
        }).catch(err=>{
            merchanterror.value = err.response.data.message
        })
    }

    const reset = () => {
        selected.name = ''
        selected.id = ''
        v$.value.$reset()
    }
    onMounted(async()=>{
        await loadMerchants()
    })

    const deletE = async(id) => {
        if(window.confirm('Are you sure you want to delete?')){
            await axios.post('http://127.0.0.1:8000/api/deletemerchant/'+id,null,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    loadMerchants()
                    alert(response.data.message)
                })
        }
    }
    const editE = (id,name) => {
        selected.id = id
        selected.name = name
        window.location.href = '#'
    }
    const searchMerchants = () => {
        page.page = 1
        loadMerchants()
    }
    const order = () => {
        page.order = true
        loadMerchants()
        page.orderBy = !page.orderBy
    }
    const loadMerchants = async() => {
        await axios.post('http://127.0.0.1:8000/api/loadmerchants',page,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    merchants.value = response.data.merchants.data
                    pagesCount.value = response.data.last
                    merchantCount.value = response.data.count
                })
    }
    const changePage = (n) => {
        page.page = n
        loadMerchants()
    }
    const previousPage = () => {
        if(page.page != 1){
            page.page--
            loadMerchants()
        }
    }
    const nextPage = () => {
        if(page.page != pagesCount){
            page.page++
            loadMerchants()
        }
    }
    </script>

    <style scoped>

    </style>
