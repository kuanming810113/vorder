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
            <v-col cols="12" class="text-center">
                <v-btn color="info"  @click="showGoodsDialog()">
                    <v-icon >{{ icons.mdiPlus }}</v-icon>上架商品
                </v-btn>
            </v-col>
            <!-- tabs -->
            <v-tabs v-model="tabNum" show-arrows>
                <v-tab v-for="tab in tabs" :key="tab.icon">
                    <span>{{ tab.title }}</span>
                </v-tab>
            </v-tabs>
            <v-tabs-items v-model="tabNum">
                <v-tab-item class="pa-6 mt-6">

                </v-tab-item>
<!--                 <v-tab-item class="pa-6 mt-6">
                    <validation-observer ref="style_observer">
                        <v-form @submit.prevent="submit">
                            <v-expansion-panels focusable multiple>
                                <draggable v-model="goods" style="width: 100%;">
                                    <v-expansion-panel v-for="(item,key,index) in goods" :key="key">
                                        <v-expansion-panel-header>
                                            <div class="text-h5">
                                                <v-icon>
                                                    {{ icons.mdiDragHorizontal }}
                                                </v-icon> 
                                                <b>{{item.combo_name}}</b>
                                            </div>
                                        </v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                            <v-expansion-panels focusable>
                                                <draggable v-model="item.product_data" style="width: 100%;">
                                                    <v-expansion-panel v-for="(item1,key1,index1) in item.product_data" :key="key1">
                                                        <v-expansion-panel-header color="#F1E1FF">
                                                            <div class="text-h6">
                                                                <v-icon>
                                                                    {{ icons.mdiDragHorizontalVariant }}
                                                                </v-icon>
                                                                <b>{{item1.product_name}}</b>
                                                            </div>
                                                        </v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <v-container>
                                                                <v-row v-for="(item2,key2,index2) in item1.product_style" :key="key2">
                                                                    <v-col md="6" cols="6">
                                                                        <validation-provider v-slot="{ errors }" name="樣式名稱*" rules="required">
                                                                            <v-text-field type="text" v-model="item2.name" dense outlined :error-messages="errors" label="樣式名稱*" filled readonly></v-text-field>
                                                                        </validation-provider>
                                                                    </v-col>
                                                                    <v-col md="6" cols="6">
                                                                        <validation-provider v-slot="{ errors }" name="加價*" rules="required">
                                                                            <v-text-field type="number" v-model="item2.extra_price" dense outlined :error-messages="errors" label="加價*"></v-text-field>
                                                                        </validation-provider>
                                                                    </v-col>
                                                                </v-row>
                                                            </v-container>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </draggable>
                                            </v-expansion-panels>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </draggable>
                            </v-expansion-panels>
                        </v-form>
                    </validation-observer>
                </v-tab-item> -->
            </v-tabs-items>

            <div class="pa-6 mt-6">
                <validation-observer ref="basic_observer">
                    <v-form @submit.prevent="submit">
                        <v-row>
                            <v-col md="4" cols="12">
                                <v-text-field v-model="basic.total_price" dense outlined label="總金額" filled readonly></v-text-field>
                            </v-col>
                            <v-col md="4" cols="12">
                                <validation-provider v-slot="{ errors }" name="浮動金額*" rules="required">
                                    <v-text-field type="number" v-model="basic.floating_price" dense outlined :error-messages="errors" label="浮動金額*"></v-text-field>
                                </validation-provider>
                            </v-col>
                            <v-col md="4" cols="12">
                                <v-text-field dense outlined v-model="final_price" label="最終金額" filled readonly></v-text-field>
                            </v-col>
                            <v-col md="6" cols="12">
                                <v-select dense outlined v-model="basic.status" label="狀態" :items="status_items" item-text="text" item-value="value"></v-select>
                            </v-col>
                            <v-col md="6" cols="12">
                                <v-select dense outlined v-model="basic.is_checkout" label="是否結帳" :items="is_checkout_items" item-text="text" item-value="value"></v-select>
                            </v-col>
                            <v-col cols="12">
                                <v-textarea dense outlined v-model="basic.remark" label="備註"></v-textarea>
                            </v-col>
                            <v-col cols="12" class="d-flex justify-end">
                                <v-btn color="primary" class="mt-5 mr-4 " @click="insertData">
                                    送出
                                </v-btn>
                            </v-col>
                        </v-row>

                    </v-form>
                </validation-observer>
            </div>

        </v-card>

        <v-row justify="center">
            <v-dialog v-model="GoodsDialog" persistent max-width="600px">
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
                                            <v-autocomplete v-model="goods_category" item-text="text" item-value="value" :items="goods_category_all" label="類別名稱" dense outlined>
                                                </v-autocomplete>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-form>
                        </validation-observer>
                    </v-card-text>
                    <v-container>
                        <v-row class="pr-3 pl-3">
                            <v-col cols='12' class="pb-5 d-flex justify-end">
                                <v-btn class="mr-3" @click="GoodsDialog = false">
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
import draggable from 'vuedraggable'

export default {

    components: {
        ValidationProvider,
        ValidationObserver,
        draggable,
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
                    text: '訂單',
                    href: '',
                },
                {
                    text: '訂單列表',
                    href: '/order',
                },
                {
                    text: '新增',
                    href: window.location.pathname + window.location.search,
                },
            ],
            tabNum: 0,
            tabs: [
                // { title: '基本設定' },
            ],

            snackbar: false,
            snackbar_text: ``,

            GoodsDialog: false,



            status_items: [
                { text: '下單', value: 2 },
                { text: '完成', value: 1 },
            ],
            is_checkout_items: [
                { text: '是', value: 1 },
                { text: '否', value: 0 },
            ],

            basic: {
                total_price: 0,
                floating_price: 0,
                status: 2,
                is_checkout: 0,
                remark: '',
            },

            goods_category:'',

            goods: [],

            goods_category_all: [],
        }
    },
    computed: {
        final_price() {
            var total_price = (parseInt(this.basic.total_price)) ? parseInt(this.basic.total_price) : 0;
            var floating_price = (parseInt(this.basic.floating_price)) ? parseInt(this.basic.floating_price) : 0;
            return  total_price+ floating_price
        },
    },
    methods: {
        insertData: _.debounce(function() {
            var self = this;
            self.$refs.basic_observer.validate().then(success => {
                if (success) {
                    self.$refs.style_observer.validate().then(success => {
                        if (success) {
                            if (self.goods.length == 0) {
                                self.snackbar = true;
                                self.snackbar_text = '至少包含一個組合 ! ';
                                return false;
                            }

                            axios.post('/api/goods/insert', {
                                    basic: self.basic,
                                    goods: self.goods,
                                })
                                .then(function(response) {
                                    if (response.data.result == 'success') {
                                        self.$router.push({ path: '/goods' })
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
        categoryInsertView() {
            var self = this;
            self.$router.push({ path: '/goods_category/insert' })
        },
        productInsertView() {
            var self = this;
            self.$router.push({ path: '/product/insert' })
        },

        showGoodsDialog() {
            var self = this;
            self.GoodsDialog = true;
        },
    },
    created() {
        var self = this;
        axios.post('/api/goods_category/get/all')
            .then(function(response) {
                if (response.data.result == 'success') {
                    var data = response.data.data;
                    for (var k in data) {
                        self.goods_category_all.push({ text: data[k].name, value: data[k].id });
                    }
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });

    },
}

</script>
