<template>
    <main id="content" role="main">
        <div class="mb-6 bg-gray-7 py-6">
            <div class="container">
                Categories
                <div class=" d-flex justify-content-between border-bottom border-color-1 flex-lg-nowrap flex-wrap border-md-down-top-0 border-md-down-bottom-0 mb-5">
                    <h3 class="section-title mb-0 pb-2 font-size-22">Categories</h3>
                </div>
                <div class="row flex-nowrap flex-md-wrap overflow-auto overflow-md-visble">
                    <div v-if="categories" v-for="cat in categories" :key="cat.id" class="col-md-4 col-lg-3 col-xl-4 col-xl-2gdot4 mb-3 flex-shrink-0 flex-md-shrink-1">
                        <div class="bg-white overflow-hidden shadow-on-hover h-100 d-flex align-items-center">
                            <router-link :to="{name:'Category',params:{slug:cat.slug}}" class="d-block pr-2 pr-wd-6">
                                <div class="media align-items-center">
                                    <div class="pt-2">
                                        <img class="img-fluid transform-rotate-15" :src="'http://127.0.0.1:8000/api/images/categories/'+cat.image" alt="Image Description">
                                    </div>
                                    <div class="ml-3 media-body">
                                        <h6 class="mb-0 text-gray-90">{{cat.name}}</h6>
                                    </div>  
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed } from 'vue'
onMounted(()=>{
    store.dispatch('loadcategories')
})
const categories = computed(()=>store.state.categories)

</script>