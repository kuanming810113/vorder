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
                                    <validation-provider v-slot="{ errors }" name="管理名稱*" rules="required">
                                        <v-text-field v-model="basic.name" dense outlined :error-messages="errors" label="管理名稱*"></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-select v-model="basic.company_id" dense outlined label="合作廠商" :items="company_all" item-text="name" item-value="id" :clearable='true'>
                                        <template v-slot:append-outer>
                                            <v-icon color="info" @click="companyInsertView()" title="新增合作廠商">
                                                {{ icons.mdiPlusCircle }}
                                            </v-icon>
                                        </template>
                                    </v-select>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-select dense outlined v-model="basic.price_type" label="支出/收入" :items="price_type_items" item-text="text" item-value="value"></v-select>
                                </v-col>

                                
                                <v-col md="6" cols="12">
                                    <validation-provider v-slot="{ errors }" name="金額" rules="min_value:0">
                                        <v-text-field type="number" dense outlined v-model="basic.price" label="金額" :error-messages="errors"></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-select dense outlined v-model="basic.type" label="類型" :items="warehouse_manage_type_items" item-text="text" item-value="value" ></v-select>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-select dense outlined v-model="basic.is_account" label="紀錄到帳務紀錄" :items="is_account_items" item-text="text" item-value="value"></v-select>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-menu ref="menu" v-model="menu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field dense outlined v-model="basic.date" label="日期" readonly v-bind="attrs" v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker v-model="basic.date" :active-picker.sync="activePicker" :max="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)" min="1950-01-01" @change="save"></v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea dense outlined v-model="basic.remark" label="備註"></v-textarea>
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
                            <v-expansion-panels focusable multiple>
                                    <v-expansion-panel v-for="(item1,key1,index1) in product" :key="key1">
                                        <v-expansion-panel-header color="#F1E1FF">
                                            <div class="text-h6">
                                                <b>{{item1.product_name}}</b>
                                            </div>
                                        </v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                            <v-row>
                                                <v-col class="text-end">
                                                    <v-chip color="error" @click="deleteProduct(key1)">
                                                        商品
                                                        <v-icon right>{{icons.mdiClose}}</v-icon>
                                                    </v-chip>
                                                </v-col>
                                            </v-row>
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
                                                            <v-text-field type="number" v-model="item2.change_amount" dense outlined :error-messages="errors" label="更動數量*" hint="正數為增加,負數為減少"></v-text-field>
                                                        </validation-provider>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                            </v-expansion-panels>
                            <v-expansion-panels :readonly="true">
                                <v-expansion-panel class="mt-5">
                                    <v-expansion-panel-header disable-icon-rotate @click="productDialog = true"  color="#F1E1FF">
                                        <b>新增商品</b>
                                        <template v-slot:actions>
                                            <v-icon color="info">
                                                {{ icons.mdiPlusCircle }}
                                            </v-icon>
                                        </template>
                                    </v-expansion-panel-header>
                                </v-expansion-panel>
                            </v-expansion-panels>
                            <v-row>
                                <v-col cols="12" class="d-flex justify-end">
                                    <v-btn color="primary" class="mt-5" @click="insertData">
                                        送出
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </validation-observer>
                </v-tab-item>
            </v-tabs-items>
        </v-card>
        <v-row justify="center">
            <v-dialog v-model="productDialog" persistent max-width="600px">
                <v-card>
                    <v-card-title>
                        <span class="text-h5">新增商品</span>
                    </v-card-title>
                    <v-card-text>
                        <validation-observer ref="product_observer">
                            <v-form>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <validation-provider v-slot="{ errors }" name="商品名稱*" rules="required">
                                                <v-autocomplete v-model="product_id" item-text="name" item-value="id" :items="product_all" :error-messages="errors" label="商品名稱*" dense outlined>
                                                    <template v-slot:append-outer>
                                                        <v-icon color="info" @click="productInsertView()" title="新增商品">
                                                            {{ icons.mdiPlusCircle }}
                                                        </v-icon>
                                                    </template>
                                                </v-autocomplete>
                                            </validation-provider>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-form>
                        </validation-observer>
                    </v-card-text>
                    <v-container>
                        <v-row class="pr-3 pl-3">
                            <v-col cols='12' class="pb-5 d-flex justify-end">
                                <v-btn class="mr-3" @click="productDialog = false">
                                    關閉
                                </v-btn>
                                <v-btn color="primary" @click="addProduct()">
                                    送出
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-dialog>
        </v-row>

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
    watch: {
        menu(val) {
            val && setTimeout(() => (this.activePicker = 'YEAR'))
        },
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
                    text: '新增',
                    href: window.location.pathname + window.location.search,
                },
            ],
            tabNum: 0,
            tabs: [
                { title: '基本設定' },
                { title: '庫存更動' },
            ],

            snackbar: false,
            snackbar_text: ``,

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

            activePicker: null,
            menu: false,

            product_id: '',
            product: [],

            product_all: [],
            company_all: [],
            account_all: [],

        }
    },
    methods: {
        save(date) {
            this.$refs.menu.save(date)
        },
        insertData: _.debounce(function() {
            var self = this;
            self.$refs.basic_observer.validate().then(success => {
                if (success) {
                    self.$refs.style_observer.validate().then(success => {
                        if (success) {
                            if (self.product.length == 0) {
                                self.snackbar = true;
                                self.snackbar_text = '至少包含一個商品 ! ';
                                return false;
                            }
                            self.basic.price = (self.basic.price_type === 1) ? ~self.basic.price+1 : self.basic.price;

                            axios.post('/api/warehouse_manage/insert', {
                                    name:self.basic.name,
                                    account_id:self.basic.account_id,
                                    company_id:self.basic.company_id,
                                    price_type:self.basic.price_type,
                                    type:self.basic.type,
                                    price:self.basic.price,
                                    date:self.basic.date,
                                    is_account:self.basic.is_account,
                                    remark:self.basic.remark,
                                    product: self.product,
                                })
                                .then(function(response) {
                                    if (response.data.result == 'success') {
                                        self.$router.push({ path: '/warehouse_manage' })
                                    }
                                })
                                .catch(function(error) {
                                    self.$router.push({ path: '/error-500' })
                                });
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

        companyInsertView() {
            var self = this;
            self.$router.push({ path: '/company/insert' })
        },
        productInsertView() {
            var self = this;
            self.$router.push({ path: '/product/insert' })
        },
        addProduct() {
            var self = this;
            self.$refs.product_observer.validate().then(success => {
                for(var key in self.product){
                    if (self.product[key].product_id == self.product_id) {
                        self.snackbar = true;
                        self.snackbar_text = '已有此商品 !';
                        return false;
                    }
                }
                if (success) {
                    axios.post('/api/product_style/get/product_id', {
                            product_id: self.product_id
                        })
                        .then(function(response) {
                            if (response.data.result == 'success') {
                                var data = response.data.data;

                                var result = {
                                    product_name: data.product_data.name,
                                    product_id: data.product_data.id,
                                    product_style: []
                                };

                                for (var k in data.product_style) {
                                    result.product_style.push({ 
                                        id: data.product_style[k].id, 
                                        name: data.product_style[k].name, 
                                        amount: data.product_style[k].amount,
                                        change_amount: 0 });
                                }

                                self.product.push(result)

                            }
                        })
                        .catch(function(error) {
                            self.$router.push({ path: '/error-500' })
                        });
                    self.productDialog = false
                }
            })
        },
        deleteProduct(key) {
            var self = this;
            if (self.product.length <= 1) {
                self.snackbar = true;
                self.snackbar_text = '至少包含一個商品 ! ';
                return false;
            }
            if (confirm('確定要刪除整個商品嗎 ? ')) {
                self.product.splice(key, 1)
            }

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
    },
}

</script>
