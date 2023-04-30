<template>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">Sliders</h4>
            <div>
                <span @click="selectedLanguage = 1" :style="[selectedLanguage === 1 ? 'color:blue;' : '']" style="margin-left:10px;font-size:25px;cursor:pointer">AZ</span>
                <span @click="selectedLanguage = 2" :style="[selectedLanguage === 2 ? 'color:blue;' : '']" style="margin-left:10px;font-size:25px;cursor:pointer">EN</span>
                <span @click="selectedLanguage = 3" :style="[selectedLanguage === 3 ? 'color:blue;' : '']" style="margin-left:10px;font-size:25px;cursor:pointer">RU</span>
            </div>
            <div class="row">
              <!-- Bootstrap carousel -->
                  <div class="col-md">
                    <div v-if="sliders" id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                      <div class="carousel-inner">
                        <div class="carousel-item active">
                          <img class="d-block w-100" :src="'http://127.0.0.1:8000/api/images/sliders/'+sliders[page].image" alt="First slide">
                          <div class="carousel-caption d-none d-md-block">
                               <button @click="this.$refs.file.click()" class="btn btn-primary">change</button>
                               <input @change="setUltiImage" type="file" ref="file" style="display: none;">
                          </div>
                        </div>
                      <a v-if="page != 0" class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span @click="page === 0 ? page = 0 : page--" class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                      </a>
                      <a v-if="page + 1 < sliders.length" class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span @click="page + 1 < sliders.length ? page++ : page = page" class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <h4 v-if="ultiUrl" class="fw-bold py-3 mb-4">Will be changed to:</h4>
              <div v-if="ultiUrl" class="row">
                  <div class="col-md">
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                      <div class="carousel-inner">
                        <div class="carousel-item active">
                          <img class="d-block w-100" :src="ultiUrl" alt="First slide">
                          <div class="carousel-caption d-none d-md-block">
                               <button @click="changeImage" class="btn btn-primary">Save</button>
                               <button @click="reset" class="btn btn-primary">Reset</button>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
          <div class="content-backdrop fade"></div>
        </div>
    </div>
  </template>
<script setup>
import { Navigation, Pagination, Scrollbar, A11y, Autoplay , Grid } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { ref,onMounted,watch } from 'vue';
import store from '../../store';
import axios from 'axios';
import 'swiper/css';
import 'swiper/css/pagination';
const modules = [Navigation, Pagination, Scrollbar, A11y, Autoplay, Grid];
const sliders = ref(false)
const page = ref(0)
const ultiUrl = ref(null)
const ultiImg = ref(null)
const ultiN = ref(null)
const file = ref(null)
const selectedLanguage = ref(1)
onMounted(async() => {
    await axios.get('http://127.0.0.1:8000/api/loadslider').then((response)=>{
        sliders.value = response.data
        setImage()
    })
})
const changeImage = async() => {
    const formData = new FormData()
    formData.append("image",ultiImg.value)
    formData.append('id',sliders.value[ultiN.value].id)
    formData.append('lang',selectedLanguage.value)
    await axios.post('http://127.0.0.1:8000/api/changeslider',formData,{
            headers: {
                Authorization: 'Bearer '+localStorage.getItem('TOKEN')
            }
        }).then((response)=>{
            if(page.value != 0){
                page.value--
            }
            sliders.value = response.data
            reset()
            setImage()
    })
}
watch(()=>selectedLanguage.value,()=>{
    reset()
    if(sliders.value){
        setImage()
    }
    
})
const setImage = () => {
        if (selectedLanguage.value === 1){
            sliders.value.forEach(item => {
                item.image = item.az_image
            })
        }else if (selectedLanguage.value === 2){
            sliders.value.forEach(item => {
                item.image = item.en_image
            })
        }else if (selectedLanguage.value === 3){
            sliders.value.forEach(item => {
                item.image = item.ru_image
            })
        }
    }
const setUltiImage = (event) => {
    ultiImg.value = event.target.files[0]
    const reader = new FileReader();
    reader.onload = (e) => {
        ultiUrl.value = e.target.result;
    };
    reader.readAsDataURL(ultiImg.value);
    ultiN.value = page.value
}
const reset = () => {
    ultiImg.value = null
    ultiUrl.value = null
    ultiN.value = null
    if(file.value){
        file.value.value = ""
    }
}
</script>
<style>
  
</style>
