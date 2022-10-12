<template>
    <div>
        <v-card class="mb-2">
            <v-breadcrumbs :items="breadcrumbs">
                <v-breadcrumbs-item
                    slot="item"
                    slot-scope="{ item }"
                    exact
                    exact-active-class="success--text"
                    :to="item.href">
                    {{ item.text }}
                </v-breadcrumbs-item>
            </v-breadcrumbs>
        </v-card>
        <v-card>
            <div class="pa-3">
                <v-btn class="primary" to="product/insert">新增</v-btn>
            </div>
            
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="搜尋" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="desserts" :search="search">
                <template v-slot:item.calories="{ item }">
                    <span v-for="(item, index) in item.calories">
                    <v-chip class="mr-2" :color="getColor(item.num)" >
                        {{ item.name }} {{ item.num }}
                    </v-chip>
                    </span>
                </template>
                <template v-slot:item.iron="{ item }">
                    <v-btn>{{ item.iron }}</v-btn>
                    <v-btn>{{ item.iron }}</v-btn>
                </template>
            </v-data-table>
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

            search: '',
            headers: [{
                    text: 'Dessert (100g serving)',
                    align: 'start',
                    sortable: false,
                    value: 'name',
                },
                { text: 'Calories', value: 'calories' },
                { text: 'Fat (g)', value: 'fat' },
                { text: 'Carbs (g)', value: 'carbs' },
                { text: 'Protein (g)', value: 'protein' },
                { text: 'Iron (%)', value: 'iron' },
            ],
            desserts: [{
                    name: 'Frozen Yogurt',
                    calories: [{name:'test', num:'5000'},{name:'fds', num:'10'}],
                    fat: 6.0,
                    carbs: 24,
                    protein: 4.0,
                    iron: '1%',
                },
            ],
        }
    },
    methods: {
        getColor(calories) {
            if (calories > 400) return 'info'
            else if (calories > 200) return 'warning'
            else return 'error'
        },
    },
    created() {
        var self = this;
    }
}

</script>
