<template>
    <div v-if="brands" class="space-top-2">
        <div class=" d-flex justify-content-between border-bottom border-color-1 flex-md-nowrap flex-wrap border-sm-bottom-0">
            <h3 class="section-title mb-0 pb-2 font-size-22">{{store.state.user.language.brands}}</h3>
        </div>
        <swiper style="padding-bottom:40px;margin-top:20px;"
        :modules="modules"
        :slides-per-view="5"
        :slidesPerGroup="5"
        :space-between="20"
        :centeredSlides="false"
        :pagination="{ clickable: true }"
        :scrollbar="{ draggable: true }"
        :autoplay="{ 
            delay: 5000,
            stopOnInteraction:false,
            disableOnInteraction:false 
            }"
        >
            <swiper-slide v-if="brands" v-for="br in brands" :key="br.id">
              <router-link :to="{name:'Brand',params:{slug:br.slug}}">
                <div class="product-item__outer h-100 shadow-lg" style="padding:20px 0px">
                    <div class="product-item__inner row no-gutters">
                        <div class="col-12 col-lg-12 d-flex justify-content-center">
                            <a @click.prevent class="max-width-150 d-flex justify-content-center"><img width="50" class="img-fluid" :src="'http://127.0.0.1:8000/api/images/brands/'+br.image" alt="Image Description"></a>
                        </div>
                    </div>
                </div>
              </router-link>
            </swiper-slide>
        ...
        </swiper>
    </div>
  </template>
<script setup>
  import { Navigation, Pagination, Scrollbar, A11y, Autoplay , Grid } from 'swiper';
  import { Swiper, SwiperSlide } from 'swiper/vue';
  import { ref,onMounted,computed } from 'vue';
  import store from '../store';
  import 'swiper/css';
  import 'swiper/css/pagination';
  const modules = [Navigation, Pagination, Scrollbar, A11y, Autoplay, Grid];
  const brands = computed(()=>store.state.brands.carousel)
  onMounted(() => {
    store.dispatch('brandCarousel')
  })
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