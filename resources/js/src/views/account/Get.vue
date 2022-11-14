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
                        <form @submit.prevent="submit">
                            <v-row>
                                <v-col md="6" cols="12">
                                    <v-text-field dense outlined v-model="basic.name" label="帳務名稱*" filled readonly></v-text-field>
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
                                    <v-text-field type="number" dense outlined v-model="basic.price" label="金額" filled readonly></v-text-field>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field dense outlined v-model="basic.date" label="日期" filled readonly></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea dense outlined v-model="basic.remark" label="備註" filled readonly></v-textarea>
                                </v-col>
                                <v-col cols="12" class="d-flex justify-end">
                                    <v-btn color="primary" class="mt-5 mr-4 " @click="updateView">
                                        前往更新
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </form>
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

import { mdiPlusCircle } from "@mdi/js";

export default {
    components: {
        ValidationProvider,
        ValidationObserver,
    },
    setup() {
        return {
            icons: {
                mdiPlusCircle,
            },
        }
    },

    data() {
        return {
            breadcrumbs: [{
                    text: '內部管理',
                    href: '',
                },
                {
                    text: '帳務紀錄',
                    href: '/account',
                },
                {
                    text: '新增',
                    href: window.location.pathname + window.location.search,
                },
            ],
            overlay: true,
            tabNum: 0,
            tabs: [
                { title: '基本設定' },
            ],
            price_type_items: [
                { text: '支出', value: 1 },
                { text: '收入', value: 0 },
            ],
            activePicker: null,
            menu: false,

            basic: {
                name: '',
                company_id: '',
                price: 0,
                date: '',
                remark: '',
                price_type: 1
            },
            company_all: []
        }
    },
    methods: {

        updateView() {
            var self = this;
            self.$router.push({ name: 'account-update', params: { id: this.$route.params.id } });
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

        axios.post('/api/account/get/id', {
                id: self.$route.params.id
            })
            .then(function(response) {
                if (response.data.result == 'success') {
                    self.basic = response.data.data;

                    if (self.basic.price < 0) {
                        self.basic.price = Math.abs(self.basic.price);
                        self.basic.price_type = 1;
                    } else {
                        self.basic.price_type = 0;
                    }

                    self.overlay = false;

                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });

    },
}

</script>
