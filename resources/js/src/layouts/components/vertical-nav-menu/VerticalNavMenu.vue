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
            <nav-menu-group title="訂單" :icon="icons.mdiViewListOutline">
                <nav-menu-link title="訂單列表" :to="{ name: 'order' }"></nav-menu-link>
                <nav-menu-link title="本日即時訂單" :to="{ name: 'order_timely' }"></nav-menu-link>
            </nav-menu-group>
            <nav-menu-group title="成本" :icon="icons.mdiViewListOutline">
                <nav-menu-link title="成本紀錄" :to="{ name: 'cost' }"></nav-menu-link>
                <nav-menu-link title="供應商" :to="{ name: 'vendor' }"></nav-menu-link>
            </nav-menu-group>
            <nav-menu-group title="商品管理" :icon="icons.mdiViewListOutline">
                <nav-menu-link title="商品庫存" :to="{ name: 'product' }"></nav-menu-link>
                <nav-menu-link title="倉儲管理" :to="{ name: 'change_inventory' }"></nav-menu-link>
                <nav-menu-link title="庫存更動紀錄" :to="{ name: 'change_inventory_record' }"></nav-menu-link>
            </nav-menu-group>
            <nav-menu-group title="成品設定" :icon="icons.mdiViewListOutline">
                <nav-menu-link title="成品分類" :to="{ name: 'product_type' }"></nav-menu-link>
                <nav-menu-link title="成品設定" :to="{ name: 'product_finish' }"></nav-menu-link>
            </nav-menu-group>
            <nav-menu-group title="一般設定" :icon="icons.mdiViewListOutline">
                <nav-menu-link title="使用者設定" :to="{ name: 'user' }"></nav-menu-link>
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
