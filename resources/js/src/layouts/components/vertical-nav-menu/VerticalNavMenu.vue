<template>
    <v-navigation-drawer :value="isDrawerOpen" app floating width="260" class="app-navigation-menu" :right="$vuetify.rtl" @input="val => $emit('update:is-drawer-open', val)">
        <!-- Navigation Header -->
        <div class="vertical-nav-header d-flex items-center ps-6 pe-5 pt-5 pb-2">
            <router-link to="/" class="d-flex align-center text-decoration-none">
                <v-img :src="require('@/assets/images/logos/logo.svg').default" max-height="30px" max-width="30px" alt="logo" contain eager class="app-logo me-3"></v-img>
                <v-slide-x-transition>
                    <h2 class="app-title text--primary">訂單後台系統</h2>
                </v-slide-x-transition>
            </router-link>
        </div>


        
        <!-- Navigation Items -->
        <v-list expand shaped class="vertical-nav-menu-items pr-5">
            <nav-menu-link title="儀表板" :to="{ name: 'dashboard' }" :icon="icons.mdiViewListOutline"></nav-menu-link>
            <nav-menu-group title="訂單" :icon="icons.mdiViewListOutline" :active="(this.$route.name == 'order' || this.$route.name == 'order_timely') ? 1 : 0">
                <nav-menu-link title="訂單列表" :to="{ name: 'order' }"></nav-menu-link>
                <nav-menu-link title="本日即時訂單" :to="{ name: 'order_timely' }"></nav-menu-link>
            </nav-menu-group>
            <nav-menu-group title="內部管理" :icon="icons.mdiViewListOutline" :active="(this.$route.name == 'account' || this.$route.name == 'company') ? 1 : 0">
                <nav-menu-link title="帳務紀錄" :to="{ name: 'account' }"></nav-menu-link>
                <nav-menu-link title="合作廠商" :to="{ name: 'company' }"></nav-menu-link>
            </nav-menu-group>
            <nav-menu-group title="庫存管理" :icon="icons.mdiViewListOutline" :active="(this.$route.name == 'product' || this.$route.name == 'warehouse_manage'|| this.$route.name == 'inventory_change_record') ? 1 : 0">
                <nav-menu-link title="商品設定" :to="{ name: 'product' }"></nav-menu-link>
                <nav-menu-link title="倉儲管理" :to="{ name: 'warehouse_manage' }"></nav-menu-link>
                <nav-menu-link title="庫存更動紀錄" :to="{ name: 'inventory_change_record' }"></nav-menu-link>
            </nav-menu-group>
            <nav-menu-group title="上架設定" :icon="icons.mdiViewListOutline" :active="(this.$route.name == 'goods_category' || this.$route.name == 'goods_combo'|| this.$route.name == 'goods') ? 1 : 0">
                <nav-menu-link title="類別設定" :to="{ name: 'goods_category' }"></nav-menu-link>
                <nav-menu-link title="組合設定" :to="{ name: 'goods_combo' }"></nav-menu-link>
                <nav-menu-link title="上架" :to="{ name: 'goods' }"></nav-menu-link>
            </nav-menu-group>
            <nav-menu-group title="一般設定" :icon="icons.mdiViewListOutline" :active="(this.$route.name == 'user' ) ? 1 : 0">
                <nav-menu-link title="基本設定" :to="{ name: 'user' }"></nav-menu-link>
            </nav-menu-group>
        </v-list>
    </v-navigation-drawer>
</template>
<script>
// eslint-disable-next-line object-curly-newline
import {
    mdiViewListOutline,
} from '@mdi/js'
import NavMenuSectionTitle from './components/NavMenuSectionTitle.vue'
import NavMenuGroup from './components/NavMenuGroup.vue'
import NavMenuLink from './components/NavMenuLink.vue'

export default {
    components: {
        NavMenuSectionTitle,
        NavMenuGroup,
        NavMenuLink,
    },
    props: {
        isDrawerOpen: {
            type: Boolean,
            default: null,
        },
    },
    setup() {
        return {
            icons: {
                mdiViewListOutline,
            },
        }
    },
}

</script>
<style lang="scss" scoped>
@import '@resources/sass/preset/mixins.scss';

.app-title {
    font-size: 1.25rem;
    font-weight: 700;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: 0.3px;
}

// ? Adjust this `translateX` value to keep logo in center when vertical nav menu is collapsed (Value depends on your logo)
.app-logo {
    transition: all 0.18s ease-in-out;

    .v-navigation-drawer--mini-variant & {
        transform: translateX(-4px);
    }
}

@include theme(app-navigation-menu) using ($material) {
    background-color: map-deep-get($material, 'background');
}

.app-navigation-menu {
    .v-list-item {
        &.vertical-nav-menu-link {
            ::v-deep .v-list-item__icon {
                .v-icon {
                    transition: none !important;
                }
            }
        }
    }
}

// You can remove below style
// Upgrade Banner
.app-navigation-menu {
    .upgrade-banner {
        position: absolute;
        bottom: 13px;
        left: 50%;
        transform: translateX(-50%);
    }
}

</style>
