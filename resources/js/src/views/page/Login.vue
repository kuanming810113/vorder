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
                    <p class="text-2xl font-weight-semibold text--primary mb-2">Welcome! 馃憢馃徎</p>
                    <p class="mb-2">璜嬬櫥閷勬偍鐨勫赋鎴朵甫闁嬪鍐掗毆</p>
                </v-card-text>
                <!-- login form -->
                <v-card-text>
                    <validation-observer ref="observer">
                        <v-form>
                            <validation-provider v-slot="{ errors }" name="甯宠櫉淇＄" rules="required" ref="emailProvider">
                                <v-text-field v-model="main.email" label="甯宠櫉淇＄" :error-messages="errors"></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="瀵嗙⒓" rules="required" ref="passwordProvider">
                                <v-text-field v-model="main.password" :type="isPasswordVisible ? 'text' : 'password'" label="瀵嗙⒓" placeholder="路路路路路路路路路路路路" :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline" @click:append="isPasswordVisible = !isPasswordVisible" :error-messages="errors"></v-text-field>
                            </validation-provider>
                            <div class="d-flex align-center justify-space-between flex-wrap">
                                <v-checkbox label="瑷樺緱鎴?" hide-details class="me-3 mt-1" v-model="main.remember_me"> </v-checkbox>
                                <!-- forgot link -->
                                <a href="javascript:void(0)" class="mt-1"> 蹇樿瀵嗙⒓? </a>
                            </div>
                            <v-btn block color="primary" class="mt-6" @click="login"> 鐧诲叆 </v-btn>
                        </v-form>
                    </validation-observer>
                </v-card-text>
                <!-- create new account  -->
                <v-card-text class="d-flex align-center justify-center flex-wrap mt-2">
                    <span class="me-2"> 閭勬矑鏈夊赋铏熷棊? </span>
                    <router-link :to="{ name: 'register' }"> 寤虹珛甯宠櫉 </router-link>
                </v-card-text>
                <!-- divider -->
<!--                 <v-card-text class="d-flex align-center mt-2">
                    <v-divider></v-divider>
                    <span class="mx-5">or</span>
                    <v-divider></v-divider>
                </v-card-text> -->
                <!-- social links -->
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
        <img class="auth-mask-bg" height="173" :src="require(`@/assets/images/misc/mask-${$vuetify.theme.dark ? 'dark' : 'light'}.png`).default" />
        <!-- tree -->
        <v-img class="auth-tree" width="247" height="185" :src="require('@/assets/images/misc/tree.png').default"></v-img>
        <!-- tree  -->
        <v-img class="auth-tree-3" width="377" height="289" :src="require('@/assets/images/misc/tree-3.png').default"></v-img>
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
            main: {
                email: '',
                password: '',
                remember_me: false,
            },
            isPasswordVisible: false,

            icons: {
                mdiEyeOutline,
                mdiEyeOffOutline,
            },
        }
    },
    methods: {
        login: _.debounce(function() {
            var self = this;
            self.$refs.observer.validate().then(success => {
                if (success) {
                    axios.post('/api/user/login', {
                            email: self.main.email,
                            password: self.main.password,
                            remember_me: self.main.remember_me,
                        })
                        .then(function(response) {
                            if (response.data.result == 'success') {
                                self.$router.push({ path: '/order' })
                            }
                        })
                        .catch(function(error) {
                            if (error.response.data.result == 'email_error') {
                                self.$refs.emailProvider.applyResult({
                                    errors: ["甯宠櫉淇＄ 灏氭湭瑷诲唺閬?"],
                                    valid: false,
                                });
                                return false;
                            }
                            if (error.response.data.result == 'password_error') {
                                self.$refs.passwordProvider.applyResult({
                                    errors: ["瀵嗙⒓ 閷"],
                                    valid: false,
                                });
                                return false;
                            }
                            
                            self.$router.push({ path: '/error-500' })
                        });
                }
            })
        }, 1000, {
            'leading': true,
            'trailing': false,
        }),
    },

}

</script>
<style lang="scss">
@import '~@resources/sass/preset/pages/auth.scss';

</style>
