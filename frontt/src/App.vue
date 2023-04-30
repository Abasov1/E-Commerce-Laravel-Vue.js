<template>
<router-view v-if="store.state.user.language">

</router-view>
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router'
import store from './store'
import {onMounted} from 'vue'

onMounted(()=>{
    if(localStorage.getItem('lang') != 'az' && localStorage.getItem('lang') != 'en' && localStorage.getItem('lang') != 'ru'){
        localStorage.setItem('lang','az')
    }
    store.dispatch('loadLanguage')
    store.dispatch('loadUser')
})
</script>

<style scoped>

</style>
