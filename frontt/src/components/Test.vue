<template>
  <div v-show="verified">
      <span @click="selectedLanguage = 'az'" :style="[selectedLanguage === 'az' ? 'color:blue;' : '']" style="margin-left:10px;font-size:25px;cursor:pointer">AZ</span>
      <span @click="selectedLanguage = 'en'" :style="[selectedLanguage === 'en' ? 'color:blue;' : '']" style="margin-left:10px;font-size:25px;cursor:pointer">EN</span>
      <span @click="selectedLanguage = 'ru'" :style="[selectedLanguage === 'ru' ? 'color:blue;' : '']" style="margin-left:10px;font-size:25px;cursor:pointer">RU</span>
  </div>
  <div v-show="verified">
    <Vue3JsonEditor
      v-model="state.json"
      :show-btns="true"
      :expandedOnStart="true"
      @json-change="setValue"
    />
  </div>
  <h1 v-if="!verified">Loading..</h1>
</template>

<script setup>
import { reactive, toRefs, ref, onMounted, watch } from 'vue'
import { Vue3JsonEditor } from 'vue3-json-editor'
import store from '../store'
import router from '../router'
import axios from 'axios'
const selectedLanguage = ref(localStorage.getItem('lang'))
const az = ref(false)
const verified = ref(false)
    onMounted(async()=>{
      $('.json-save-btn').on('click',()=>{
        save()
      })
    })
    watch(()=>store.state.loading,()=>{
      if(store.state.user.isLoggedIn){
        if(store.state.user.moderator){
          verified.value = true
        }else{
          router.push({name:'Home'})
        }
      }else{
        router.push({name:'Home'})
      }
      
    })
    watch(()=>selectedLanguage.value,()=>{
      changeIt()
    })
    watch(()=>store.state.user.language,()=>{
      state.json = store.state.user.language
    })
    const changeIt = () => {
       store.commit('changeLang',selectedLanguage.value)
    }
    const setValue = (value) => {
      state.json = value
    }
    const state = reactive({
      json: {}
    })
    const save = async() => {
      await axios.post('http://127.0.0.1:8000/api/changelang/'+selectedLanguage.value,state,{
        headers:{
          Authorization: 'Bearer '+localStorage.getItem('TOKEN')
        }
      }).then((response)=>{
        alert('success')
      }).catch((error)=>{
        alert(error.response.data.message)
      })
    }
</script>