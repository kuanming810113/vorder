<template>
    <v-card>
        <!-- tabs -->
        <v-tabs v-model="tabNum" show-arrows>
            <v-tab v-for="tab in tabs" :key="tab.icon">
                <span>{{ tab.title }}</span>
            </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tabNum">
            <v-tab-item class="pa-6 mt-6">
                <validation-observer ref="user_observer">
                    <form @submit.prevent="submit">
                        <v-row>
                            <v-col md="6" cols="12">
                                <validation-provider v-slot="{ errors }" name="使用者名稱" rules="required">
                                    <v-text-field dense outlined v-model="user.name" :error-messages="errors" label="使用者名稱"></v-text-field>
                                </validation-provider>
                            </v-col>
                            <v-col md="6" cols="12">
                                <validation-provider  v-slot="{ errors }" name="帳號信箱" rules="required|email">
                                    <v-text-field dense outlined v-model="user.email" :error-messages="errors" label="登入信箱"></v-text-field> 
                                </validation-provider>
                            </v-col>
                            <v-col md="6" cols="12">
                                <validation-provider v-slot="{ errors }" name="手機號碼" rules="required|max:10">
                                    <v-text-field dense outlined v-model="user.phone" :counter="10" :error-messages="errors" label="手機號碼"></v-text-field>
                                </validation-provider>
                            </v-col>
                            <v-col cols="12" class="d-flex justify-end">
                                <v-btn color="primary" class="mt-5 mr-4" @click="user_validationForm">
                                    送出
                                </v-btn>
                            </v-col>
                        </v-row>
                    </form>
                </validation-observer>
            </v-tab-item>
            <v-tab-item class="pa-6 mt-6">
                <validation-observer ref="store_observer">
                    <form @submit.prevent="submit">
                        <v-row>
                            <v-col md="6" cols="12">
                                <validation-provider v-slot="{ errors }" name="店家名稱" rules="required">
                                    <v-text-field dense outlined v-model="store.name" :error-messages="errors" label="店家名稱"></v-text-field>
                                </validation-provider>
                            </v-col>
                            <v-col md="6" cols="12">
                                <v-text-field dense outlined v-model="store.line_url" label="Line 網址"></v-text-field>
                            </v-col>
                            <v-col md="6" cols="12">
                                <v-text-field dense outlined v-model="store.fb_url" label="FB 網址"></v-text-field>
                            </v-col>
                            <v-col md="6" cols="12">
                                <v-text-field dense outlined v-model="store.ig_url" label="IG 網址"></v-text-field> 
                            </v-col>
                            <v-col cols="12">
                                <v-textarea dense outlined v-model="store.intro" label="店家介紹"></v-textarea>
                            </v-col>
                            <v-col cols="12" class="d-flex justify-end">
                                <v-btn color="primary" class="mt-5 mr-4" @click="store_validationForm">
                                    送出
                                </v-btn>
                            </v-col>
                        </v-row>
                    </form>
                </validation-observer>
            </v-tab-item>
            <v-tab-item class="pa-6 mt-6">
                <validation-observer ref="privacy_observer">
                    <form @submit.prevent="submit">
                        <v-row>
                            <v-col md="6" cols="12">
                                <validation-provider v-slot="{ errors }" name="密碼" rules="required">
                                    <v-text-field type="password" dense outlined v-model="privacy.password" :error-messages="errors" label="密碼"></v-text-field>
                                </validation-provider>
                            </v-col>
                            <v-col md="6" cols="12">
                                <validation-provider v-slot="{ errors }" name="再次輸入密碼" rules="required" ref="passwordProvider">
                                    <v-text-field type="password" dense outlined v-model="privacy.re_password" :error-messages="errors" label="再次輸入密碼"></v-text-field>
                                </validation-provider>
                            </v-col>
                            <v-col cols="12" class="d-flex justify-end">
                                <v-btn color="primary" class="mt-5 mr-4" @click="privacy_validationForm">
                                    送出
                                </v-btn>
                            </v-col>
                        </v-row>
                    </form>
                </validation-observer>
            </v-tab-item>
        </v-tabs-items>
    </v-card>
</template>
<script>
import { ValidationProvider, ValidationObserver, localize, extend } from 'vee-validate/dist/vee-validate.full.esm';
import tw from "vee-validate/dist/locale/zh_TW.json";
localize("zh_TW", tw);


export default {
    components: {
        ValidationProvider,
        ValidationObserver,
    },
    setup() {
        return {
            tabNum: 0,
            tabs: [
                { title: '使用者設定'},
                { title: '店家設定'},
                { title: '隱私權設定'},
            ],
            user:{
                name: '',
                phone: '',
                email: '',
            },
            store:{
                name:'',
                intro:'',
                line_url:'',
                fb_url:'',
                ig_url:'',
            },
            privacy:{
                password:'',
                re_password:''
            }
        }
    },
    methods: {
        user_validationForm: _.debounce(function() {
            this.$refs.user_observer.validate().then(success => {
                if (success) {

                }
            })
        }, 1000 ,{
            'leading': true,
            'trailing': false,
        }),
        store_validationForm: _.debounce(function() {

        }, 1000 ,{
            'leading': true,
            'trailing': false,
        }),
        privacy_validationForm: _.debounce(function() {
            this.$refs.privacy_observer.validate().then(success => {
                if (success) {
                    if (this.privacy.password != this.privacy.re_password) {
                        this.$refs.passwordProvider.applyResult({
                            errors: ["兩次密碼不正確"], // array of string errors
                            valid: false, // boolean state
                            failedRules: {} // should be empty since this is a manual error.
                        });
                    }
                }
            })
        }, 1000 ,{
            'leading': true,
            'trailing': false,
        }),
    },
    created() {

    }
}

</script>
