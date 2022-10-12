<template>
    <div>
        <v-card class="mb-2">
            <v-breadcrumbs :items="breadcrumbs">
                <v-breadcrumbs-item
                    slot="item"
                    slot-scope="{ item }"
                    exact
                    exact-active-class="success--text"
                    :to="item.href">
                    {{ item.text }}
                </v-breadcrumbs-item>
            </v-breadcrumbs>
        </v-card>

        <v-card>
            <!-- tabs -->
            <v-tabs v-model="tabNum" show-arrows>
                <v-tab v-for="tab in tabs" :key="tab.icon">
                    <span>{{ tab.title }}</span>
                </v-tab>
            </v-tabs>
            <v-tabs-items v-model="tabNum">
                <v-tab-item class="pa-6 mt-6">
                    <validation-observer ref="product_observer">
                        <form @submit.prevent="submit">
                            <v-row>
                                <v-col md="6" cols="12">
                                    <validation-provider v-slot="{ errors }" name="商品名稱" rules="required">
                                        <v-text-field dense outlined v-model="product.name" :error-messages="errors" label="商品名稱"></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12"></v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field type="number" dense outlined v-model="product.weight" label="商品重量"></v-text-field>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field dense outlined v-model="product.volume" label="商品體積"></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea dense outlined v-model="product.instro" label="商品描述"></v-textarea>
                                </v-col>
                                <v-col cols="12" class="d-flex justify-end">
                                    <v-btn class="mt-5 mr-4" @click="tabNum++">
                                        下一步
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </form>
                    </validation-observer>
                </v-tab-item>
                <v-tab-item class="pa-6 mt-6">
                    <validation-observer ref="style_observer">
                        <form @submit.prevent="submit">
                            <div v-for="(item, index) in style">
                                <v-row>
                                    <v-col cols="12" class="d-flex">
                                        <v-chip small label class=" mr-auto" color="primary">{{index+1}}</v-chip>
                                        <v-icon color="error" size="26" v-if="index != 0" @click="delete_style(index)">{{ icons.mdiTrashCanOutline }}</v-icon>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <validation-provider v-slot="{ errors }" name="樣式名稱" rules="required">
                                            <v-text-field dense outlined v-model="item.name" :error-messages="errors" label="樣式名稱"></v-text-field>
                                        </validation-provider>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <validation-provider v-slot="{ errors }" name="樣式數量" rules="required">
                                            <v-text-field type="number" dense outlined v-model="item.amount" :error-messages="errors" label="數量"></v-text-field>
                                        </validation-provider>
                                    </v-col>
                                </v-row>
                            </div>
                            <v-row>
                                <v-col cols="12" class="d-flex">
                                    <v-btn color="info" class="mt-5 mr-auto" @click="add_style">
                                        新增樣式
                                    </v-btn>
                                    <v-btn color="primary" class="mt-5 mr-4 " @click="insert">
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
import { mdiTrashCanOutline } from '@mdi/js'

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
            icons: {
                mdiTrashCanOutline
            },
        }
    },
    data() {
        return {
            breadcrumbs: [{
                    text: '庫存管理',
                    href: '',
                },
                {
                    text: '商品設定',
                    href: '/product',
                },
                {
                    text: '新增',
                    href: '/product/insert',
                },
            ],
            tabNum: 0,
            tabs: [
                { title: '基本資料' },
                { title: '樣式設定' },
            ],
            snackbar: false,
            snackbar_text: ``,

            product: {
                name: '',
                instro: '',
                weight: '',
                volume: '',
            },
            style: [{
                name: '',
                amount: '',
            }],
        }
    },
    methods: {
        insert: _.debounce(function() {
            var self = this;
            self.$refs.product_observer.validate().then(success => {
                if (success) {
                    self.$refs.style_observer.validate().then(success => {
                        if (success) {
                            alert(1321)
                            //     axios.post('/api/user/update/user', {
                            //             name: self.user.name,
                            //             phone: self.user.phone,
                            //             email: self.user.email,
                            //         })
                            //         .then(function(response) {
                            //             if (response.data.result == 'success') {
                            //                 self.snackbar = true;
                            //                 self.snackbar_text = '更新成功 !'
                            //                 return false;
                            //             }
                            //         })
                            //         .catch(function(error) {
                            //             if (error.response.data.result == 'validator_error') {
                            //                 if (error.response.data.data.indexOf('email_unique') != -1) {
                            //                     self.$refs.emailProvider.applyResult({
                            //                         errors: ["此帳號信箱已被註冊過了"],
                            //                         valid: false
                            //                     });
                            //                 }
                            //                 return false;
                            //             }

                            //             self.$router.push({ path: '/error-500' })
                            //         });
                        }
                    })
                } else {
                    self.tabNum = 0;
                    return false;
                }
            })

        }, 1000, {
            'leading': true,
            'trailing': false,
        }),
        add_style() {
            var self = this;
            self.style.push({
                name: '',
                amount: '',
            });
        },
        delete_style(key) {
            var self = this;

            self.style.splice(key, 1)

            console.log(self.style)
        }
    },
    created() {
        var self = this;
    }
}

</script>
