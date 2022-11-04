<template>
    <div>
        <v-card class="mb-2">
            <v-breadcrumbs :items="breadcrumbs">
                <v-breadcrumbs-item
                    slot="item"
                    slot-scope="{ item }"
                    exact
                    exact-active-class="info--text"
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
                                        <v-text-field dense outlined v-model="product.name" :error-messages="errors" label="商品名稱*" filled readonly></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field type="number" dense outlined v-model="product.weight" label="商品重量"  filled readonly></v-text-field>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field dense outlined v-model="product.volume" label="商品體積"  filled readonly></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea dense outlined v-model="product.intro" label="商品描述"  filled readonly></v-textarea>
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
                    <v-alert
                        dense
                        elevation="2"
                        text
                        type="warning"
                    >數量為 -99 代表 數量無限多</v-alert>
                    <validation-observer ref="style_observer">
                        <form @submit.prevent="submit">
                            <v-card outlined shaped class="pa-4 mb-3" outlined elevation="3" v-for="(item, index) in style" :key="index">
                                <v-row>
                                    <v-col cols="12" class="d-flex">
                                        <v-chip small label class=" mr-auto" color="primary">{{index+1}}</v-chip>
                                        
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <validation-provider v-slot="{ errors }" name="樣式名稱" rules="required">
                                            <v-combobox dense outlined v-model="item.name" :items="style_autocomplete" :error-messages="errors" label="樣式名稱*"  filled readonly></v-combobox>
                                        </validation-provider>
                                    </v-col>
                                    <v-col md="6" cols="12">
                                        <validation-provider v-slot="{ errors }" name="樣式數量" rules="required">
                                            <v-text-field type="number" dense outlined v-model="item.amount" :error-messages="errors" label="數量*"  filled readonly > </v-text-field >
                                        </validation-provider>
                                    </v-col>
                                </v-row>
                            </v-card>
                            <v-row>
                                <v-col cols="12" class="d-flex justify-end">
                                    <v-btn color="primary" class="mt-5 mr-4" @click="updateView()">
                                        前往更新
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
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>

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
                    text: '查看',
                    href: window.location.pathname + window.location.search,
                },
            ],
            overlay: true,

            tabNum: 0,
            tabs: [
                { title: '基本資料' },
                { title: '樣式設定' },
            ],
            snackbar: false,
            snackbar_text: ``,

            product: [],
            style: [],
            style_autocomplete:[]
        }
    },
    methods: {
        updateView() {
            var self = this;
            self.$router.push({ name: 'product-update', params: { id: this.$route.params.id } });
        },
    },
    created() {
        var self = this;
        axios.post('/api/product_style/get/autocomplete')
            .then(function(response) {
                if (response.data.result == 'success') {
                    var data = response.data.data;
                    for(var key in data){
                        self.style_autocomplete.push(data[key].name)
                    }
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });      

        axios.post('/api/product/get/id',{
            id:self.$route.params.id
        })
            .then(function(response) {
                if (response.data.result == 'success') {
                    self.product = response.data.data.product;
                    self.style = response.data.data.product_style;
                    self.overlay = false;
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });   
    },
}

</script>
<style type="text/css">

</style>