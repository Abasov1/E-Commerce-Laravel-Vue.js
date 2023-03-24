<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Horizontal Layouts</h4>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Brand input</h5> <small class="text-muted float-end">netersen gulenver</small>
                </div>
                <div class="card-body">
                    <form @submit.prevent="makeBrand">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name <b v-if="v$.name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.name" type="text" class="form-control" :class="[v$.name.$error ? 'is-invalid' : '',branderror ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            <span v-if='branderror' style="color:rgb(255,62,29)">{{branderror}}</span>
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
                                    <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
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
                                    <input v-model="page.search" v-debounce:400ms="loadBrands" type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="datatables-basic table border-top">
                <thead>
                    <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="brand in brands" :key="brand.id">
                        <td class="dt-checkboxes-cell">
                            <input type="checkbox" class="dt-checkboxes form-check-input">
                        </td>
                        <td>
                            <div class="d-flex justify-content-start align-items-center user-name">
                                <div class="avatar-wrapper">
                                    <div class="avatar me-2">
                                        <span class="avatar-initial rounded-circle bg-label-warning">GG</span>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="emp_name text-truncate">{{brand.name}}</span>
                                </div>
                            </div>
                        </td>
                        <td>ggiacoppo2r@apache.org</td>
                        <td>04/15/2021</td>
                        <td>$24973.48</td>
                        <td><span class="badge  bg-label-success">Professional</span></td>
                        <td>
                            <a @click.prevent="deletE(brand.id)" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-trash"></i></a>
                            <a href="javascript:;" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
            </div>
            </div>
            <!-- Modal to add new record -->
            <div class="offcanvas offcanvas-end" id="add-new-record">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="exampleModalLabel">New Record</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body flex-grow-1">
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" onsubmit="return false">
                <div class="col-sm-12">
                    <label class="form-label" for="basicFullname">Full Name</label>
                    <div class="input-group input-group-merge">
                    <span id="basicFullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                    <input type="text" id="basicFullname" class="form-control dt-full-name" name="basicFullname" placeholder="John Doe" aria-label="John Doe" aria-describedby="basicFullname2" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="basicPost">Post</label>
                    <div class="input-group input-group-merge">
                    <span id="basicPost2" class="input-group-text"><i class='bx bxs-briefcase'></i></span>
                    <input type="text" id="basicPost" name="basicPost" class="form-control dt-post" placeholder="Web Developer" aria-label="Web Developer" aria-describedby="basicPost2" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="basicEmail">Email</label>
                    <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                    <input type="text" id="basicEmail" name="basicEmail" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" />
                    </div>
                    <div class="form-text">
                    You can use letters, numbers & periods
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="basicDate">Joining Date</label>
                    <div class="input-group input-group-merge">
                    <span id="basicDate2" class="input-group-text"><i class='bx bx-calendar'></i></span>
                    <input type="text" class="form-control dt-date" id="basicDate" name="basicDate" aria-describedby="basicDate2" placeholder="MM/DD/YYYY" aria-label="MM/DD/YYYY" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="basicSalary">Salary</label>
                    <div class="input-group input-group-merge">
                    <span id="basicSalary2" class="input-group-text"><i class='bx bx-dollar'></i></span>
                    <input type="number" id="basicSalary" name="basicSalary" class="form-control dt-salary" placeholder="12000" aria-label="12000" aria-describedby="basicSalary2" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
                </form>

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
    const branderror = ref('')
    const brands = ref([])
    const page = reactive({
        page:'1',
        search:'',
        perPage:'10'
    })
    const selected = reactive({
        name:'',
    })
    const rules = {
        name:{required},
    }

    watch(()=>selected.name,()=>{
        if(branderror){
            branderror.value = ''
        }
    })

    const v$ = useVuelidate(rules,selected)

    const makeBrand = () => {
        v$.value.$validate()
        if(v$.value.$error){
            return
        }
        store.dispatch('addbrand',selected).then(async()=>{
            await loadBrands()
        }).catch(err=>{
            branderror.value = err.response.data.message
        })
    }

    const reset = () => {
        selected.name = ''
        v$.value.$reset()
    }

    onMounted(async()=>{
        await loadBrands()
    })

    const deletE = async(id) => {
        if(window.confirm('Are you sure you want to delete?')){
            await axios.post('http://127.0.0.1:8000/api/deletebrand/'+id,null,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    loadBrands()
                    alert(response.data.message)
                })
        }
    }
    const loadBrands = async() => {
        await axios.post('http://127.0.0.1:8000/api/loadbrands',page,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    brands.value = response.data.brands.data
                })
    }

    </script>

    <style scoped>

    </style>
