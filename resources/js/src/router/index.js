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
      component: () => import('@/views/page/Dashboard.vue'),
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
        path: '/order/get',
        name: 'order-get',
        component: () => import('@/views/order/Get.vue'),
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
    {
        path: '/user/insert',
        name: 'user-insert',
        component: () => import('@/views/user/Insert.vue'),
    },
    {
        path: '/user/update/:id',
        name: 'user-update',
        component: () => import('@/views/user/Update.vue'),
    },
    {
        path: '/user/get',
        name: 'user-get',
        component: () => import('@/views/user/Get.vue'),
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
