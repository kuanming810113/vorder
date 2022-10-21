import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [{
        path: '/',
        redirect: 'dashboard',
    },
    //儀錶板
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('@/views/dashboard/index.vue'),
    },

    //訂單
    {
        path: '/order',
        name: 'order',
        component: () => import('@/views/order/Index.vue'),
    },
    {
        path: '/order/insert',
        name: 'order-insert',
        component: () => import('@/views/order/Insert.vue'),
    },
    {
        path: '/order/update/:id',
        name: 'order-update',
        component: () => import('@/views/order/Update.vue'),
    },
    {
        path: '/order/get/:id',
        name: 'order-get',
        component: () => import('@/views/order/Get.vue'),
    },

    //商品設定
    {
        path: '/product',
        name: 'product',
        component: () => import('@/views/product/Index.vue'),
        meta: {
            keepAlive: true,
        },
    },
    {
        path: '/product/insert',
        name: 'product-insert',
        component: () => import('@/views/product/Insert.vue'),
    },
    {
        path: '/product/update/:id',
        name: 'product-update',
        component: () => import('@/views/product/Update.vue'),
    },
    {
        path: '/product/get/:id',
        name: 'product-get',
        component: () => import('@/views/product/Get.vue'),
    },

    //發布類別
    {
        path: '/goods_category',
        name: 'goods_category',
        component: () => import('@/views/goods_category/Index.vue'),
        meta: {
            keepAlive: true,
        },
    },
    {
        path: '/goods_category/insert',
        name: 'goods_category-insert',
        component: () => import('@/views/goods_category/Insert.vue'),
    },
    {
        path: '/goods_category/update/:id',
        name: 'goods_category-update',
        component: () => import('@/views/goods_category/Update.vue'),
    },
    {
        path: '/goods_category/get/:id',
        name: 'goods_category-get',
        component: () => import('@/views/goods_category/Get.vue'),
    },

    //發布商品
    {
        path: '/goods',
        name: 'goods',
        component: () => import('@/views/goods/Index.vue'),
        meta: {
            keepAlive: true,
        },
    },
    {
        path: '/goods/insert',
        name: 'goods-insert',
        component: () => import('@/views/goods/Insert.vue'),
    },
    {
        path: '/goods/update/:id',
        name: 'goods-update',
        component: () => import('@/views/goods/Update.vue'),
    },
    {
        path: '/goods/get/:id',
        name: 'goods-get',
        component: () => import('@/views/goods/Get.vue'),
    },

    //發布組合
    {
        path: '/goods_combo',
        name: 'goods_combo',
        component: () => import('@/views/goods_combo/Index.vue'),
        meta: {
            keepAlive: true,
        },
    },
    {
        path: '/goods_combo/insert',
        name: 'goods_combo-insert',
        component: () => import('@/views/goods_combo/Insert.vue'),
    },
    {
        path: '/goods_combo/update/:id',
        name: 'goods_combo-update',
        component: () => import('@/views/goods_combo/Update.vue'),
    },
    {
        path: '/goods_combo/get/:id',
        name: 'goods_combo-get',
        component: () => import('@/views/goods_combo/Get.vue'),
    },
    
    //登入
    {
        path: '/login',
        name: 'login',
        component: () => import('@/views/page/Login.vue'),
        meta: {
            layout: 'blank',
        },
    },
    //註冊
    {
        path: '/register',
        name: 'register',
        component: () => import('@/views/page/Register.vue'),
        meta: {
            layout: 'blank',
        },
    },

    //使用者
    {
        path: '/user',
        name: 'user',
        component: () => import('@/views/user/Index.vue'),
    },

    //錯誤頁面
    {
        path: '/error-403',
        name: 'error-403',
        component: () => import('@/views/errors/403.vue'),
        meta: {
            layout: 'blank',
        },
    },
    {
        path: '/error-404',
        name: 'error-404',
        component: () => import('@/views/errors/404.vue'),
        meta: {
            layout: 'blank',
        },
    },
    {
        path: '/error-500',
        name: 'error-500',
        component: () => import('@/views/errors/500.vue'),
        meta: {
            layout: 'blank',
        },
    },
    {
        path: '*',
        redirect: 'error-404',
    },


    // {
    //   path: '/dashboard',
    //   name: 'dashboard',
    //   component: () => import('@/views/dashboard/Dashboard.vue'),
    // },
    // {
    //   path: '/typography',
    //   name: 'typography',
    //   component: () => import('@/views/typography/Typography.vue'),
    // },
    // {
    //   path: '/icons',
    //   name: 'icons',
    //   component: () => import('@/views/icons/Icons.vue'),
    // },
    // {
    //   path: '/cards',
    //   name: 'cards',
    //   component: () => import('@/views/cards/Card.vue'),
    // },

    // {
    //   path: '/simple-table',
    //   name: 'simple-table',
    //   component: () => import('@/views/simple-table/SimpleTable.vue'),
    // },
    // {
    //   path: '/form-layouts',
    //   name: 'form-layouts',
    //   component: () => import('@/views/form-layouts/FormLayouts.vue'),
    // },
    // {
    //   path: '/pages/account-settings',
    //   name: 'pages-account-settings',
    //   component: () => import('@/views/pages/account-settings/AccountSettings.vue'),
    // },
    // {
    //   path: '/pages/login',
    //   name: 'pages-login',
    //   component: () => import('@/views/pages/Login.vue'),
    //   meta: {
    //     layout: 'blank',
    //   },
    // },
    // {
    //   path: '/pages/register',
    //   name: 'pages-register',
    //   component: () => import('@/views/pages/Register.vue'),
    //   meta: {
    //     layout: 'blank',
    //   },
    // },
    // {
    //   path: '/error-404',
    //   name: 'error-404',
    //   component: () => import('@/views/Error.vue'),
    //   meta: {
    //     layout: 'blank',
    //   },
    // },

]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes,
})

export default router
