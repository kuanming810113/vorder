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
                                        <v-text-field v-model="basic.name" dense outlined :error-messages="errors" label="上架名稱*" filled readonly></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <validation-provider v-slot="{ errors }" name="發布類別*" rules="required">
                                        <v-select type="number" v-model="basic.goods_category_id" dense outlined :error-messages="errors" label="發布類別*" :items="goods_category_all" filled readonly>
                                        </v-select>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <validation-provider v-slot="{ errors }" name="售價*" rules="required">
                                        <v-text-field type="number" v-model="basic.price" dense outlined :error-messages="errors" label="售價*" filled readonly></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-select dense outlined v-model="basic.is_show" label="是否上架" :items="is_show_items" filled readonly></v-select>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field type="number" dense outlined v-model="basic.sort" label="排序" hint="數字越大排越前面
                                    " filled readonly></v-text-field>
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
                            <v-expansion-panels focusable multiple>
                                <v-expansion-panel v-for="(item,key,index) in goods" :key="key">
                                    <v-expansion-panel-header>
                                        <div class="text-h5"><b>{{item.combo_name}}</b></div>
                                    </v-expansion-panel-header>
                                    <v-expansion-panel-content>
                                        <v-expansion-panels focusable>
                                            <v-expansion-panel v-for="(item1,key1,index1) in item.product_data" :key="key1">
                                                <v-expansion-panel-header color="#F1E1FF">
                                                    <div class="text-h6"><b>{{item1.product_name}}</b></div>
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
                                                                    <v-text-field type="number" v-model="item2.extra_price" dense outlined :error-messages="errors" label="加價*" filled readonly></v-text-field>
                                                                </validation-provider>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-expansion-panel-content>
                                            </v-expansion-panel>
                                        </v-expansion-panels>
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

import { mdiPlusCircle, mdiClose, mdiPlus, } from "@mdi/js";


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
                    text: '查看',
                    href: window.location.pathname + window.location.search,
                },
            ],
            tabNum: 0,
            tabs: [
                { title: '基本設定' },
                { title: '商品設定' },
            ],

            overlay:true,

            is_show_items: [
                { text: '是', value: 1 },
                { text: '否', value: 0 },
            ],

            basic: {
                name: '',
                goods_category_id: '',
                price: '',
                is_show: '1',
                sort: '1',
                remark: ''
            },


            goods: [],

            goods_category_all: []
        }
    },
    methods: {
        updateView() {
            var self = this;
            self.$router.push({ name: 'goods-update', params: { id: this.$route.params.id } });
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

        axios.post('/api/goods/get/id', {
                id: self.$route.params.id
            })
            .then(function(response) {
                if (response.data.result == 'success') {
                    var data = response.data.data;
                    self.goods = data.goods;
                    self.basic = data.basic;
                    self.overlay = false;
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });
    },
}

</script>
