<template>
    <div>
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
                                    <validation-provider v-slot="{ errors }" name="帳號信箱" rules="required|email" ref="emailProvider">
                                        <v-text-field dense outlined v-model="user.email" :error-messages="errors" label="登入信箱"></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <validation-provider v-slot="{ errors }" name="手機號碼" rules="max:10">
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
        <v-snackbar v-model="snackbar" :multi-line="true">
            {{ snackbar_text }}
            <template v-slot:action="{ attrs }">
                <v-btn color="error" text v-bind="attrs" @click="snackbar = false">
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


export default {
    components: {
        ValidationProvider,
        ValidationObserver,
    },
    data() {
        return {
            tabNum: 0,
            tabs: [
                { title: '使用者設定' },
                { title: '店家設定' },
                { title: '隱私權設定' },
            ],
            snackbar: false,
            snackbar_text: ``,

            user: {
                name: '',
                phone: '',
                email: '',
            },
            store: {
                name: '',
                intro: '',
                line_url: '',
                fb_url: '',
                ig_url: '',
            },
            privacy: {
                password: '',
                re_password: ''
            }
        }
    },
    methods: {
        user_validationForm: _.debounce(function() {
            var self = this;
            self.$refs.user_observer.validate().then(success => {
                if (success) {
                    axios.post('/api/user/update/user', {
                            name: self.user.name,
                            phone: self.user.phone,
                            email: self.user.email,
                        })
                        .then(function(response) {
                            if (response.data.result == 'success') {
                                self.snackbar = true;
                                self.snackbar_text = '更新成功 !'
                                return false;
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
        }, 1000, {
            'leading': true,
            'trailing': false,
        }),

        store_validationForm: _.debounce(function() {
            var self = this;
            self.$refs.store_observer.validate().then(success => {
                if (success) {
                    axios.post('/api/user/update/store', {
                            name: self.store.name,
                            intro: self.store.intro,
                            line_url: self.store.line_url,
                            fb_url: self.store.fb_url,
                            ig_url: self.store.ig_url,
                        })
                        .then(function(response) {
                            if (response.data.result == 'success') {
                                self.snackbar = true;
                                self.snackbar_text = '更新成功 !'
                                return false;
                            }
                        })
                        .catch(function(error) {
                            self.$router.push({ path: '/error-500' })
                        });
                }
            })
        }, 1000, {
            'leading': true,
            'trailing': false,
        }),
        privacy_validationForm: _.debounce(function() {
            var self = this;
            self.$refs.privacy_observer.validate().then(success => {
                if (success) {
                    if (self.privacy.password != self.privacy.re_password) {
                        self.$refs.passwordProvider.applyResult({
                            errors: ["兩次密碼不正確"],
                            valid: false,
                        });
                    }

                    axios.post('/api/user/update/privacy', {
                            password: self.privacy.password
                        })
                        .then(function(response) {
                            if (response.data.result == 'success') {
                                self.snackbar = true;
                                self.snackbar_text = '更新成功 !'
                                return false;
                            }
                        })
                        .catch(function(error) {
                            self.$router.push({ path: '/error-500' })
                        });
                }
            })
        }, 1000, {
            'leading': true,
            'trailing': false,
        }),
    },
    mounted() {
        var self = this;
        axios.post('/api/user/get/id')
            .then(function(response) {
                if (response.data.result == 'success') {
                    self.user.name = response.data.data.user_name;
                    self.user.phone = response.data.data.user_phone;
                    self.user.email = response.data.data.user_email;

                    self.store.name = response.data.data.name;
                    self.store.intro = response.data.data.intro;
                    self.store.line_url = response.data.data.line_url;
                    self.store.fb_url = response.data.data.fb_url;
                    self.store.ig_url = response.data.data.ig_url;
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });
    },
    created() {

    }
}

</script>
