<template>
    salam <h1 @click="logout">Logout</h1>
    <router-view>

    </router-view>
</template>
<script setup>
import { onMounted } from 'vue'
import store from '../store'
import { useRouter } from 'vue-router'
const router = useRouter()
const logout = () => {
        store.dispatch('logout').then(()=>{
            location.reload()
        })
  }
</script>
