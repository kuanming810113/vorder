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
            <v-card-title>
                <v-btn class="primary mr-2" to="product/insert">新增</v-btn>
                <v-badge :value="searchPlusActive" :content="'!'" color="error" overlap>
                    <v-btn class="primary mr-2" @click="seachDialog=true">進階搜尋</v-btn>
                </v-badge>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="搜尋" single-line hide-details @keyup='getData()'></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="list" hide-default-footer :page.sync="page" :items-per-page="perPage" :options.sync="options" :loading="loading" class="elevation-1" mobile-breakpoint="0">
                <template v-slot:item.product_style="{ item }">
                    <span v-for="(item, index) in item.product_style">
                        <v-badge class="mr-7 mb-1" :color="getColor(item.amount)" :content="(item.amount == -99) ? '無限' : String(item.amount)" overlap>
                            <v-chip label>{{ item.name }}</v-chip>
                        </v-badge>
                    </span>
                </template>
                <template v-slot:item.name="{ item }">
                    <a href="javascript:void(0)" @click="getView(item.id)">{{item.name}}</a>
                </template>
                <template v-slot:item.tool="{ item }">
                    <v-btn small rounded @click="updateView(item.id)">編輯</v-btn>
                    <v-btn small rounded @click="deleteData(item.id)">刪除</v-btn>
                </template>
            </v-data-table>
            <div class="text-center pa-3">
                <v-pagination v-model="page" :length="pageCount" :total-visible="5" @input="getData()"></v-pagination>
            </div>
        </v-card>
        <v-row justify="center">
            <v-dialog v-model="seachDialog" persistent max-width="600px">
                <v-card>
                    <v-card-title>
                        <span class="text-h5">進階搜尋</span>
                    </v-card-title>
                    <v-card-text>
                        <v-form ref="seachForm">
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field label="商品名稱" v-model="searchPlus.name"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field type="number" label="各樣式的數量小於" v-model="searchPlus.amount"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-autocomplete v-model="searchPlus.goods" item-text="name" item-value="id" :items="goods_all" label="上架名稱">
                                        </v-autocomplete>
                                    </v-col>

                                </v-row>
                            </v-container>
                        </v-form>
                    </v-card-text>
                    <v-container>
                        <v-row class="pr-3 pl-3">
                            <v-col cols='12' md='6' class="pb-5">
                                <v-btn block color="error" outlined @click="searchPlusReset()">
                                    重製
                                </v-btn>
                            </v-col>
                            <v-col cols='12' md='6' class="pb-5 d-flex justify-end">
                                <v-btn class="mr-3" @click="seachDialog = false">
                                    關閉
                                </v-btn>
                                <v-btn color="primary" @click="searchPlusData()">
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
    import { mdiExclamationThick } from '@mdi/js';
    export default {
    components: {

    },
    setup() {
        return {
            icons: {
                mdiExclamationThick
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
                    href: window.location.pathname + window.location.search,
                },
            ],
            headers: [
                { text: '流水號', value: 'sno', sortable: false },
                { text: '商品名稱', value: 'name', sortable: true },
                { text: '樣式', value: 'product_style', sortable: false },
                { text: '創建日期', value: 'created_at', sortable: true },
                { text: '功能', value: 'tool', sortable: false },
            ],
            page: 1 ,
            pageCount: 0,
            perPage: 20,
            options: {},
            loading: true,
            snackbar: false,
            snackbar_text: ``,
            search: null,
            searchPlus:{
                name: null,
                amount: null,
                goods:null
            },
            searchPlusActive:false,
            seachDialog: false,

            goods_all:[],
            list: [],
        }
    },
    watch: {
        options: {
            handler() {
                if (this.getData()) {
                    return this.getData();
                }else{
                    return [];
                }
            },
            deep: true,

        },
    },
    computed: {

    },
    methods: {
        searchPlusReset(){
            this.$refs.seachForm.reset()
        },
        searchPlusData(){
            var self = this;
            self.seachDialog = false
            self.getData()
            if (self.searchPlus.name || self.searchPlus.amount || self.searchPlus.goods) {
                self.searchPlusActive = true;
            }else{
                self.searchPlusActive = false;
            }

        },
        updateView(id) {
            var self = this;
            self.$router.push({ name: 'product-update', params: { id: id }  });
        },
        getView(id) {
            var self = this;
            self.$router.push({ name: 'product-get', params: { id: id } });
        },
        deleteData(id){
            var self = this;
            if (confirm("確定要永久刪除嗎 ?")==true){    
                axios.post('/api/product/delete', {
                    id:id
                })
                .then(function (response) {
                    if (response.data.result == 'success') {
                        self.getData()
                    }
                })
                .catch(function (error) {
                    if (error.response.data.result == 'goods_exist') {
                        self.snackbar = true;
                        self.snackbar_text = '此商品已上架,請先到上架頁面做刪除 ! ';
                        return false;
                    }

                    self.$router.push({ path: '/error-500' })
                });
            }  
        },
        getData() {
            var self = this;
            self.loading = true;
            const { sortBy, sortDesc } = self.options
            axios.post('/api/product/index', {
                    page: self.page,
                    sort_by: sortBy[0],
                    sort_desc: sortDesc[0],
                    search: self.search,
                    search_plus:self.searchPlus,
                    per_page:self.perPage
                })
                .then(function(response) {
                    if (response.data.result == 'success') {
                        self.list = response.data.data.data.map((d, index) => ({ ...d, sno: (self.page-1)*parseInt(self.perPage) + index + 1 }))
                        self.pageCount = response.data.data.last_page;

                        self.loading = false;
                    }
                })
                .catch(function(error) {
                    self.$router.push({ path: '/error-500' })
                });
        },
        getColor(amount) {
            if (amount > 0) return 'success'
            else if (amount == -99) return 'warning'
            else return 'error'
        },
    },
    activated(){
        var self = this;
        self.getData();
    },
    created() {
        var self = this;
        axios.post('/api/goods/get/all')
            .then(function(response) {
                if (response.data.result == 'success') {
                    var data = response.data.data;
                    self.goods_all = data;
                }
            })
            .catch(function(error) {
                self.$router.push({ path: '/error-500' })
            });
    }
}

</script>
<style type="text/css">
.v-data-table-header th,
.v-data-table td {
    white-space: nowrap;
}

</style>
