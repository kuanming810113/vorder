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
                                    <validation-provider v-slot="{ errors }" name="組合名稱*" rules="required">
                                        <v-text-field dense outlined v-model="goods_combo.name" :error-messages="errors" label="組合名稱*" filled readonly hint="如 : 一般、前菜、主菜、A區、B區 ...."></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col md="6" cols="12">
                                    <v-text-field type="number" dense outlined v-model="goods_combo.sort" label="排序" hint="數字越大排越前面
                                    " filled readonly></v-text-field>
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
                    text: '上架設定',
                    href: '',
                },
                {
                    text: '組合設定',
                    href: '/goods_combo',
                },
                {
                    text: '查看',
                    href: window.location.pathname + window.location.search,
                },
            ],
            tabNum: 0,
            tabs: [
                { title: '基本設定' },
            ],
            goods_combo: {
                name: '',
                sort: '1',
            },
        }
    },
    methods: {
        updateView() {
            var self = this;
            self.$router.push({ name: 'goods_combo-update', params: { id: this.$route.params.id } });
        },
    },
    created() {
        var self = this;
        axios.post('/api/goods_combo/get/id',{
            id:self.$route.params.id
        })
            .then(function(response) {
                if (response.data.result == 'success') {
                    console.log(response.data.data)
                    if (!response.data.data) {
                        self.$router.push({ path: '/error-500' })
                        return false;
                    }

                    self.goods_combo = response.data.data;
                    
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });  
    },
}

</script>
