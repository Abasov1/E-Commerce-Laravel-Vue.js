<template>
    <aside  id="sidebarContent" class="u-sidebar u-sidebar__lg" aria-labelledby="sidebarNavToggler">
         <div class="u-sidebar__scroller">
             <div class="u-sidebar__container">
                 <div class="js-scrollbar u-header-sidebar__footer-offset pb-3">
                     <!-- Toggle Button -->
                     <div class="d-flex align-items-center pt-4 px-7">
                         <button type="button" class="close ml-auto"
                             aria-controls="sidebarContent"
                             aria-haspopup="true"
                             aria-expanded="false"
                             data-unfold-event="click"
                             data-unfold-hide-on-scroll="false"
                             data-unfold-target="#sidebarContent"
                             data-unfold-type="css-animation"
                             data-unfold-animation-in="fadeInRight"
                             data-unfold-animation-out="fadeOutRight"
                             data-unfold-duration="500">
                             <i class="bi bi-close-remove"></i>
                         </button>
                     </div>
                     <!-- End Toggle Button -->

                     <!-- Content -->
                     <div class="js-scrollbar u-sidebar__body">
                         <div class="u-sidebar__content u-header-sidebar__content">
                            <div :class="[loadik ? 'loadik' : '']"></div>
                             <form v-if="!store.state.user.isLoggedIn" @keydown.enter.prevent="subus" class="js-validate">
                                 <!-- Login -->
                                 <div id="login" data-target-group="idForm">
                                     <!-- Title -->
                                     <header class="text-center mb-7">
                                     <h2 class="h4 mb-0">{{store.state.user.language.auth.login.greeting}}</h2>
                                     <p>{{store.state.user.language.auth.login.title}}</p>
                                     </header>
                                     <!-- End Title -->

                                     <div class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.email}}</label>
                                             <div class="input-group" :style="lValidateEmail ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="lValidateEmail ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="lValidateEmail ? 'color:red;' : ''">@</span>
                                                     </span>
                                                 </div>
                                                 <input v-model="loginForm.email" @focus="loginFocus = true" @blur="loginFocus = false" type="text" class="form-control" :placeholder="store.state.user.language.auth.placeholders.email" :style="lValidateEmail ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="lValidateEmail" style="font-size:11px;color:red;">{{lValidateEmail}}</span>
                                         </div>
                                     </div>

                                     <div class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.password}}</label>
                                             <div class="input-group" :style="lValidatePassword ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="lValidatePassword ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="lValidatePassword ? 'color:red;' : ''" class="bi bi-key"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="loginForm.password" @focus="loginFocus = true" @blur="loginFocus = false" type="password" class="form-control" :placeholder="store.state.user.language.auth.placeholders.password" :style="lValidatePassword ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="lValidatePassword" style="font-size:11px;color:red;">{{lValidatePassword}}</span>
                                         </div>
                                     </div>

                                     <div class="d-flex justify-content-end mb-4">
                                         <a class="js-animation-link small link-muted" href="javascript:;"
                                            data-target="#forgotPassword"
                                            data-link-group="idForm"
                                            data-animation-in="slideInUp">{{store.state.user.language.auth.login.forgot_password}}</a>
                                     </div>

                                     <div class="mb-2">
                                         <button @click.prevent="login" type="button" class="btn btn-block btn-sm btn-primary transition-3d-hover">{{store.state.user.language.auth.login.button}}</button>
                                     </div>

                                     <div class="text-center mb-4">
                                         <span class="small text-muted">{{store.state.user.language.auth.login.bot_text}}</span>
                                         <a class="js-animation-link small text-dark" href="javascript:;"
                                            data-target="#signup"
                                            data-link-group="idForm"
                                            data-animation-in="slideInUp">{{store.state.user.language.auth.login.sign_up}}
                                         </a>
                                     </div>

                                     <div class="text-center">
                                         <span class="u-divider u-divider--xs u-divider--text mb-4">OR</span>
                                     </div>

                                     <!-- Login Buttons -->
                                     <div class="d-flex">
                                         <a class="btn btn-block btn-sm btn-soft-facebook transition-3d-hover mr-1" href="javascript:;">
                                           <span class="fab fa-facebook-square mr-1"></span>
                                           Facebook
                                         </a>
                                         <a class="btn btn-block btn-sm btn-soft-google transition-3d-hover ml-1 mt-0" href="javascript:;">
                                           <span class="fab fa-google mr-1"></span>
                                           Google
                                         </a>
                                     </div>
                                     <!-- End Login Buttons -->
                                 </div>

                                 <!-- Signup -->
                                 <div id="signup" style="display: none; opacity: 0;" data-target-group="idForm">
                                     <!-- Title -->
                                     <header class="text-center mb-7">
                                     <h2 class="h4 mb-0">{{store.state.user.language.auth.register.greeting}}</h2>
                                     <p>{{store.state.user.language.auth.register.title}}</p>
                                     </header>
                                     <!-- End Title -->

                                     <!-- Form Group -->
                                     <div class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.name}}</label>
                                             <div class="input-group" :style="rValidateName ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="rValidateName ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="rValidateName ? 'color:red;' : ''" class="bi bi-person"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="registerForm.name" @focus="regFocus = true" @blur="regFocus = false" type="text" class="form-control" :placeholder="store.state.user.language.auth.placeholders.name" :style="rValidateName ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="rValidateName" style="font-size:11px;color:red;">{{rValidateName}}</span>
                                         </div>
                                     </div>
                                     
                                     <!-- End Input -->

                                    <!-- Form Group -->
                                    <div class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.email}}</label>
                                             <div class="input-group" :style="rValidateEmail ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="rValidateEmail ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="rValidateEmail ? 'color:red;' : ''" >@</span>
                                                     </span>
                                                 </div>
                                                 <input v-model="registerForm.email" @focus="regFocus = true" @blur="regFocus = false" type="text" class="form-control" :placeholder="store.state.user.language.auth.placeholders.email" :style="rValidateEmail ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="rValidateEmail" style="font-size:11px;color:red;">{{rValidateEmail}}</span>
                                         </div>
                                     </div>
                                     
                                     <!-- End Input -->

                                     <!-- Form Group -->
                                     <div class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.password}}</label>
                                             <div class="input-group" :style="rValidatePassword ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="rValidatePassword ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="rValidatePassword ? 'color:red;' : ''" class="bi bi-key"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="registerForm.password" @focus="regFocus = true" @blur="regFocus = false" type="password" class="form-control" :placeholder="store.state.user.language.auth.placeholders.password" :style="rValidatePassword ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="rValidatePassword" style="font-size:11px;color:red;">{{rValidatePassword}}</span>
                                         </div>
                                     </div>
                                     
                                     <!-- End Input -->

                                     <!-- Form Group -->
                                     <div class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.password_confirmation}}</label>
                                             <div class="input-group" :style="rValidatePasswordConfirmation ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="rValidatePasswordConfirmation ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="rValidatePasswordConfirmation ? 'color:red;' : ''" class="bi bi-key"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="registerForm.password_confirmation" @focus="regFocus = true" @blur="regFocus = false" type="password" class="form-control" :placeholder="store.state.user.language.auth.placeholders.password_confirmation" :style="rValidatePasswordConfirmation ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="rValidatePasswordConfirmation" style="font-size:11px;color:red;">{{rValidatePasswordConfirmation}}</span>
                                         </div>
                                     </div>
                                     
                                     <!-- End Input -->

                                     <div class="mb-2">
                                         <button type="button" @click.prevent="register" class="btn btn-block btn-sm btn-primary transition-3d-hover">{{store.state.user.language.auth.register.button}}</button>
                                     </div>

                                     <div class="text-center mb-4">
                                         <span class="small text-muted">{{store.state.user.language.auth.register.bot_text}}</span>
                                         <a class="js-animation-link small text-dark" href="javascript:;"
                                             data-target="#login"
                                             data-link-group="idForm"
                                             data-animation-in="slideInUp">{{store.state.user.language.auth.register.sign_up}}
                                         </a>
                                     </div>

                                     <div class="text-center">
                                         <span class="u-divider u-divider--xs u-divider--text mb-4">OR</span>
                                     </div>

                                     <!-- Login Buttons -->
                                     <div class="d-flex">
                                         <a class="btn btn-block btn-sm btn-soft-facebook transition-3d-hover mr-1" href="javascript:;">
                                             <span class="fab fa-facebook-square mr-1"></span>
                                             Facebook
                                         </a>
                                         <a class="btn btn-block btn-sm btn-soft-google transition-3d-hover ml-1 mt-0" href="javascript:;">
                                             <span class="fab fa-google mr-1"></span>
                                             Google
                                         </a>
                                     </div>
                                     <!-- End Login Buttons -->
                                 </div>
                                 <!-- End Signup -->

                                 <!-- Forgot Password -->
                                 <div id="forgotPassword" style="display: none; opacity: 0;" data-target-group="idForm">
                                     <!-- Title -->
                                     <header class="text-center mb-7">
                                         <h2 class="h4 mb-0">Recover Password.</h2>
                                         <p>Enter your email address and an email with instructions will be sent to you.</p>
                                     </header>
                                     <!-- End Title -->

                                     <!-- Form Group -->
                                     <div class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only" for="recoverEmail">Your email</label>
                                             <div class="input-group">
                                                 <div class="input-group-prepend">
                                                     <span class="input-group-text" id="recoverEmailLabel">
                                                         <span class="bi bi-user"></span>
                                                     </span>
                                                 </div>
                                                 <input type="email" class="form-control" name="email" id="recoverEmail" placeholder="Your email" aria-label="Your email" aria-describedby="recoverEmailLabel" required
                                                 data-msg="Please enter a valid email address."
                                                 data-error-class="u-has-error"
                                                 data-success-class="u-has-success">
                                             </div>
                                         </div>
                                     </div>
                                     <!-- End Form Group -->

                                     <div class="mb-2">
                                         <button type="submit" class="btn btn-block btn-sm btn-primary transition-3d-hover">Recover Password</button>
                                     </div>

                                     <div class="text-center mb-4">
                                         <span class="small text-muted">Remember your password?</span>
                                         <a class="js-animation-link small" href="javascript:;"
                                            data-target="#login"
                                            data-link-group="idForm"
                                            data-animation-in="slideInUp">Login
                                         </a>
                                     </div>
                                 </div>
                                 <!-- End Forgot Password -->
                             </form>
                                <div v-else> 
                                     <header class="text-center mb-7">
                                     <h2 class="h4 mb-0">{{store.state.user.data.name}}</h2>
                                     </header>
                                     <div class="mb-2">
                                         <button @click.prevent="logout" type="button" class="btn btn-block btn-sm btn-primary transition-3d-hover">{{store.state.user.language.auth.logout}}</button>
                                     </div>
                                 </div>
                         </div>
                     </div>
                     <!-- End Content -->
                 </div>
             </div>
         </div>
     </aside>
</template>
<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed,watch } from 'vue'
const loginFocus = ref(false)
const regFocus = ref(false)
const rValidateName = ref(false)
const rValidateEmail = ref(false)
const rValidatePassword = ref(false)
const rValidatePasswordConfirmation = ref(false)
const lValidateEmail = ref(false)
const lValidatePassword = ref(false)
const loadik = ref(false)
const registerForm = reactive({
    name:null,
    email:null,
    password:null,
    password_confirmation:null,
    remember:false
})
watch(()=>registerForm.name,()=>{
    validateName()
})
watch(()=>registerForm.email,()=>{
    validateEmail()
})
watch(()=>registerForm.password,()=>{
    validatePassword()
})
watch(()=>registerForm.password_confirmation,()=>{
    validatePasswordConfirmation()
})
const loginForm = reactive({
    email:null,
    password:null,
    remember:false
})
watch(()=>loginForm.email,()=>{
    validateLEmail()
})
watch(()=>loginForm.password,()=>{
    validateLPassword()
})
const validateLEmail = () => {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(loginForm.email === null || loginForm.email === ''){
        lValidateEmail.value = store.state.user.language.auth.login.validations.e_required
    }else if(!regex.test(loginForm.email)){
        lValidateEmail.value = store.state.user.language.auth.login.validations.e_valid
    }else{
        lValidateEmail.value = false
        return true;
    }
}
const validateLPassword = () => {
    if(loginForm.password === null || loginForm.password === ''){
        lValidatePassword.value = store.state.user.language.auth.login.validations.p_required
    }else{
        lValidatePassword.value = false
        return true;
    }
}
const validateName = () => {
    if(registerForm.name === null || registerForm.name === ''){
        rValidateName.value = store.state.user.language.auth.register.validations.n_required
    }else if(registerForm.name.length < 6){
        rValidateName.value = store.state.user.language.auth.register.validations.n_min
    }else if(registerForm.name.length > 20){
        rValidateName.value = store.state.user.language.auth.register.validations.n_max
    }else{
        rValidateName.value = false
        return true;
    }
}
const validateEmail = () => {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(registerForm.email === null || registerForm.email === ''){
        rValidateEmail.value = store.state.user.language.auth.register.validations.e_required
    }else if(!regex.test(registerForm.email)){
        rValidateEmail.value = store.state.user.language.auth.register.validations.e_valid
    }else{
        rValidateEmail.value = false
        return true;
    }   
}
const validatePassword = () => {
    if(registerForm.password === null || registerForm.password === ''){
        rValidatePassword.value = store.state.user.language.auth.register.validations.p_required
    }else if(registerForm.password.length < 6){
        rValidatePassword.value = store.state.user.language.auth.register.validations.p_min
    }else if(registerForm.password.length > 20){
        rValidatePassword.value = store.state.user.language.auth.register.validations.p_max
    }else{
        rValidatePassword.value = false
        return true;
    }
}
const validatePasswordConfirmation = () => {
    if(registerForm.password_confirmation === null || registerForm.password_confirmation === ''){
        rValidatePasswordConfirmation.value = store.state.user.language.auth.register.validations.vp_required
    }else if(registerForm.password != registerForm.password_confirmation){
        rValidatePasswordConfirmation.value = store.state.user.language.auth.register.validations.vp_wrong
    }else{
        rValidatePasswordConfirmation.value = false
        return true;
    }
}
const register = () => {
    validateName()
    validateEmail()
    validatePassword()
    validatePasswordConfirmation()
    if(validateName() && validateEmail() && validatePassword() && validatePasswordConfirmation()){
        loadik.value = true
        store.dispatch('register',registerForm).then(()=>{
            window.location.reload()
            loadik.value = false
        }).catch(err=>{
        loadik.value = false
        if(err.response.data.nameexists){
            rValidateName.value = store.state.user.language.auth.register.validations.n_exists
        }else if(err.response.data.emailexists){
            rValidateEmail.value = store.state.user.language.auth.register.validations.e_exists
        }else{
            alert('Something went wrong')
        }
    })
    }
}
const login = () => {
    validateLEmail()
    validateLPassword()
    if(validateLEmail() && validateLPassword()){
        loadik.value = true
        store.dispatch('login',loginForm).then(()=>{
            window.location.reload()
            loadik.value = false
        }).catch(err=>{
            if(err.response.data.message){
                lValidateEmail.value = store.state.user.language.auth.login.validations.e_exists
                loadik.value = false    
            }else{
                lValidatePassword.value = store.state.user.language.auth.login.validations.p_wrong
                loadik.value = false
            }
        })
    }
}
const a = () => {
   return false
}
const logout = () =>{
    loadik.value = true
    store.dispatch('logout')
}
const subus = () => {
    if(loginFocus.value){
        login()
    }else if(regFocus.value){
        register()
    }
}
</script>
<style scoped>
.loadik{
    position:absolute;
    width:100%;
    height:100%;
    background-color:white;
    opacity:0.5;
    z-index:90;
}
</style>