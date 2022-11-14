<template>
    <div>
        <v-card class="mb-2">
            <v-breadcrumbs :items="breadcrumbs">
                <v-breadcrumbs-item slot="item" slot-scope="{ item }" exact exact-active-class="info--text" :to="item.href">
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
                    <validation-observer ref="basic_observer">
                        <v-form @submit.prevent="submit">
                            <v-row>
                                <v-col md="6" cols="12">
                                    <validation-provider v-slot="{ errors }" name="倉管名稱*" rules="required">
                                        <v-text-field v-model="basic.name" dense outlined :error-messages="errors" label="倉管名稱*" filled readonly></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-select v-model="basic.company_id" dense outlined label="合作廠商" :items="company_all" item-text="name" item-value="id" filled readonly>
                                        <template v-slot:append-outer>
                                            <v-icon color="info" @click="companyInsertView()" title="新增合作廠商">
                                                {{ icons.mdiPlusCircle }}
                                            </v-icon>
                                        </template>
                                    </v-select>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-select dense outlined v-model="basic.price_type" label="支出/收入" :items="price_type_items" item-text="text" item-value="value" filled readonly></v-select>
                                </v-col>
                                
                                <v-col md="6" cols="12">
                                    <validation-provider v-slot="{ errors }" name="金額" rules="min_value:0">
                                        <v-text-field type="number" dense outlined v-model="basic.price" label="金額" :error-messages="errors" filled readonly></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-select dense outlined v-model="basic.type" label="類型" :items="warehouse_manage_type_items" item-text="text" item-value="value" filled readonly></v-select>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-select dense outlined v-model="basic.is_account" label="紀錄到帳務紀錄" :items="is_account_items" item-text="text" item-value="value" hint="帳務紀錄有資料才會更新" filled readonly></v-select>
                                </v-col>

                                <v-col md="6" cols="12">
                                    <v-text-field dense outlined v-model="basic.date" label="日期" readonly filled readonly></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea dense outlined v-model="basic.remark" label="備註" filled readonly></v-textarea>
                                </v-col>
                                <v-col cols="12" class="d-flex justify-end">
                                    <v-btn class="mt-5" @click="tabNum++">
                                        下一步
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </validation-observer>
                </v-tab-item>
                <v-tab-item class="pa-6 mt-6">
                    <validation-observer ref="style_observer">
                        <v-form @submit.prevent="submit">
                            <v-expansion-panels focusable multiple v-model="product_panel">
                                    <v-expansion-panel v-for="(item1,key1,index1) in product" :key="key1">
                                        <v-expansion-panel-header color="#F1E1FF">
                                            <div class="text-h6">
                                                <b>{{item1.product_name}}</b>
                                            </div>
                                        </v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                            <v-container>
                                                <v-row v-for="(item2,key2,index2) in item1.product_style" :key="key2">
                                                    <v-col md="4" cols="12">
                                                         <v-text-field type="text" v-model="item2.name" dense outlined label="樣式名稱" filled readonly></v-text-field>
                                                    </v-col>
                                                    <v-col md="4" cols="12">
                                                        <v-text-field type="number" v-model="item2.amount" dense outlined label="原有庫存" filled readonly></v-text-field>
                                                    </v-col>
                                                    <v-col md="4" cols="12">
                                                        <validation-provider v-slot="{ errors }" name="更動數量*" rules="required" >
                                                            <v-text-field type="number" v-model="item2.change_amount" dense outlined :error-messages="errors" label="更動數量*" hint="正數為增加,負數為減少" filled readonly></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                            </v-expansion-panels>
                            <v-row>
                                <v-col cols="12" class="d-flex justify-end">
                                    <v-btn color="primary" class="mt-5 mr-4" @click="updateView()">
                                        前往更新
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </validation-observer>
                </v-tab-item>
            </v-tabs-items>
        </v-card>

        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
    </div>
</template>
<script>
import { ValidationProvider, ValidationObserver, localize, extend } from 'vee-validate/dist/vee-validate.full.esm';
import tw from "vee-validate/dist/locale/zh_TW.json";
localize("zh_TW", tw);

import { mdiPlusCircle, mdiClose, mdiPlus, mdiDragHorizontal, mdiDragHorizontalVariant } from "@mdi/js";


export default {

    components: {
        ValidationProvider,
        ValidationObserver,

    },
    setup() {
        return {
            icons: {
                mdiPlusCircle,
                mdiClose,
                mdiPlus,
                mdiDragHorizontal,
                mdiDragHorizontalVariant
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
                    text: '倉儲管理',
                    href: '/warehouse_manage',
                },
                {
                    text: '查看',
                    href: window.location.pathname + window.location.search,
                },
            ],
            overlay: true,

            tabNum: 0,
            tabs: [
                { title: '基本設定' },
                { title: '庫存更動' },
            ],

            productDialog: false,

            is_account_items: [
                { text: '是', value: 1 },
                { text: '否', value: 0 },
            ],

            basic: {
                name: '',
                account_id: '',
                company_id: '',
                price_type: 1,
                type: 1,
                price: 0,
                date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                remark: '',
                is_account:1,
            },

            price_type_items: [
                { text: '支出', value: 1 },
                { text: '收入', value: 0 },
            ],
            warehouse_manage_type_items: [
                { text: '一般', value: 1 },
                { text: '進貨', value: 2 },
                { text: '退貨', value: 3 },
                { text: '退料', value: 4 },
                { text: '報廢', value: 5 },
            ],

            product_panel:[],

            product_id: '',
            product: [],

            product_all: [],
            company_all: [],
            account_all: [],

        }
    },
    methods: {
        companyInsertView() {
            var self = this;
            self.$router.push({ path: '/company/insert' })
        },
        productInsertView() {
            var self = this;
            self.$router.push({ path: '/product/insert' })
        },
        updateView() {
            var self = this;
            self.$router.push({ name: 'warehouse_manage-update', params: { id: this.$route.params.id } });
        },

    },
    created() {
        var self = this;
        axios.post('/api/company/get/all')
            .then(function(response) {
                if (response.data.result == 'success') {
                    var data = response.data.data;
                    self.company_all = data;
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });
        axios.post('/api/account/get/all')
            .then(function(response) {
                if (response.data.result == 'success') {
                    var data = response.data.data;

                    for (var key in data) {
                        self.account_all.push({ text: data[key].name, value: data[key].id })
                    }
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });
        axios.post('/api/product/get/all')
            .then(function(response) {
                if (response.data.result == 'success') {
                    var data = response.data.data;
                    self.product_all = data;
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });

        axios.post('/api/warehouse_manage/get/id',{
            id:self.$route.params.id
        })
            .then(function(response) {
                if (response.data.result == 'success') {
                    var data = response.data.data;
                    self.basic = data;
                    if (self.basic.price < 0) {
                        self.basic.price = Math.abs(self.basic.price);
                        self.basic.price_type = 1;
                    }else{
                        self.basic.price_type = 0;
                    }
                    self.product = JSON.parse(data.change_info)

                    self.product_panel = [...Array(self.product.length).keys()].map((k, i) => i)

                    self.overlay = false;
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            }); 

    },
}

</script>
