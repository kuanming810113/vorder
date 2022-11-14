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
                <v-btn class="primary mr-2" to="warehouse_manage/insert">新增</v-btn>
                <v-badge :value="searchPlusActive" :content="'!'" color="error" overlap>
                    <v-btn class="primary mr-2" @click="seachDialog=true">進階搜尋</v-btn>
                </v-badge>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="搜尋" single-line hide-details @keyup='getData()'></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="list" hide-default-footer :page.sync="page" :items-per-page="perPage" :options.sync="options" :loading="loading" class="elevation-1" mobile-breakpoint="0">
                <template v-slot:item.name="{ item }">
                    <a href="javascript:void(0)" @click="getView(item.id)">{{item.name}}</a>
                </template>
                <template v-slot:item.price="{ item }">
                    <div :class="getColor(item.price)">{{item.price}}</div>
                </template>
                <template v-slot:item.tool="{ item }">
                    <v-btn small rounded @click="updateView(item.id)" v-if="item.id">編輯</v-btn>
                    <v-btn small rounded @click="recoverAmount(item.id)" v-if="item.id">恢復數量</v-btn>
                    <v-btn small rounded @click="deleteData(item.id)" v-if="item.id">刪除</v-btn>
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
                                        <v-text-field label="倉管名稱" v-model="searchPlus.name"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                    <v-select v-model="searchPlus.company_id" label="合作廠商" :items="company_all" item-text="name" item-value="id" :clearable='true'>
                                    </v-select>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-menu ref="start_menu" v-model="start_menu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field v-model="searchPlus.start_date" label="開始日期" readonly v-bind="attrs" v-on="on" :clearable='true'></v-text-field>
                                            </template>
                                            <v-date-picker v-model="searchPlus.start_date" :active-picker.sync="start_activePicker" :max="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)" min="1950-01-01" @change="start_save"></v-date-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-menu ref="end_menu" v-model="end_menu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field v-model="searchPlus.end_date" label="結束日期" readonly v-bind="attrs" v-on="on" :clearable='true'></v-text-field>
                                            </template>
                                            <v-date-picker v-model="searchPlus.end_date" :active-picker.sync="end_activePicker" :max="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)" min="1950-01-01" @change="end_save"></v-date-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field type="number" label="金額大於" v-model="searchPlus.bigger_price"></v-text-field>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field type="number" label="金額小於" v-model="searchPlus.smaller_price"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                         <v-select v-model="searchPlus.is_count" label="計算金額" :items="is_count_items" item-text="text" item-value="value"></v-select>
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
                    text: '倉儲管理',
                    href: window.location.pathname + window.location.search,
                },
            ],
            headers: [
                { text: '流水號', value: 'sno', sortable: false },
                { text: '倉管名稱', value: 'name', sortable: true },
                { text: '廠商名稱', value: 'company_name', sortable: true },
                { text: '金額', value: 'price', sortable: true },
                { text: '日期', value: 'date', sortable: true },
                { text: '備註', value: 'remark', sortable: true },
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
                start_date: null,
                end_date: null,
                is_count:0,
                company_id:null,
                bigger_price:null,
                smaller_price:null,
            },
            searchPlusActive:false,
            seachDialog: false,

            start_menu: false,
            start_activePicker: null,
            end_menu: false,
            end_activePicker: null,

            list: [],
            is_count_items:[
                { text: '是', value: 1},
                { text: '否', value: 0},
            ],
            company_all: [],
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
        start_menu(val) {
            val && setTimeout(() => (this.start_activePicker = 'YEAR'))
        },
        end_menu(val) {
            val && setTimeout(() => (this.end_activePicker = 'YEAR'))
        },

    },
    computed: {

    },
    methods: {
        start_save(date) {
            this.$refs.start_menu.save(date)
        },
        end_save(date) {
            this.$refs.end_menu.save(date)
        },
        searchPlusReset(){
            this.$refs.seachForm.reset()
        },
        searchPlusData(){
            var self = this;
            self.seachDialog = false
            self.getData()

            if (self.searchPlus.name || self.searchPlus.start_date|| self.searchPlus.end_date|| self.searchPlus.is_count == 1|| self.searchPlus.bigger_price|| self.searchPlus.smaller_price|| self.searchPlus.company_id) {
                self.searchPlusActive = true;
            }else{
                self.searchPlusActive = false;
            }

        },
        updateView(id) {
            var self = this;
            self.$router.push({ name: 'warehouse_manage-update', params: { id: id }  });
        },
        getView(id) {
            var self = this;
            self.$router.push({ name: 'warehouse_manage-get', params: { id: id } });
        },
        deleteData(id){
            var self = this;
            if (confirm("確定要永久刪除嗎 ?")==true){    
                axios.post('/api/warehouse_manage/delete', {
                    id:id
                })
                .then(function (response) {
                    if (response.data.result == 'success') {
                        self.getData()
                    }
                })
                .catch(function (error) {
                    self.$router.push({ path: '/error-500' })
                });
            }  
        },
        recoverAmount(id){
            var self = this;
            if (confirm("確定要恢復更改數量嗎 ?")==true){    
                axios.post('/api/warehouse_manage/update/recover', {
                    id:id
                })
                .then(function (response) {
                    if (response.data.result == 'success') {
                        self.getData()
                    }
                })
                .catch(function (error) {
                    self.$router.push({ path: '/error-500' })
                });
            }  
        },
        getData() {
            var self = this;
            self.loading = true;
            const { sortBy, sortDesc } = self.options
            axios.post('/api/warehouse_manage/index', {
                    page: self.page,
                    sort_by: sortBy[0],
                    sort_desc: sortDesc[0],
                    search: self.search,
                    search_plus:self.searchPlus,
                    per_page:self.perPage
                })
                .then(function(response) {
                    if (response.data.result == 'success') {
                        var data = response.data.data.data;
                        self.list = data.map((d, index) => ({ ...d, sno: (self.page-1)*parseInt(self.perPage) + index + 1 }))
                        self.pageCount = response.data.data.last_page;

                        //計算總金額
                        if (self.searchPlus.is_count == 1) {
                            var count = 0;
                            for(var k in data){
                                count += data[k].price
                            }
                            self.list.push({
                                sno:'',
                                name:'',
                                company_name:'總金額 => ',
                                price:count,
                                date:'',
                                remark:'',
                                tool:'',
                            });
                        }

                        self.loading = false;
                    }
                })
                .catch(function(error) {
                    self.$router.push({ path: '/error-500' })
                });
        },
        getColor(price) {
            if (price > 0) return 'success--text'
            else if (price == 0) return 'secondary--text'
            else return 'error--text'
        },
    },
    activated(){
        var self = this;
        self.getData();
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
    }
}

</script>
<style type="text/css">
.v-data-table-header th,
.v-data-table td {
    white-space: nowrap;
}

</style>
