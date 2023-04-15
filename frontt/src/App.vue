<template>
<router-view >

</router-view>
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router'
import store from './store'
import {onMounted} from 'vue'

onMounted(()=>{
    if (localStorage.getItem('lang') !== null) {
        store.commit('changeLang',localStorage.getItem('lang'))
    }
    store.dispatch('loadUser')
})
</script>

<style scoped>

</style>
