<template>
    <div class="auth-wrapper auth-v1">
        <div class="auth-inner">
            <v-card class="auth-card">
                <!-- logo -->
                <v-card-title class="d-flex align-center justify-center py-7">
                    <router-link to="/" class="d-flex align-center">
                        <v-img :src="require('@/assets/images/logos/logo.svg').default" max-height="30px" max-width="30px" alt="logo" contain class="me-3"></v-img>
                        <h2 class="text-2xl font-weight-semibold">Materio</h2>
                    </router-link>
                </v-card-title>
                <!-- title -->
                <v-card-text>
                    <p class="text-2xl font-weight-semibold text--primary mb-2">冒險從這裡開始 🚀</p>
                    <p class="mb-2">讓您的 App 變得輕鬆有趣！</p>
                </v-card-text>
                <!-- login form -->
                <v-card-text>
                    <validation-observer ref="observer">
                    <v-form>
                        <validation-provider v-slot="{ errors }" name="用戶名稱" rules="required">
                            <v-text-field v-model="main.user_name" label="用戶名稱" :error-messages="errors" class="mb-2"></v-text-field>
                        </validation-provider>
                        <validation-provider v-slot="{ errors }" name="店家名稱" rules="required">
                            <v-text-field v-model="main.store_name"  label="店家名稱" :error-messages="errors" class="mb-2"></v-text-field>
                        </validation-provider>
                        <validation-provider v-slot="{ errors }" name="帳號信箱" rules="required|email" ref="emailProvider">
                            <v-text-field v-model="main.email"  label="帳號信箱" :error-messages="errors" class="mb-2"></v-text-field>
                        </validation-provider>
                        <validation-provider v-slot="{ errors }" name="密碼" rules="required">
                            <v-text-field v-model="main.password"  :type="isPasswordVisible ? 'text' : 'password'" label="密碼" :error-messages="errors" placeholder="············" :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline" @click:append="isPasswordVisible = !isPasswordVisible" class="mb-2"></v-text-field>
                        </validation-provider>
                        <validation-provider v-slot="{ errors }" name="再次輸入密碼" rules="required" ref="passwordProvider">
                            <v-text-field v-model="main.re_password"  :type="isPasswordVisible ? 'text' : 'password'" label="再次輸入密碼" :error-messages="errors" placeholder="············" :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline" @click:append="isPasswordVisible = !isPasswordVisible" class="mb-2"></v-text-field>
                        </validation-provider>
                        <v-checkbox class="mt-1" v-model='is_agree'>
                            <template #label>
                                <div class="d-flex align-center flex-wrap">
                                    <span class="me-2">我同意</span><a href="javascript:void(0)">隱私政策條款</a>
                                </div>
                            </template>
                        </v-checkbox>
                        <v-btn block color="primary" class="mt-6" @click="register"> 註冊 </v-btn>
                    </v-form>
                    </validation-observer>
                </v-card-text>

                <!-- create new account  -->
                <v-card-text class="d-flex align-center justify-center flex-wrap mt-2">
                    <span class="me-2"> 已經有一個帳戶？  </span>
                    <router-link :to="{ name: 'login' }"> 按我登入 </router-link>
                </v-card-text>
                <!-- divider -->
<!--                 <v-card-text class="d-flex align-center mt-2">
                    <v-divider></v-divider>
                    <span class="mx-5">or</span>
                    <v-divider></v-divider>
                </v-card-text> -->
                <!-- social link -->
<!--                 <v-card-actions class="d-flex justify-center">
                    <v-btn v-for="link in socialLink" :key="link.icon" icon class="ms-1">
                        <v-icon :color="$vuetify.theme.dark ? link.colorInDark : link.color">
                            {{ link.icon }}
                        </v-icon>
                    </v-btn>
                </v-card-actions> -->
            </v-card>
        </div>
        <!-- background triangle shape  -->
        <img class="auth-mask-bg" height="190" :src="require(`@/assets/images/misc/mask-${$vuetify.theme.dark ? 'dark' : 'light'}.png`).default" />
        <!-- tree -->
        <v-img class="auth-tree" width="247" height="185" :src="require('@/assets/images/misc/tree.png').default"></v-img>
        <!-- tree  -->
        <v-img class="auth-tree-3" width="377" height="289" :src="require('@/assets/images/misc/tree-3.png').default"></v-img>


        <v-snackbar
          v-model="snackbar"
          :multi-line="true"
        >
          {{ snackbar_text }}

          <template v-slot:action="{ attrs }">
            <v-btn
              color="error"
              text
              v-bind="attrs"
              @click="snackbar = false"
            >
              Close
            </v-btn>
          </template>
        </v-snackbar>

    </div>
</template>


<script>

import { ValidationProvider, ValidationObserver, localize, extend } from 'vee-validate/dist/vee-validate.full.esm';
import tw from "vee-validate/dist/locale/zh_TW.json";
localize("zh_TW", tw);
// eslint-disable-next-line object-curly-newline
import { mdiFacebook, mdiTwitter, mdiGithub, mdiGoogle, mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js'
import { ref } from '@vue/composition-api'

export default {
    components: {
        ValidationProvider,
        ValidationObserver,
    },
    setup() {
        return {
            main:{
                user_name:'',
                store_name:'',
                email:'',
                password:'',
                re_password:'',
            },
            snackbar: false,
            snackbar_text: ``,
            is_agree:false,
            isPasswordVisible:false,
            socialLink:[{
                icon: mdiFacebook,
                color: '#4267b2',
                colorInDark: '#4267b2',
            },
            {
                icon: mdiTwitter,
                color: '#1da1f2',
                colorInDark: '#1da1f2',
            },
            {
                icon: mdiGithub,
                color: '#272727',
                colorInDark: '#fff',
            },
            {
                icon: mdiGoogle,
                color: '#db4437',
                colorInDark: '#db4437',
            }],

            icons: {
                mdiEyeOutline,
                mdiEyeOffOutline,
            },
        }
    },
    methods: {
        register: _.debounce(function() {
            var self = this;
            self.$refs.observer.validate().then(success => {
                if (success) {
                    if (!self.is_agree) {
                        self.snackbar = true;
                        self.snackbar_text = '請同意 隱私權政策 !'
                        return false;
                    }
                    if (self.main.password != this.main.re_password) {
                        self.$refs.passwordProvider.applyResult({
                            errors: ["兩次密碼不正確"],
                            valid: false,
                        });
                    }

                    axios.post('/api/v1/user/register', {
                        user_name:self.main.user_name,
                        store_name:self.main.store_name,
                        email:self.main.email,
                        password:self.main.password,
                    })
                    .then(function(response) {
                        if (response.data.result == 'success') {
                            self.$router.push({ path: '/login' })
                        }

                    })
                    .catch(function(error) {
                        if (error.response.data.result == 'validator_error') {
                            if (error.response.data.data.indexOf('email_unique') != -1) {
                                self.$refs.emailProvider.applyResult({
                                    errors: ["此帳號信箱已被註冊過了"],
                                    valid: false
                                });
                            }
                            return false;
                        }

                        self.$router.push({ path: '/error-500' })
                    });
                }
            })
        }, 1000 ,{
            'leading': true,
            'trailing': false,
        }),
    },


}

</script>
<style lang="scss">
@import '~@resources/sass/preset/pages/auth.scss';

</style>
