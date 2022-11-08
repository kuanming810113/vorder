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
                    <validation-observer ref="basic_observer">
                        <form @submit.prevent="submit">
                            <v-row>
                                <v-col md="6" cols="12">
                                    <validation-provider v-slot="{ errors }" name="廠商名稱*" rules="required">
                                        <v-text-field dense outlined v-model="basic.name" :error-messages="errors" label="廠商名稱*"></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field  dense outlined v-model="basic.contact_person" label="聯絡人" ></v-text-field>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field  dense outlined v-model="basic.contact_phone" label="聯絡電話" ></v-text-field>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field  dense outlined v-model="basic.contact_url" label="聯絡網址" ></v-text-field>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field dense outlined v-model="basic.address" label="地址" ></v-text-field>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field  dense outlined v-model="basic.tax_number" label="統一編號" ></v-text-field>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field type="number" dense outlined v-model="basic.sort" label="排序" hint="數字越大排越前面
                                    "></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea  dense outlined v-model="basic.remark" label="備註" ></v-textarea>
                                </v-col>
                                <v-col cols="12" class="d-flex justify-end">
                                    <v-btn color="primary" class="mt-5 mr-4 " @click="insertData">
                                        送出
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </form>
                    </validation-observer>
                </v-tab-item>
            </v-tabs-items>
        </v-card>
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
            breadcrumbs: [{
                    text: '內部管理',
                    href: '',
                },
                {
                    text: '合作廠商',
                    href: '/company',
                },
                {
                    text: '新增',
                    href: window.location.pathname + window.location.search,
                },
            ],
            tabNum: 0,
            tabs: [
                { title: '基本設定' },
            ],
            is_show_items:[
                { text: '是', value: 1},
                { text: '否', value: 0},
            ],
            basic: {
                name: '',
                contact_person:'',
                contact_phone:'',
                contact_url:'',
                address:'',
                tax_number:'',
                sort: '1',
                remark:'',
            },
        }
    },
    methods: {
        insertData: _.debounce(function() {
            var self = this;
            self.$refs.basic_observer.validate().then(success => {
                if (success) {
                    axios.post('/api/company/insert', {
                            name: self.basic.name,
                            contact_person: self.basic.contact_person,
                            contact_phone: self.basic.contact_phone,
                            contact_url: self.basic.contact_url,
                            address: self.basic.address,
                            tax_number: self.basic.tax_number,
                            sort: self.basic.sort,
                            remark: self.basic.remark,
                        })
                        .then(function(response) {
                            if (response.data.result == 'success') {
                                self.$router.push({ path: '/company' })
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
    created() {
        var self = this;

    },
}

</script>
