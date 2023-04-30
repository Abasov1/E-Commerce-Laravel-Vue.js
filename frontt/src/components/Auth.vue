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
                             <form v-if="!store.state.user.isLoggedIn || setPassword" @keydown.enter.prevent class="js-validate">
                                 <!-- Login -->
                                 <div id="login" data-target-group="idForm">
                                     <!-- Title -->
                                     <header class="text-center mb-7">
                                     <h2 v-if="!forgotting && !setPassword" class="h4 mb-0">{{store.state.user.language.auth.login.greeting}}</h2>
                                     <h2 v-if="forgotting && !setPassword" class="h4 mb-0">{{store.state.user.language.auth.verification.title}}</h2>
                                     <h2 v-if="setPassword" class="h4 mb-0">{{store.state.user.language.auth.forgot_password.title}}</h2>
                                     <p v-if="!forgotting && !setPassword">{{store.state.user.language.auth.login.title}}</p>
                                     <p v-if="forgotting && !setPassword">{{verPar}}</p>
                                     <p v-if="setPassword">{{store.state.user.language.auth.forgot_password.p}}</p>
                                     </header>
                                     <!-- End Title -->

                                     <div v-if="forgotting && !setPassword" class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.verification.title}}</label>
                                             <div class="input-group" :style="validateForgot ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="validateForgot ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="validateForgot ? 'color:red;' : ''" class="bi bi-key"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="loginForm.v_token" type="password" class="form-control" :placeholder="store.state.user.language.auth.verification.placeholder" :style="validateForgot ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="validateForgot" style="font-size:11px;color:red;">{{validateForgot}}</span>
                                         </div>
                                     </div>
                                     <div v-if="forgotting && !setPassword" class="mb-2">
                                         <button type="button" @click.prevent="iRemamba" class="btn btn-block btn-sm btn-primary transition-3d-hover">{{store.state.user.language.auth.verification.verificate}}</button>
                                     </div>
                                     <div v-if="setPassword" class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.forgot_password.p}}</label>
                                             <div class="input-group" :style="validateSet ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="validateSet ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="validateSet ? 'color:red;' : ''" class="bi bi-key"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="loginForm.new_password" type="password" class="form-control" :placeholder="store.state.user.language.auth.forgot_password.title" :style="validateSet ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="validateSet" style="font-size:11px;color:red;">{{validateSet}}</span>
                                         </div>
                                     </div>
                                     <div v-if="setPassword" class="mb-2">
                                         <button type="button" @click.prevent="iSet" class="btn btn-block btn-sm btn-primary transition-3d-hover">{{store.state.user.language.auth.forgot_password.p}}</button>
                                     </div>

                                     <div v-if="!forgotting && !setPassword" class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.email}}</label>
                                             <div class="input-group" :style="lValidateEmail ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="lValidateEmail ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="lValidateEmail ? 'color:red;' : ''">@</span>
                                                     </span>
                                                 </div>
                                                 <input v-model="loginForm.email" type="text" class="form-control" :placeholder="store.state.user.language.auth.placeholders.email" :style="lValidateEmail ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="lValidateEmail" style="font-size:11px;color:red;">{{lValidateEmail}}</span>
                                         </div>
                                     </div>

                                     <div v-if="!forgotting && !setPassword" class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.password}}</label>
                                             <div class="input-group" :style="lValidatePassword ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="lValidatePassword ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="lValidatePassword ? 'color:red;' : ''" class="bi bi-key"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="loginForm.password" type="password" class="form-control" :placeholder="store.state.user.language.auth.placeholders.password" :style="lValidatePassword ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="lValidatePassword" style="font-size:11px;color:red;">{{lValidatePassword}}</span>
                                         </div>
                                     </div>

                                     <div v-if="!forgotting && wrongPassword && !setPassword" @click="iforgor" class="d-flex justify-content-end mb-4" style="cursor:pointer;">
                                         <a class="js-animation-link small link-muted">{{store.state.user.language.auth.login.forgot_password}}</a>
                                     </div>

                                     <div v-if="!forgotting && !setPassword" class="mb-2">
                                         <button @click.prevent="login" type="button" class="btn btn-block btn-sm btn-primary transition-3d-hover">{{store.state.user.language.auth.login.button}}</button>
                                     </div>

                                     <div v-if="!forgotting && !setPassword" class="text-center mb-4">
                                         <span class="small text-muted">{{store.state.user.language.auth.login.bot_text}}</span>
                                         <a class="js-animation-link small text-dark" href="javascript:;"
                                            data-target="#signup"
                                            data-link-group="idForm"
                                            data-animation-in="slideInUp">{{store.state.user.language.auth.login.sign_up}}
                                         </a>
                                     </div>
                                 </div>

                                 <!-- Signup -->
                                 <div id="signup" style="display: none; opacity: 0;" data-target-group="idForm">
                                     <!-- Title -->
                                     <header class="text-center mb-7">
                                     <h2 class="h4 mb-0">{{store.state.user.language.auth.register.greeting}}</h2>
                                     <p>{{store.state.user.language.auth.register.title}}</p>
                                     </header>
                                     <!-- End Title -->
                                     <!-- Verification Section -->
                                     <!-- End Verification Section -->
                                     <!-- Form Group -->
                                     <div v-if="verifyng" class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">Verification</label>
                                             <div class="input-group" :style="validateToken ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="validateToken ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="validateToken ? 'color:red;' : ''" class="bi bi-key"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="registerForm.v_token" type="password" class="form-control" placeholder="Verification Code" :style="validateToken ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="validateToken" style="font-size:11px;color:red;">{{validateToken}}</span>
                                         </div>
                                     </div>
                                     <div v-show="verifyng" class="mb-2">
                                         <button type="button" @click.prevent="verificate" class="btn btn-block btn-sm btn-primary transition-3d-hover">Verificate</button>
                                     </div>
                                     <div v-if="!verifyng" class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.name}}</label>
                                             <div class="input-group" :style="rValidateName ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="rValidateName ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="rValidateName ? 'color:red;' : ''" class="bi bi-person"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="registerForm.name" type="text" class="form-control" :placeholder="store.state.user.language.auth.placeholders.name" :style="rValidateName ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="rValidateName" style="font-size:11px;color:red;">{{rValidateName}}</span>
                                         </div>
                                     </div>
                                     
                                     <!-- End Input -->

                                    <!-- Form Group -->
                                    <div v-if="!verifyng" class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.email}}</label>
                                             <div class="input-group" :style="rValidateEmail ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="rValidateEmail ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="rValidateEmail ? 'color:red;' : ''" >@</span>
                                                     </span>
                                                 </div>
                                                 <input v-model="registerForm.email" type="text" class="form-control" :placeholder="store.state.user.language.auth.placeholders.email" :style="rValidateEmail ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="rValidateEmail" style="font-size:11px;color:red;">{{rValidateEmail}}</span>
                                         </div>
                                     </div>
                                     
                                     <!-- End Input -->

                                     <!-- Form Group -->
                                     <div v-if="!verifyng" class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.password}}</label>
                                             <div class="input-group" :style="rValidatePassword ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="rValidatePassword ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="rValidatePassword ? 'color:red;' : ''" class="bi bi-key"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="registerForm.password" type="password" class="form-control" :placeholder="store.state.user.language.auth.placeholders.password" :style="rValidatePassword ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="rValidatePassword" style="font-size:11px;color:red;">{{rValidatePassword}}</span>
                                         </div>
                                     </div>
                                     
                                     <!-- End Input -->

                                     <!-- Form Group -->
                                     <div v-if="!verifyng" class="form-group">
                                         <div class="js-form-message js-focus-state">
                                             <label class="sr-only">{{store.state.user.language.auth.labels.password_confirmation}}</label>
                                             <div class="input-group" :style="rValidatePasswordConfirmation ? 'box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' : ''">
                                                 <div class="input-group-prepend">
                                                     <span :style="rValidatePasswordConfirmation ? 'border-color:red;' : ''" class="input-group-text">
                                                         <span :style="rValidatePasswordConfirmation ? 'color:red;' : ''" class="bi bi-key"></span>
                                                     </span>
                                                 </div>
                                                 <input v-model="registerForm.password_confirmation" type="password" class="form-control" :placeholder="store.state.user.language.auth.placeholders.password_confirmation" :style="rValidatePasswordConfirmation ? 'border-color:red;' : ''">
                                                
                                             </div>
                                             <span v-if="rValidatePasswordConfirmation" style="font-size:11px;color:red;">{{rValidatePasswordConfirmation}}</span>
                                         </div>
                                     </div>
                                     
                                     <!-- End Input -->

                                     <div v-if="!verifyng" class="mb-2">
                                         <button type="button" @click.prevent="register" class="btn btn-block btn-sm btn-primary transition-3d-hover">{{store.state.user.language.auth.register.button}}</button>
                                     </div>

                                     <div v-if="!verifyng" class="text-center mb-4">
                                         <span class="small text-muted">{{store.state.user.language.auth.register.bot_text}}</span>
                                         <a class="js-animation-link small text-dark" href="javascript:;"
                                             data-target="#login"
                                             data-link-group="idForm"
                                             data-animation-in="slideInUp">{{store.state.user.language.auth.register.sign_up}}
                                         </a>
                                     </div>

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
                                <div v-if="store.state.user.isLoggedIn && !setPassword"> 
                                     <header class="text-center mb-7">
                                     <h2 class="h4 mb-0">{{store.state.user.data.name}}</h2>
                                     <p v-if="store.state.user.data.role != 'NULL'">{{store.state.user.data.role}}</p>
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
import axios from 'axios'
import { onMounted,ref,reactive,computed,watch } from 'vue'
const loginFocus = ref(false)
const regFocus = ref(false)
const verPar = ref(false)
const rValidateName = ref(false)
const rValidateEmail = ref(false)
const rValidatePassword = ref(false)
const rValidatePasswordConfirmation = ref(false)
const lValidateEmail = ref(false)
const lValidatePassword = ref(false)
const verificationCode = ref('')
const validateToken = ref(false)
const forgotCode = ref('')
const validateForgot = ref(false)
const setPassword = ref(false)
const validateSet = ref(false)
const time = ref(5)
const loadik = ref(false)
const verifyng = ref(false)
const forgotting = ref(false)
const resetting = ref(false)
const wrongPassword = ref(false)
const registerForm = reactive({
    name:null,
    email:null,
    password:null,
    password_confirmation:null,
    v_token:null,
    remember:false
})
watch(()=>registerForm.name,()=>{
    if(!resetting.value){
        validateName()
    }
})
watch(()=>registerForm.email,()=>{
    if(!resetting.value){
        validateEmail()
    }
})
watch(()=>registerForm.password,()=>{
    if(!resetting.value){
        validatePassword()
    }
   
})
watch(()=>registerForm.password_confirmation,()=>{
    if(!resetting.value){
        validatePasswordConfirmation()
    }
})
watch(()=>store.state.expired,()=>{
    if(store.state.expired){
        resetReg()
    }
})
watch(()=>store.state.user.language,()=>{
    verPar.value = store.state.user.language.auth.verification.p.replace('{x}',loginForm.email)
    validateForgot.value = false
    validateToken.value = false
})
const loginForm = reactive({
    email:null,
    password:null,
    v_token:'',
    new_password:'',
    remember:false
})
watch(()=>loginForm.email,()=>{
    validateLEmail()
    wrongPassword.value = false
})
watch(()=>loginForm.password,()=>{
    validateLPassword()
})
onMounted(()=>{
    if(store.state.loading){
        loadik.value = true
    }
})
watch(()=>store.state.loading,()=>{
    if(!store.state.loading){
        loadik.value = false
    }
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
watch(()=>loginForm.new_password,()=>{
        validateNew()
})
const validateNew = () => {
    if(loginForm.new_password === null || loginForm.new_password === ''){
        validateSet.value = store.state.user.language.auth.register.validations.p_required
        return false
    }else if(loginForm.new_password.length < 6){
        validateSet.value = store.state.user.language.auth.register.validations.p_min
        return false
    }else if(loginForm.new_password.length > 20){
        validateSet.value = store.state.user.language.auth.register.validations.p_max
        return false
    }else{
        validateSet.value = false
        return true;
    }
}
const register = async() => {
    validateName()
    validateEmail()
    validatePassword()
    validatePasswordConfirmation()
    if(validateName() && validateEmail() && validatePassword() && validatePasswordConfirmation()){
        loadik.value = true

        await axios.post('http://127.0.0.1:8000/api/sendverification',registerForm).then(()=>{
            verifyng.value = true
            loadik.value = false
        }).catch((error)=>{
            loadik.value = false
            if(error.response.data.message === 1){
                rValidateEmail.value = store.state.user.language.auth.register.validations.e_exists
            }
        })
    }
}
const iforgor = async() => {
    loadik.value = true
    await axios.post('http://127.0.0.1:8000/api/forgotpassword',loginForm).then(()=>{
            loadik.value = false
            forgotting.value = true
            verPar.value = store.state.user.language.auth.verification.p.replace('{x}',loginForm.email)            
        }).catch((error)=>{
            alert(error.response.data.message)
            loadik.value = false
        })
}
const iRemamba = () => {
    loadik.value = true
    store.dispatch('verificate',loginForm).then(()=>{
            loadik.value = false
            setPassword.value = true

        }).catch(err=>{
            loadik.value = false
            if(err.response.data.message === 1){
                validateForgot.value = store.state.user.language.auth.verification.wrong
            }else if(err.response.data.message === 2){
                validateForgot.value = store.state.user.language.auth.verification.expired.replace('{x}',time.value)
                countDown()
            }
        })
}
const iSet = () => {
    validateNew()
    if(validateNew()){
    loadik.value = true
    store.dispatch('setNew',loginForm).then(()=>{
            loadik.value = false
        }).catch(err=>{
            alert(err.response.data.message)
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
                wrongPassword.value = true
                loadik.value = false
            }
        })
    }
}
const verificate = () => {
    loadik.value = true
    store.dispatch('verificate',registerForm).then(()=>{
            loadik.value = false
            window.location.reload()
        }).catch(err=>{
            loadik.value = false
            if(err.response.data.message === 1){
                validateToken.value = store.state.user.language.auth.verification.wrong
            }else if(err.response.data.message === 2){
                validateToken.value = store.state.user.language.auth.verification.expired.replace('{x}',time.value)
                countDown()
            }
        })
}
const countDown = () => {
        setTimeout(()=>{
            time.value--
            if(validateToken.value){
                validateToken.value = store.state.user.language.auth.verification.expired.replace('{x}',time.value)
            }
            if(validateForgot.value){
                validateForgot.value = store.state.user.language.auth.verification.expired.replace('{x}',time.value)
            }
            if(time.value === 0){
                validateToken.value = false
                registerForm.v_token = null
                verifyng.value = false
                validateForgot.value = false
                loginForm.v_token = null
                forgotting.value = false
                time.value = 5
            }else if(time.value > 0){
                countDown()
            }
        },1000)
}
const a = () => {
   return false
}
const logout = () =>{
    loadik.value = true
    store.dispatch('logout')
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