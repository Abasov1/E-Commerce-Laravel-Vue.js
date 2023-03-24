<template>
        <h1 @click="logout">Logout</h1>
      <router-view></router-view>
  </template>

  <script setup>
  import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
  import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'
  import {useStore} from 'vuex'
  import { computed, onMounted } from 'vue'
  import { useRouter } from 'vue-router'
  const store = useStore()
  const router = useRouter()
  const user = store.state.user.data
  const logout = () => {
        store.dispatch('logout').then(()=>{
            location.reload()
        })
  }
  const navigation = [
    { name: 'Dashboard', to:{name:'Dashboard'}, current: true },
    { name: 'Surveys', to:{name:'Surveys'}, current: false }
  ]
  </script>
