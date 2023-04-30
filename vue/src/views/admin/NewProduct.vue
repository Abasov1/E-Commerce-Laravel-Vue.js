<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Products</h4>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4 position-relative">
                    <div v-if="productSending" style="position:absolute;z-index:99999;width:100%;height:100%;background-color:white;opacity:50%;"></div>
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Product input</h5>
                    <div>
                        <span @click="selectedLanguage = 1" :style="[selectedLanguage === 1 ? 'color:blue;' : ''],[(v$.az_name.$error || v$.az_inf.$error) && selectedLanguage != 1 ? 'color:red;' : '']" style="margin-left:10px;cursor:pointer">AZ</span>
                        <span @click="selectedLanguage = 2" :style="[selectedLanguage === 2 ? 'color:blue;' : ''],[(v$.en_name.$error || v$.en_inf.$error) && selectedLanguage != 2 ? 'color:red;' : '']" style="margin-left:10px;cursor:pointer">EN</span>
                        <span @click="selectedLanguage = 3" :style="[selectedLanguage === 3 ? 'color:blue;' : ''],[(v$.ru_name.$error || v$.ru_inf.$error) && selectedLanguage != 3 ? 'color:red;' : '']" style="margin-left:10px;cursor:pointer">RU</span>
                    </div>
                </div>
                <div class="card-body">
                    <form @submit.prevent="makeProduct">
                        <div v-if="previewUrl" class="row mb-3">
                            <label class="col-sm-2 mt-auto mb-auto col-form-label" for="basic-default-name">Preview</label>
                            <div class="col-sm-10">
                                <div class="position-relative d-flex justify-content-center" style="width:30%;">
                                    <img :src="previewUrl" style="width:80%;">
                                    <a v-if="filePreview.imagescount > filePreview.page + 1" @click.prevent="nextImageUp" href="#" class="position-absolute top-50 end-0 translate-middle-y">
                                            <i class="bx bxs-chevron-right" style="font-size: 2rem;"></i>
                                        </a>
                                    <a v-if="filePreview.page != 0" @click.prevent="previousImageUp" href="#" class="position-absolute top-50 start-0 translate-middle-y">
                                        <i class="bx bxs-chevron-left" style="font-size: 2rem;"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div v-if="updateImages.images.length > 0" class="row d-flex justify-content-center align-items-start mb-4" style="width:50%;height: 10%;">
                            <div class="col-6 position-relative d-flex justify-content-center">
                                <img :src="'http://127.0.0.1:8000/api/images/products/'+updateImages.images[updateImages.page].image" style="width:200px;" alt="Your Image">


                                <a v-if="updateImages.page + 1 < updateImages.images.length && !ultimatePreview && !addingPreview" @click.prevent="nextUpdateImage" href="#" class="position-absolute top-50 end-0 translate-middle-y">
                                    <i class="bx bxs-chevron-right" style="font-size: 2rem;"></i>
                                </a>
                                <a v-if="updateImages.page != 0 && !ultimatePreview && !addingPreview" @click.prevent="previousUpdateImage" href="#" class="position-absolute top-50 start-0 translate-middle-y">
                                    <i class="bx bxs-chevron-left" style="font-size: 2rem;"></i>
                                </a>

                            </div>
                            <div v-if="ultimatePreview" class="col-6 position-relative d-flex justify-content-center">
                                <img :src="ultimatePreview" style="width:200px;">
                            </div>
                            <div v-if="addingPreview" class="col-6 position-relative d-flex justify-content-center">
                                <img :src="addingPreview" style="width:200px;">
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <button v-if="!ultimatePreview && !addingPreview" type="button" @click="[this.$refs.changeImg.click()],[updateImages.id = updateImages.images[updateImages.page].id]" class="btn btn-primary" style="max-width:100px;margin-top:20px;">Change</button>
                                <button v-if="!ultimatePreview && !addingPreview" type="button" @click="[updateImages.id = updateImages.images[updateImages.page].id],[updateImages.delete = true],[changeImage()]" class="btn btn-primary" style="max-width:100px;margin-top:20px;">Delete</button>
                                <button v-if="!ultimatePreview && !addingPreview" type="button" @click="[this.$refs.changeImaga.click()],[updateImages.id = updateImages.images[updateImages.page].id],[updateImages.add = true]" class="btn btn-primary" style="max-width:100px;margin-top:20px;">Add</button>
                                <button v-if="ultimatePreview || addingPreview" type="button"  @click="changeImage" class="btn btn-primary" style="max-width:100px;margin-top:20px;">Save</button>
                                <button v-if="ultimatePreview || addingPreview" type="button"  @click="resetUlti" class="btn btn-primary" style="max-width:100px;margin-top:20px;">Reset</button>
                            </div>
                            <input @change="setUltimateImage"  type="file" style="display:none;" ref="changeImg">
                            <input @change="setAddingImage"  type="file" style="display:none;" ref="changeImaga">
                        </div>
                        <div v-if="updateImages.images.length === 0" class="row mb-3">
                            <label for="formFile" class="col-sm-2 col-form-label" >Product Image <b v-if="v$.images.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                                <input ref="fileInput"  multiple @change="setImage" :class="[v$.images.$error ? 'is-invalid' : '']" class="form-control" type="file" id="formFile">
                            </div>
                        </div>
                        <div v-if="selectedLanguage === 1" class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name-az <b v-if="selected.id && !v$.az_name.$error" style="color:yellow;"> - updating</b><b v-if="v$.az_name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.az_name" type="text" class="form-control" :class="[v$.az_name.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            </div>
                        </div>
                        <div v-if="selectedLanguage === 2" class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name-en <b v-if="selected.id && !v$.en_name.$error" style="color:yellow;"> - updating</b><b v-if="v$.en_name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.en_name" type="text" class="form-control" :class="[v$.en_name.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            </div>
                        </div>
                        <div v-if="selectedLanguage === 3" class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name-ru <b v-if="selected.id && !v$.ru_name.$error" style="color:yellow;"> - updating</b><b v-if="v$.ru_name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.ru_name" type="text" class="form-control" :class="[v$.ru_name.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-company">Merchant <b v-if="selected.id && !v$.merchant_id.$error && !selected.merchant_id" style="color:yellow;"> - updating</b> <b v-if="v$.merchant_id.$error" style="color:rgb(255,62,29)"> - required</b> <b style="cursor:pointer;color:blue;" @click="resetMerchant" v-if="selected.merchant_id">reset</b></label>
                            <div class="col-sm-10">
                            <input v-model='form.merchant' v-debounce:300ms="searchMerchant" type="text" class="form-control" :class="[v$.merchant_id.$error ? 'is-invalid' : '']" id="basic-default-company" placeholder="ACME Inc." />
                            <span v-if="!selected.merchant_id && merchant" @click="chooseMerchant">{{merchant.name}}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-email">Brand <b v-if="selected.id && !v$.brand_id.$error && !selected.brand_id" style="color:yellow;"> - updating</b> <b v-if="v$.brand_id.$error" style="color:rgb(255,62,29)"> - required</b> <b style="cursor:pointer;color:blue;" @click="resetBrand" v-if="selected.brand_id">reset</b></label>
                            <div class="col-sm-10">
                            <input v-debounce:300ms="searchBrand" v-model="form.brand" type="text" class="form-control" :class="[v$.brand_id.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            <span v-if="!selected.brand_id && brand" @click="chooseBrand">{{brand.name}}</span>
                        </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-phone">Category <b v-if="selected.id && !v$.category_id.$error && !selected.category_id" style="color:yellow;"> - updating</b> <b v-if="v$.category_id.$error" style="color:rgb(255,62,29)"> - required</b><b style="cursor:pointer;color:blue;" @click="resetCategory" v-if="selected.category_id">reset</b></label>
                            <div class="col-sm-10">
                                <input v-model="form.category" v-debounce:300ms="searchCategory" type="text" class="form-control" :class="[v$.category_id.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                                <span v-if="!selected.category_id && category" @click="chooseCategory">{{category.name}}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Price  <b v-if="selected.id && !v$.price.$error" style="color:yellow;"> - updating</b><b v-if="v$.price.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.price" type="text" class="form-control" :class="[v$.price.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Quantity  <b v-if="selected.id && !v$.quantity.$error" style="color:yellow;"> - updating</b><b v-if="v$.quantity.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.quantity" type="text" class="form-control" :class="[v$.quantity.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            </div>
                        </div>
                        <div v-if="selectedLanguage === 1 && selected.az_inf.length != 0" class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-phone">İnformation <b v-if="v$.az_inf.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <div v-if="info.az_title" class="row">
                                <div class="col d-flex justify-content-center align-items-center">
                                    <label>{{info.az_title}}</label>
                                </div>
                                <div class="col">
                                    <input v-model="info.az_body" class="form-control" :class="[d$.az_body.$error ? 'is-invalid' : '']" type="text" style="display:inline-block" id="basic-default-name" placeholder="John Doe" />
                                    <span style="color:rgb(255,62,29)" v-if="d$.az_body.$error">{{d$.az_body.$errors[0].$message}}</span>
                                </div>
                                <div class="col">
                                    <button @click="updateInf(1)" type="button" class="btn btn-primary ms-2">Update İnformation</button>
                                    <button @click="resetInfEdit(1)" type="button" class="btn btn-primary ms-2">X</button>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div v-if="selectedLanguage === 1 && selected.az_inf" class="row mb-3" style="display: flex;flex-wrap: wrap;justify-content: flex-start" v-for="inf in selected.az_inf" :key="inf.title">
                            <div class="col-10">
                                <label><b>{{inf.title}}: </b> {{inf.body}}</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" v-model="inf.selected" @change="synchronizeAz"><i v-if="inf.body" @click="editInf(inf.title,inf.body,1)" class="bx bxs-edit" style="cursor:pointer;margin-left:5px;"></i><i v-else @click="editInf(inf.title,inf.body,1)" class="bx bx-plus" style="cursor:pointer;margin-left:5px;"> </i>
                            </div>
                        </div>
                        <div v-if="selectedLanguage === 2 && selected.en_inf.length != 0" class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-phone">İnformation <b v-if="v$.en_inf.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <div v-if="info.en_title" class="row">
                                <div class="col d-flex justify-content-center align-items-center">
                                    <label>{{info.en_title}}</label>
                                </div>
                                <div class="col">
                                    <input v-model="info.en_body" class="form-control" :class="[d$.en_body.$error ? 'is-invalid' : '']" type="text" style="display:inline-block" id="basic-default-name" placeholder="John Doe" />
                                    <span style="color:rgb(255,62,29)" v-if="d$.en_body.$error">{{d$.en_body.$errors[0].$message}}</span>
                                </div>
                                <div class="col">
                                    <button @click="updateInf(2)" type="button" class="btn btn-primary ms-2">Update İnformation</button>
                                    <button @click="resetInfEdit(2)" type="button" class="btn btn-primary ms-2">X</button>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div v-if="selectedLanguage === 2 && selected.en_inf" class="row mb-3" style="display: flex;flex-wrap: wrap;justify-content: flex-start" v-for="inf in selected.en_inf" :key="inf.title">
                            <div class="col-10">
                                <label><b>{{inf.title}}: </b> {{inf.body}}</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" v-model="inf.selected" @change="synchronizeEn"><i v-if="inf.body" @click="editInf(inf.title,inf.body,2)" class="bx bxs-edit" style="cursor:pointer;margin-left:5px;"></i><i v-else @click="editInf(inf.title,inf.body,2)" class="bx bx-plus" style="cursor:pointer;margin-left:5px;"> </i>
                            </div>
                        </div>
                        <div v-if="selectedLanguage === 3 && selected.ru_inf.length != 0" class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-phone">İnformation <b v-if="v$.ru_inf.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <div v-if="info.ru_title" class="row">
                                <div class="col d-flex justify-content-center align-items-center">
                                    <label>{{info.ru_title}}</label>
                                </div>
                                <div class="col">
                                    <input v-model="info.ru_body" class="form-control" :class="[d$.ru_body.$error ? 'is-invalid' : '']" type="text" style="display:inline-block" id="basic-default-name" placeholder="John Doe" />
                                    <span style="color:rgb(255,62,29)" v-if="d$.ru_body.$error">{{d$.ru_body.$errors[0].$message}}</span>
                                </div>
                                <div class="col">
                                    <button @click="updateInf(3)" type="button" class="btn btn-primary ms-2">Update İnformation</button>
                                    <button @click="resetInfEdit(3)" type="button" class="btn btn-primary ms-2">X</button>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div v-if="selectedLanguage === 3 && selected.ru_inf" class="row mb-3" style="display: flex;flex-wrap: wrap;justify-content: flex-start" v-for="inf in selected.ru_inf" :key="inf.title">
                            <div class="col-10">
                                <label><b>{{inf.title}}: </b> {{inf.body}}</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" v-model="inf.selected" @change="synchronizeRu"><i v-if="inf.body" @click="editInf(inf.title,inf.body,3)" class="bx bxs-edit" style="cursor:pointer;margin-left:5px;"></i><i v-else @click="editInf(inf.title,inf.body,3)" class="bx bx-plus" style="cursor:pointer;margin-left:5px;"> </i>
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
                                            <input v-model="page.search" v-debounce:400ms="searchProducts" type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
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
                            <th>Category</th>
                            <th>Merchant</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products" :key="product.id">
                                <td class="dt-checkboxes-cell">
                                    <input type="checkbox" class="dt-checkboxes form-check-input">
                                </td>
                                <td  @click="showProduct(product)" style="cursor:pointer;overflow:hidden;">
                                    <div class="d-flex justify-content-start align-items-center user-name">
                                        <div class="avatar-wrapper">
                                            <div class="avatar me-2">
                                                <span v-if="product.images === 'default.png'" class="avatar-initial rounded-circle bg-label-warning">{{product.name.charAt(0)}}</span>
                                                <img v-if="product.images.length != 0" :src="'http://127.0.0.1:8000/api/images/products/'+product.images[0].image" alt="Avatar" class="rounded-circle">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column" style="overflow:hidden;max-width:100px;">
                                            <span class="emp_name text-truncate">{{product.name}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{product.category.name}}</td>
                                <td>{{product.merchant.name}}</td>
                                <td>{{product.brand.name}}</td>
                                <td v-text="getPrice(product.price)"></td>
                                <td>{{product.quantity}}</td>
                                <td>
                                    <a @click.prevent="deletE(product.id)" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-trash"></i></a>
                                    <a @click.prevent="editE(product)" href="javascript:;" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>

                                </td>
                            </tr>
                        </tbody>
                        </table>
                        <div class="row" style="padding-left:40px;padding-top:20px;">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing {{page.page}} to {{page.perPage}} of {{productCount}} products</div>
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
            <div v-if="previewProduct" class="container fixed-top fixed-bottom d-flex justify-content-center align-items-center">
                <div class="card text-center position-relative shadow-lg" style="width:70%;height:80%">
                    <div class="close position-absolute top-0 end-2 m-3">
                        <span @click="previewMaterial.language = 1" :style="previewMaterial.language === 1 ? 'color:blue;' : ''" style="margin-left:10px;cursor:pointer">AZ</span>
                        <span @click="previewMaterial.language = 2" :style="previewMaterial.language === 2 ? 'color:blue;' : ''" style="margin-left:10px;cursor:pointer">EN</span>
                        <span @click="previewMaterial.language = 3" :style="previewMaterial.language === 3 ? 'color:blue;' : ''" style="margin-left:10px;cursor:pointer">RU</span>
                    </div>
                    <button @click="closePreview" type="button" class="btn btn-primary me-4 close position-absolute top-0 end-0 m-3" aria-label="Close">X</button>
                    <div class="card-body scroll-magic" style="overflow:scroll;">
                        <h3 class="card-title">{{previewMaterial.name}}</h3>
                        <div class="row" style="height: 50%;">
                            <div class="col-6" style="height: 100%;">
                                <div class="row d-flex justify-content-left align-items-start" style="height: 100%;">
                                    <div class="col-12 position-relative" style="width:90%;">
                                        <img :src="'http://127.0.0.1:8000/api/images/products/'+previewMaterial.images[previewMaterial.page].image" style="width:80%;" alt="Your Image" class="img-fluid">

                                        <a v-if="previewMaterial.page + 1 < previewMaterial.imagescount" @click.prevent="nextImage" href="#" class="position-absolute top-50 end-0 translate-middle-y">
                                            <i class="bx bxs-chevron-right" style="font-size: 2rem;"></i>
                                        </a>
                                        <a v-if="previewMaterial.page != 0" @click.prevent="previousImage" href="#" class="position-absolute top-50 start-0 translate-middle-y">
                                            <i class="bx bxs-chevron-left" style="font-size: 2rem;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="d-flex justify-content-left align-items-start">Category: {{previewMaterial.category}}</h5>
                                <h5 class="d-flex justify-content-left align-items-start">Brand: {{previewMaterial.brand}}</h5>
                                <h5 class="d-flex justify-content-left align-items-start">Merchant: {{previewMaterial.merchant}}</h5>
                                <h5 class="d-flex justify-content-left align-items-start" v-text="'Price: ' + getPrice(previewMaterial.price)"></h5>
                                <h5 class="d-flex justify-content-left align-items-start">Quantity: {{previewMaterial.quantity}}</h5>
                            </div>
                        </div>
                        <h4 class="card-title" style="margin-top: 60px;">Information</h4>
                        <div v-if="previewMaterial.inf" v-for="inf in previewMaterial.inf" :key="inf.id" class="row">
                            <div v-if="previewMaterial.language === 1" class="col-6">
                                <b>{{inf.title.translations[0].title}}</b>
                            </div>
                            <div v-if="previewMaterial.language === 1" class="col-6">
                                {{inf.translations[0].body}}
                            </div>
                            <div v-if="previewMaterial.language === 2" class="col-6">
                                <b>{{inf.title.translations[1].title}}</b>
                            </div>
                            <div v-if="previewMaterial.language === 2" class="col-6">
                                {{inf.translations[1].body}}
                            </div>
                            <div v-if="previewMaterial.language === 3" class="col-6">
                                <b>{{inf.title.translations[2].title}}</b>
                            </div>
                            <div v-if="previewMaterial.language === 3" class="col-6">
                                {{inf.translations[2].body}}
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
const merchant = ref('')
const brand = ref('')
const category = ref('')
const loopNumber = ref(0)
const previewUrl = ref('')
const previewProduct = ref(false)
const productSending = ref(false)
const fileInput = ref(null)
const changeImg = ref(null)
const changeImaga = ref(null)
const ultimatePreview = ref(false)
const addingPreview = ref(false)
const incomplete = ref(false)
const products = ref([])
const infoCount = ref('')
const pagesCount = ref('')
const productCount = ref('')
const selectedLanguage = ref(1)
const noImage = ref(false)
const page = reactive({
    page:1,
    search:'',
    perPage:'10',
    order:false,
    orderBy:true
})
watch(()=>page.perPage,()=>{
    page.page = 1
    loadProducts()
})
const previewMaterial = reactive({
    name:'',
    merchant:'',
    brand:'',
    category:'',
    price:'',
    quantity:'',
    inf:false,
    images:null,
    imagescount:null,
    page:0,
    language:1
})
const updateImages = reactive({
    page:0,
    images:[],
    imagescount:null,
    image:null,
    id:null,
    delete:false,
    add:false
})
const filePreview = reactive({
    page:0  ,
    imagescount:0,
    images:null
})
const form = reactive({
    merchant:'',
    brand:'',
    category:''
})
const info = reactive({
    az_title:'',
    az_body:'',
    en_title:'',
    en_body:'',
    ru_title:'',
    ru_body:'',
})
const selected = reactive({
    images:null,
    az_name:'',
    en_name:'',
    ru_name:'',
    id:'',
    merchant_id:'',
    brand_id:'',
    category_id:'',
    price:'',
    quantity:'',
    az_inf:[],
    en_inf:[],
    ru_inf:[]
})

const rules = {
    az_name:{required},
    en_name:{required},
    ru_name:{required},
    merchant_id:{required},
    brand_id:{required},
    category_id:{required},
    price:{required},
    quantity:{required},
    az_inf:{required},
    en_inf:{required},
    ru_inf:{required},
    images:{required}
}
const synchronizeAz = () => {
     selected.az_inf.forEach(item => {
         selected.en_inf[selected.en_inf.findIndex(aton=>aton.id === item.id)].selected = item.selected
         selected.ru_inf[selected.ru_inf.findIndex(aton=>aton.id === item.id)].selected = item.selected
     });
}
const synchronizeEn = () => {
    selected.en_inf.forEach(item => {
        selected.az_inf[selected.az_inf.findIndex(aton=>aton.id === item.id)].selected = item.selected
        selected.ru_inf[selected.ru_inf.findIndex(aton=>aton.id === item.id)].selected = item.selected
    });
}
const synchronizeRu = () => {
     selected.ru_inf.forEach(item => {
         selected.az_inf[selected.az_inf.findIndex(aton=>aton.id === item.id)].selected = item.selected
         selected.en_inf[selected.en_inf.findIndex(aton=>aton.id === item.id)].selected = item.selected
     });
}
const infrules = {
    az_title:{required},
    az_body:{required},
    en_title:{required},
    en_body:{required},
    ru_title:{required},
    ru_body:{required}
}
onMounted(async()=>{
    loadProducts()
})
const v$ = useVuelidate(rules,selected)

const d$ = useVuelidate(infrules,info)

const updateInf = (n) => {
    if(n === 1){
        selected.az_inf[selected.az_inf.findIndex(item => item.title === info.az_title)].body = info.az_body
        resetInfEdit(1)
    }
    if(n === 2){
        selected.en_inf[selected.en_inf.findIndex(item => item.title === info.en_title)].body = info.en_body
        resetInfEdit(2)
    }
    if(n === 3){
        selected.ru_inf[selected.ru_inf.findIndex(item => item.title === info.ru_title)].body = info.ru_body
        resetInfEdit(3)
    }
}
const editInf = (title,body,n) => {
    if(n === 1){
        info.az_title = title
        info.az_body = body
    }
    if(n === 2){
        info.en_title = title
        info.en_body = body
    }
    if(n === 3){
        info.ru_title = title
        info.ru_body = body
    }
}
const showProduct = (product) => {
    previewProduct.value = true
    previewMaterial.pr = product
    previewMaterial.brand = product.brand.name
    previewMaterial.merchant = product.merchant.name
    previewMaterial.price = product.price
    previewMaterial.quantity = product.quantity
    previewMaterial.inf = product.infbody
    previewMaterial.images = product.images
    previewMaterial.imagescount = product.images.length
    setPreviewLang()
}
watch(()=>previewMaterial.language,()=>{
    setPreviewLang()
})
const setPreviewLang = () => {
    if(previewMaterial.language === 1){
        previewMaterial.name = previewMaterial.pr.translations[0].name
        previewMaterial.category = previewMaterial.pr.category.translations[0].name
    }else if(previewMaterial.language === 2){
        previewMaterial.name = previewMaterial.pr.translations[1].name
        previewMaterial.category = previewMaterial.pr.category.translations[1].name
    }else if(previewMaterial.language === 3){
        previewMaterial.name = previewMaterial.pr.translations[2].name
        previewMaterial.category = previewMaterial.pr.category.translations[2].name
    }
}
const closePreview = () => {
    previewProduct.value = false
    previewMaterial.pr = ''
    previewMaterial.name = ''
    previewMaterial.category = ''
    previewMaterial.merchant = ''
    previewMaterial.brandname = ''
    previewMaterial.price = ''
    previewMaterial.inf = ''
    previewMaterial.images = null
    previewMaterial.imagescount = null
    previewMaterial.page = 0
}
const nextImage = () => {
    if(previewMaterial.imagescount > previewMaterial.page + 1){
        previewMaterial.page++
    }
}
const previousImage = () => {
    if(previewMaterial.page != 0){
        previewMaterial.page--
    }
}
const nextUpdateImage = () => {
    if(updateImages.images.length > updateImages.page + 1){
        updateImages.page++
    }
}
const setUltimateImage = (event) => {
    updateImages.image = event.target.files[0]
    const reader = new FileReader();
    reader.onload = (e) => {
        ultimatePreview.value = e.target.result;
    };
    reader.readAsDataURL(updateImages.image);
}
const setAddingImage = (event) => {
    updateImages.image = event.target.files[0]
    const reader = new FileReader();
    reader.onload = (e) => {
        addingPreview.value = e.target.result;
    };
    reader.readAsDataURL(updateImages.image);
}
const changeImage = async() => {
    const formData = new FormData()
    formData.append("image",updateImages.image)
    formData.append("id",updateImages.id)
    formData.append("product_id",selected.id)
    formData.append("delete",updateImages.delete)
    formData.append("add",updateImages.add)
    productSending.value = true
    await axios.post('http://127.0.0.1:8000/api/changeimage',formData,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                updateImages.images = response.data.product.images
                resetUlti()
                productSending.value = false
            }).catch(err=>{
                productSending.value = false
            })
}
const resetUlti = () => {
    if(updateImages.page != 0){
        updateImages.page = updateImages.page - 1
    }
    if(changeImg.value){
        changeImg.value.value = ""
    }
    if(changeImaga.value){
        changeImaga.value.value = ""
    }
    ultimatePreview.value = false
    addingPreview.value = false
    updateImages.image = null
    updateImages.id = null
    updateImages.delete = false
    updateImages.add = false
    loadProducts()
}
const previousUpdateImage = () => {
    if(updateImages.page != 0){
        updateImages.page--
    }
}
const nextImageUp = () => {
    if(filePreview.imagescount > filePreview.page + 1){
        filePreview.page++
        setImage({ target: { files: selected.images } });
    }
}
const previousImageUp = () => {
    if(filePreview.page != 0){
        filePreview.page--
        setImage({ target: { files: selected.images } });
    }
}
const reset = () => {
    updateImages.imagescount = 0
    merchant.value = ""
    brand.value = ""
    category.value = ""
    form.merchant = ""
    form.brand = ""
    form.category = ""
    selected.az_name = ""
    selected.en_name = ""
    selected.ru_name = ""
    selected.merchant_id = ""
    selected.brand_id = ""
    selected.category_id = ""
    selected.price = ""
    selected.quantity = ""
    selected.id = ""
    selected.az_inf = []
    selected.en_inf = []
    selected.ru_inf = []
    info.az_title = ""
    info.az_body = ""
    info.en_title = ""
    info.en_body = ""
    info.ru_title = ""
    info.ru_body = ""
    if(fileInput.value){
        fileInput.value.value = ""
    }
    selected.images = null
    filePreview.imagescount = null
    filePreview.page = 0
    previewUrl.value = false
    updateImages.page = 0
    updateImages.images = []
    updateImages.image = null
    updateImages.id = null
    resetUlti()
    v$.value.$reset()
    d$.value.$reset()
}
const resetInfEdit = (n) => {
    if(n === 1){
        info.az_title = ""
        info.az_body = ""
    }
    if(n === 2){
        info.en_title = ""
        info.en_body = ""
    }
    if(n === 3){
        info.ru_title = ""
        info.ru_body = ""
    }
    d$.value.$reset()
}
const setImage = (event) => {
    selected.images = event.target.files
    filePreview.imagescount = event.target.files.length
    const reader = new FileReader();
    reader.onload = (e) => {
        previewUrl.value = e.target.result;
    };
    reader.readAsDataURL(event.target.files[filePreview.page]);
}
const makeProduct = () => {
    v$.value.$validate()
    if(v$.value.$error){
        return
    }
    const countById = (as) => {
        return selected.az_inf.filter(item => item.selected === as).length
    }
    if(selected.az_inf.length === countById(false)){
        alert('At least few information required')
        return
    }
    selected.az_inf.forEach(element => {
        if(element.selected && (element.body === null || element.body === '')){
            alert('Information isnt completed')
            incomplete.value = true
        }else if(element.selected && element.body != null && element.body != ''){
            incomplete.value = false
        }
    })
    if(incomplete.value){
        return
    }
    selected.en_inf.forEach(element => {
        if(element.selected && (element.body === null || element.body === '')){
            alert('Information isnt completed')
            incomplete.value = true
        }else if(element.selected && element.body != null && element.body != ''){
            incomplete.value = false
        }
    })
    if(incomplete.value){
        return
    }
    selected.ru_inf.forEach(element => {
        if(element.selected && (element.body === null || element.body === '')){
            alert('Information isnt completed')
            incomplete.value = true
        }else if(element.selected && element.body != null && element.body != ''){
            incomplete.value = false
        }
    })
    if(incomplete.value){
        return
    }
    productSending.value = true
    const formData = new FormData()
    if(!selected.id){
        for (let i = 0; i < selected.images.length; i++) {
        formData.append('images[]', selected.images[i]);
        }
    }
    formData.append('az_name',selected.az_name)
    formData.append('en_name',selected.en_name)
    formData.append('ru_name',selected.ru_name)
    formData.append('id',selected.id)
    formData.append('category_id',selected.category_id)
    formData.append('merchant_id',selected.merchant_id)
    formData.append('brand_id',selected.brand_id)
    formData.append('price',selected.price)
    formData.append('quantity',selected.quantity)
    const azJson = JSON.stringify(selected.az_inf)
    formData.append('az_inf',azJson)
    const enJson = JSON.stringify(selected.en_inf)
    formData.append('en_inf',enJson)
    const ruJson = JSON.stringify(selected.ru_inf)
    formData.append('ru_inf',ruJson)
    if(window.confirm('Are you sure to make product')){
        store.dispatch('addproduct',formData).then(()=>{
            productSending.value = false
            loadProducts()
        }).catch(err=>{
            productSending.value = false
        })
    }else{
        productSending.value = false
    }
}
const searchMerchant = async() => {
    await axios.post('http://127.0.0.1:8000/api/merchants',form,{
        headers: {
            Authorization: 'Bearer '+localStorage.getItem('TOKEN')
        }
    }).then((response)=>{
        if(form.merchant){
            if(selected.merchant_id === ''){
                merchant.value = response.data.merchant
            }
        }
    })
}
const chooseMerchant = () => {
    if(merchant.value){
        selected.merchant_id = merchant.value.id
        form.merchant = merchant.value.name
        merchant.value = ''
    }
}
const resetMerchant = () => {
    selected.merchant_id = ''
}
const searchBrand = async() => {
    if(form.brand){
            await axios.post('http://127.0.0.1:8000/api/brands',form,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                if(form.brand){
                    brand.value = response.data.brand
                }
            })
        }else{
            brand.value = ''
        }
}
const chooseBrand = () => {
    if(brand.value){
        selected.brand_id = brand.value.id
        form.brand = brand.value.name
        brand.value = ''
    }
}
const resetBrand = () => {
    selected.brand_id = ''
}
const searchCategory = async() => {
    if(form.category){
            await axios.post('http://127.0.0.1:8000/api/categorys',form,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                category.value = response.data.category
            })
        }else{
            category.value = ''
        }
}
const chooseCategory = () => {
    if(category.value){
        if(category.value.informations.length === 0){
            alert('This category doesnt have any information! If you want to choose it then go and add some information')
            return
        }
        selected.category_id = category.value.id
        form.category = category.value.name
        category.value.informations.forEach(item => {
            selected.az_inf.push({id:item.id,title:item.translations[0].title,body:null,selected:true})
            selected.en_inf.push({id:item.id,title:item.translations[1].title,body:null,selected:true})
            selected.ru_inf.push({id:item.id,title:item.translations[2].title,body:null,selected:true})
        });
        category.value = ''
    }
}
const deletE = async(id) => {
    if(window.confirm('Are you sure you want to delete?')){
        await axios.post('http://127.0.0.1:8000/api/deleteproduct/'+id,null,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                loadProducts()
                alert(response.data.message)
            })
    }
}
const editE = (product) => {
    reset()
    selected.id = product.id
    selected.images = 'asdasdadsadad'
    updateImages.images = product.images
    updateImages.imagescount = product.images.length
    selected.az_name = product.translations[0].name
    selected.en_name = product.translations[1].name
    selected.ru_name = product.translations[2].name
    selected.category_id = product.category.id
    form.category = product.category.name
    selected.merchant_id = product.merchant.id
    form.merchant = product.merchant.name
    selected.brand_id = product.brand.id
    form.brand = product.brand.name
    selected.price = product.price
    selected.quantity = product.quantity
    product.category.informations.forEach(item => {
        if(product.infbody.some(atom=>atom.information_id === item.id)){
            selected.az_inf.push({id:item.id,title:item.translations[0].title,body:product.infbody[product.infbody.findIndex(atom=>atom.information_id === item.id)].translations[0].body,selected:true})
            selected.en_inf.push({id:item.id,title:item.translations[1].title,body:product.infbody[product.infbody.findIndex(atom=>atom.information_id === item.id)].translations[1].body,selected:true})
            selected.ru_inf.push({id:item.id,title:item.translations[2].title,body:product.infbody[product.infbody.findIndex(atom=>atom.information_id === item.id)].translations[2].body,selected:true})
        }else{
            selected.az_inf.push({id:item.id,title:item.translations[0].title,body:null,selected:false})
            selected.en_inf.push({id:item.id,title:item.translations[1].title,body:null,selected:false})
            selected.ru_inf.push({id:item.id,title:item.translations[2].title,body:null,selected:false})
        }
    });
    window.scroll(0,0)
    v$.value.$reset()
    d$.value.$reset()
}
const searchProducts = () => {
    page.page = 1
    loadProducts()
}
const order = () => {
    page.order = true
    loadProducts()
    page.orderBy = !page.orderBy
}
const loadProducts = async() => {
    await axios.post('http://127.0.0.1:8000/api/loadproducts',page,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                products.value = response.data.products.data
                pagesCount.value = response.data.last
                productCount.value = response.data.count
            })
}
const resetCategory = () => {
    selected.category_id = ''
}
const changePage = (n) => {
    page.page = n
    loadProducts()
}
const previousPage = () => {
    if(page.page != 1){
        page.page--
        loadProducts()
    }
}
const nextPage = () => {
    if(page.page != pagesCount){
        page.page++
        loadProducts()
    }
}
const getPrice = (pr) => {
    return (pr / 100).toLocaleString('en-US',{style:'currency',currency:'USD'});
}
</script>

<style scoped>
    .modalcard{
        position:fixed;
        width:100%;
        height:100%;
        display:flex;
        align-items:center;
        justify-content:center;
        z-index:9999999999;
        padding:20px;
    }
    .scroll-magic::-webkit-scrollbar {
        width: 8px;
    }

    .scroll-magic::-webkit-scrollbar-track {
        background-color: #f5f5f5;
    }

    .scroll-magic::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 10px;
    }

    .scroll-magic::-webkit-scrollbar-thumb:hover {
        background-color: #999;
    }
</style>
