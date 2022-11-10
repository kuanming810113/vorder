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
                                    <validation-provider v-slot="{ errors }" name="上架名稱*" rules="required">
                                        <v-text-field v-model="basic.name" dense outlined :error-messages="errors" label="上架名稱*"></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <validation-provider v-slot="{ errors }" name="發布類別*" rules="required">
                                        <v-select type="number" v-model="basic.goods_category_id" dense outlined :error-messages="errors" label="發布類別*" :items="goods_category_all">
                                            <template v-slot:append-outer>
                                                <v-icon color="info" @click="categoryInsertView()" title="新增發布類別">
                                                    {{ icons.mdiPlusCircle }}
                                                </v-icon>
                                            </template>
                                        </v-select>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <validation-provider v-slot="{ errors }" name="售價*" rules="required">
                                        <v-text-field type="number" v-model="basic.price" dense outlined :error-messages="errors" label="售價*"></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-select dense outlined v-model="basic.is_show" label="是否上架" :items="is_show_items" item-text="text" item-value="value"></v-select>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field type="number" dense outlined v-model="basic.sort" label="排序" hint="數字越大排越前面"></v-text-field>
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
                                            <v-row>
                                                <v-col class="text-end mb-3">
                                                    <v-chip color="error" @click="deleteCombo(key)">
                                                        組合
                                                        <v-icon right>{{icons.mdiClose}}</v-icon>
                                                    </v-chip>
                                                </v-col>
                                            </v-row>
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
                                                            <v-row>
                                                                <v-col class="text-end">
                                                                    <v-chip color="error" @click="deleteProduct(key,key1)">
                                                                        商品
                                                                        <v-icon right>{{icons.mdiClose}}</v-icon>
                                                                    </v-chip>
                                                                </v-col>
                                                            </v-row>
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
                                            <v-expansion-panels :readonly="true">
                                                <v-expansion-panel class="mt-5">
                                                    <v-expansion-panel-header disable-icon-rotate @click="showProductDialog(key)" color="#F1E1FF">
                                                        <div>新增商品</div>
                                                        <template v-slot:actions>
                                                            <v-icon color="info">
                                                                {{ icons.mdiPlusCircle }}
                                                            </v-icon>
                                                        </template>
                                                    </v-expansion-panel-header>
                                                </v-expansion-panel>
                                            </v-expansion-panels>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </draggable>
                            </v-expansion-panels>
                            <v-expansion-panels :readonly="true">
                                <v-expansion-panel class="mt-5">
                                    <v-expansion-panel-header disable-icon-rotate @click="comboDialog = true">
                                        <b>新增組合</b>
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
            <v-dialog v-model="comboDialog" persistent max-width="600px">
                <v-card>
                    <v-card-title>
                        <span class="text-h5"><b>新增組合</b></span>
                    </v-card-title>
                    <v-card-text>
                        <validation-observer ref="combo_observer">
                            <v-form>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <validation-provider v-slot="{ errors }" name="發布組合*" rules="required">
                                                <v-select dense outlined v-model="goods_combo" label="發布組合*" :error-messages="errors" :items="goods_combo_all" return-object>
                                                    <template v-slot:append-outer>
                                                        <v-icon color="info" @click="comboInsertView()" title="新增發布組合">
                                                            {{ icons.mdiPlusCircle }}
                                                        </v-icon>
                                                    </template>
                                                </v-select>
                                            </validation-provider>
                                        </v-col>
                                    </v-row>
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
                                <v-btn class="mr-3" @click="comboDialog = false">
                                    關閉
                                </v-btn>
                                <v-btn color="primary" @click="addCombo()">
                                    送出
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-dialog>
        </v-row>
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
                    text: '上架設定',
                    href: '',
                },
                {
                    text: '上架',
                    href: '/goods',
                },
                {
                    text: '新增',
                    href: window.location.pathname + window.location.search,
                },
            ],
            tabNum: 0,
            tabs: [
                { title: '基本設定' },
                { title: '商品設定' },
            ],

            snackbar: false,
            snackbar_text: ``,

            comboDialog: false,
            productDialog: false,

            is_show_items: [
                { text: '是', value: 1 },
                { text: '否', value: 0 },
            ],

            basic: {
                name: '',
                goods_category_id: '',
                price: '',
                is_show: 1,
                sort: '1',
                remark: '',
            },

            product_id: '',
            addProductKey: '',

            goods: [],

            goods_combo: [],

            product_all: [],
            goods_combo_all: [],
            goods_category_all: [],
        }
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
        comboInsertView() {
            var self = this;
            self.$router.push({ path: '/goods_combo/insert' })
        },
        categoryInsertView() {
            var self = this;
            self.$router.push({ path: '/goods_category/insert' })
        },
        productInsertView() {
            var self = this;
            self.$router.push({ path: '/product/insert' })
        },
        addCombo() {
            var self = this;

            self.$refs.combo_observer.validate().then(success => {
                if (success) {
                    for(var key in self.goods){
                        if (self.goods[key].combo_id == self.goods_combo.value) {
                            self.snackbar = true;
                            self.snackbar_text = '已有此組合 !';
                            return false;
                        }
                    }

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
                                    result.product_style.push({ id: data.product_style[k].id, name: data.product_style[k].name, extra_price: 0 });
                                }

                                self.goods.push({
                                    combo_id: self.goods_combo.value,
                                    combo_name: self.goods_combo.text,
                                    product_data: [result]
                                })
                            }
                        })
                        .catch(function(error) {
                            self.$router.push({ path: '/error-500' })
                        });
                    self.comboDialog = false
                }
            })
        },
        deleteCombo(key) {
            var self = this;
            if (self.goods.length <= 1) {
                self.snackbar = true;
                self.snackbar_text = '至少包含一個組合 ! ';
                return false;
            }
            if (confirm('確定要刪除整個組合嗎 ? ')) {
                self.goods.splice(key, 1)
            }

        },

        showProductDialog(key) {
            var self = this;
            self.productDialog = true;
            self.addProductKey = key;
        },
        addProduct() {
            var self = this;

            self.$refs.product_observer.validate().then(success => {
                var product_data = self.goods[self.addProductKey].product_data;
                for(var key in product_data){
                    if (product_data[key].product_id == self.product_id) {
                        self.snackbar = true;
                        self.snackbar_text = '此組合已有此商品 !';
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
                                    result.product_style.push({ id: data.product_style[k].id, name: data.product_style[k].name, extra_price: 0 });
                                }

                                self.goods[self.addProductKey].product_data.push(result);

                            }
                        })
                        .catch(function(error) {
                            self.$router.push({ path: '/error-500' })
                        });

                    self.productDialog = false
                }
            })
        },
        deleteProduct(key, key1) {
            var self = this;
            if (self.goods[key].product_data.length <= 1) {
                self.snackbar = true;
                self.snackbar_text = '至少包含一個商品 ! ';
                return false;
            }
            if (confirm('確定要刪除此商品嗎 ? ')) {
                self.goods[key].product_data.splice(key1, 1)
            }
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

        axios.post('/api/goods_combo/get/all')
            .then(function(response) {
                if (response.data.result == 'success') {
                    var data = response.data.data;

                    for (var key in data) {
                        self.goods_combo_all.push({ text: data[key].name, value: data[key].id })
                    }
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });

    },
}

</script>
