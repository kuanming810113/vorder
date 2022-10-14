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
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="搜尋" single-line hide-details @keyup='getData()'></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="list" hide-default-footer :page.sync="page" :items-per-page="perPage" :options.sync="options" :loading="loading" class="elevation-1" mobile-breakpoint="0">
                <template v-slot:item.product_style="{ item }">
                    <span v-for="(item, index) in item.product_style">
                        <v-badge
                            class="mr-5 ml-3 mb-1"
                            :color="getColor(item.amount)"
                            :content="(item.amount == -99) ? '無限' : String(item.amount)"
                            overlap
                        >
                          <v-chip label>{{ item.name }}</v-chip>
                        </v-badge>

                    </span>
                </template>
                <template v-slot:item.name="{ item }">
                    <a href="javascript:void(0)" @click="getView(item.id)">{{item.name}}</a>
                </template>
                <template v-slot:item.tool="{ item }">
                    <v-btn small rounded @click="updateView(item.id)" class="ma-1">編輯</v-btn>
                    <v-btn small rounded @click="deleteData(item.id)" class="ma-1">刪除</v-btn>
                </template>
            </v-data-table>
            <div class="text-center pa-3">
                <v-pagination v-model="page" :length="pageCount" :total-visible="5" @input="getData()"></v-pagination>
            </div>
        </v-card>
    </div>
</template>
<script>
    export default {
    components: {

    },

    data() {
        return {
            breadcrumbs: [{
                    text: '庫存管理',
                    href: '',
                },
                {
                    text: '商品設定',
                    href: 'product',
                },
            ],
            headers: [
                { text: '流水號', value: 'sno', sortable: false },
                { text: '商品名稱', value: 'name', sortable: true },
                { text: '樣式', value: 'product_style', sortable: false },
                { text: '上架商品數量', value: 'goods_amount', sortable: false },
                { text: '創建日期', value: 'created_at', sortable: true },
                { text: '功能', value: 'tool', sortable: false },
            ],
            page: 1,
            pageCount: 0,
            perPage: 20,
            options: {},
            loading: true,
            search: '',


            list: [],
        }
    },
    watch: {
        options: {
            handler() {
                return this.getData()
            },
            deep: true,
        },
    },
    computed: {

    },
    methods: {
        updateView(id) {
            this.$router.push({ name: 'product-update', params: { id: id } });
        },
        getView(id) {
            this.$router.push({ name: 'product-get', params: { id: id } });
        },
        getColor(amount) {
            if (amount > 0) return 'success'
            else if (amount == -99) return 'warning'
            else return 'error'
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
                    per_page:self.perPage
                })
                .then(function(response) {
                    if (response.data.result == 'success') {
                        self.list = response.data.data.data.map((d, index) => ({ ...d, sno: (self.page-1)*20 + index + 1 }))
                        self.pageCount = response.data.data.last_page;
                        self.loading = false;
                    }
                })
                .catch(function(error) {
                    self.$router.push({ path: '/error-500' })
                });
        },
    },
    created() {
        var self = this;
        // axios.post('/api/product/index')
        //     .then(function(response) {
        //         if (response.data.result == 'success') {
        //             self.list = response.data.data.data;
        //             self.pageCount = response.data.data.last_page;
        //             self.loading=false;
        //         }
        //     })
        //     .catch(function(error) {
        //         self.$router.push({ path: '/error-500' })
        //     });
    }
}

</script>
<style type="text/css">
.v-data-table-header th,.v-data-table td {
    white-space: nowrap;
}


</style>
