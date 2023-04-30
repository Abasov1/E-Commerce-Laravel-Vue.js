<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Orders</h4>
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
                                            <input v-model="page.search" v-debounce:400ms="searchOrders" type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="datatables-basic table border-top">
                        <thead>
                            <tr>
                            <th>id</th>
                            <th>User</th>
                            <th>Product</th>
                            <th>Trasnaction_id</th>
                            <th>Date</th>
                            </tr>
                        </thead>
                        <tbody v-if="orders">
                            <tr v-for="or in orders" :key="or.id">
                                <td class="dt-checkboxes-cell">
                                    <input type="checkbox" class="dt-checkboxes form-check-input">
                                </td>
                                <td style="cursor:pointer;overflow:hidden;">
                                    <div class="d-flex justify-content-start align-items-center user-name">
                                        <div class="d-flex flex-column" style="overflow:hidden;max-width:150px;">
                                            <span class="emp_name text-truncate">{{or.user.email}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td @click="showPreview(or)" style="cursor:pointer;">View <i class="bx bx-right-top-arrow-circle"></i></td>
                                <td>{{or.transaction_id}}</td>
                                <td>{{or.date}}</td>
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
         <div v-if="preview" class="container fixed-top fixed-bottom d-flex justify-content-center align-items-center">
                <div class="card text-center position-relative shadow-lg" style="width:70%;height:80%">
                    <button @click="closePreview" type="button" class="btn btn-primary me-4 close position-absolute top-0 end-0 m-3" aria-label="Close">X</button>
                    <div class="card-body scroll-magic" style="overflow:scroll;">
                        <h3 class="card-title">Products</h3>
                        <div class="row">
                            <div class="col">
                                Product Name
                            </div>
                            <div class="col">
                                Total
                            </div>
                        </div>
                        <div v-for="pr in previewMaterial.products" :key="pr.id" class="row">
                            <div class="col">
                                {{pr.name}}
                            </div>
                            <div v-text="getPrice(pr.pivot.quantity * pr.price)" class="col">
                            </div>
                        </div>
                        <div style="border-bottom: 1px solid black;margin:5px 0px;"></div>
                        <div class="row mt-auto">
                            <div class="col">
                            </div>
                            <div v-text="getPrice(previewMaterial.products.reduce((acc,item)=>acc + (item.pivot.quantity * item.price),0))" class="col">
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
const loading = ref(false)
const orders = ref(false)
const preview = ref(false)
const pagesCount = ref('')
const userCount = ref('')
const previewMaterial = reactive({
    products:[]
})
const page = reactive({
    page:1,
    search:'',
    perPage:'10'
})
watch(()=>page.perPage,()=>{
    page.page = 1
    loadOrders()
})
onMounted(()=>{
    loadOrders()
})
const loadOrders = async() => {
    await axios.post('http://127.0.0.1:8000/api/loadorders',page,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                orders.value = response.data.orders.data
                pagesCount.value = response.data.last
                userCount.value = response.data.count
            })
}
const searchOrders = () => {
    page.page = 1
    loadOrders()
}
const showPreview = (order) => {
    preview.value = true
    previewMaterial.products = order.products
}
const closePreview = () => {
    preview.value = false
    previewMaterial.products = []
}
const changePage = (n) => {
    page.page = n
    loadOrders()
}
const getPrice = (pr) => {
    return (pr / 100).toLocaleString('en-US',{style:'currency',currency:'USD'});
}
</script>

<style scoped>
    
</style>
