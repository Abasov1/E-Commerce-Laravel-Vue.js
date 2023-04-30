<template>
        <swiper style="padding-bottom:40px;margin-top:20px;"
        :modules="modules"
        :slides-per-view="1"
        :space-between="100"
        :centeredSlides="false"
        :pagination="{ clickable: true }"
        :scrollbar="{ draggable: true }"
        :autoplay="{
            delay: 5000,
            stopOnInteraction:false,
            disableOnInteraction:false
            }"
        >
            <swiper-slide v-if="sliders" v-for="pr in sliders" :key="pr.id">
            	<div class="container min-height-420 overflow-hidden d-flex justify-content-start" style="margin-left: 350px;">
                    <img :src="'http://127.0.0.1:8000/api/images/sliders/'+pr.image" width="1000"> 
                </div>
            </swiper-slide>
        ...
        </swiper>
  </template>
<script setup>
  import { Navigation, Pagination, Scrollbar, A11y, Autoplay , Grid } from 'swiper';
  import { Swiper, SwiperSlide } from 'swiper/vue';
  import { ref,onMounted,watch } from 'vue';
  import store from '../store';
  import axios from 'axios';
  import 'swiper/css';
  import 'swiper/css/pagination';
  const modules = [Navigation, Pagination, Scrollbar, A11y, Autoplay, Grid];
  const sliders = ref(false)
  onMounted(async() => {
    	await axios.get('http://127.0.0.1:8000/api/loadslider').then((response)=>{
    		sliders.value = response.data
  			setImage()
    	})
  })   
  watch(()=>store.state.user.language,()=>{
  		setImage()
  })
  const setImage = () => {
  	if(sliders.value.length > 0){
		if (localStorage.getItem('lang') === 'az'){
		        sliders.value.forEach(item => {
		        	item.image = item.az_image
		        })
		    }else if (localStorage.getItem('lang') === 'en'){
		        sliders.value.forEach(item => {
		        	item.image = item.en_image
		        })
		    }else if (localStorage.getItem('lang') === 'ru'){
		        sliders.value.forEach(item => {
		        	item.image = item.ru_image
		        })
		    }
  		}
  	}
		
</script>
<style>
  .swiper-container-wrapper {
    display: flex;
    flex-wrap: wrap;
  }

  .swiper-container {
    flex: 1 1 100%;
    max-width: 100%;
  }

  .swiper-slide {
    flex: 0 0 auto;
    width: calc(100% / 3 - 50px);
    margin-right: 50px;
  }
.swiper-pagination .swiper-pagination-bullet-active {
  background-color: red;
}

/* Change color of navigation arrows */
.swiper-button-next::after,
.swiper-button-prev::after {
  color: red;
}
</style>
