<template>
    <v-menu offset-y left nudge-bottom="14" min-width="230" content-class="user-profile-menu-content">
        <template v-slot:activator="{ on, attrs }">
            <v-badge bottom color="success" overlap offset-x="12" offset-y="12" class="ms-4" dot>
                <v-avatar size="40px" v-bind="attrs" v-on="on">
                    <v-img :src="require('@/assets/images/avatars/1.png').default"></v-img>
                </v-avatar>
            </v-badge>
        </template>
        <v-list>
            <div class="pb-3 pt-2">
                <v-badge bottom color="success" overlap offset-x="12" offset-y="12" class="ms-4" dot>
                    <v-avatar size="40px">
                        <v-img :src="require('@/assets/images/avatars/1.png').default"></v-img>
                    </v-avatar>
                </v-badge>
                <div class="d-inline-flex flex-column justify-center ms-3" style="vertical-align: middle">
                    <span class="text--primary font-weight-semibold mb-n1"> {{main.user_name}} </span>
                    <small class="text--disabled text-capitalize">Admin</small>
                </div>
            </div>
            <v-divider></v-divider>
            <!-- Profile -->
            <v-list-item link>
                <v-list-item-icon class="me-2">
                    <v-icon size="22">
                        {{ icons.mdiAccountOutline }}
                    </v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Profile</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <!-- Email -->
            <v-list-item link>
                <v-list-item-icon class="me-2">
                    <v-icon size="22">
                        {{ icons.mdiEmailOutline }}
                    </v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Inbox</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <!-- Chat -->
            <v-list-item link>
                <v-list-item-icon class="me-2">
                    <v-icon size="22">
                        {{ icons.mdiChatOutline }}
                    </v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Chat</v-list-item-title>
                </v-list-item-content>
                <v-list-item-action>
                    <v-badge inline color="error" content="2"> </v-badge>
                </v-list-item-action>
            </v-list-item>
            <v-divider class="my-2"></v-divider>
            <!-- Settings -->
            <v-list-item link>
                <v-list-item-icon class="me-2">
                    <v-icon size="22">
                        {{ icons.mdiCogOutline }}
                    </v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Settings</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <!-- Pricing -->
            <v-list-item link>
                <v-list-item-icon class="me-2">
                    <v-icon size="22">
                        {{ icons.mdiCurrencyUsd }}
                    </v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Pricing</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <!-- FAQ -->
            <v-list-item link>
                <v-list-item-icon class="me-2">
                    <v-icon size="22">
                        {{ icons.mdiHelpCircleOutline }}
                    </v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>FAQ</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-divider class="my-2"></v-divider>
            <!-- Logout -->
            <v-list-item link>
                <v-list-item-icon class="me-2">
                    <v-icon size="22">
                        {{ icons.mdiLogoutVariant }}
                    </v-icon>
                </v-list-item-icon>
                <v-list-item-content @click="logout">
                    <v-list-item-title>登出</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-menu>
</template>
<script>
import _ from "lodash"
import {
    mdiAccountOutline,
    mdiEmailOutline,
    mdiCheckboxMarkedOutline,
    mdiChatOutline,
    mdiCogOutline,
    mdiCurrencyUsd,
    mdiHelpCircleOutline,
    mdiLogoutVariant,
} from '@mdi/js'

export default {
    data() {
        return {
            icons: {
                mdiAccountOutline,
                mdiEmailOutline,
                mdiCheckboxMarkedOutline,
                mdiChatOutline,
                mdiCogOutline,
                mdiCurrencyUsd,
                mdiHelpCircleOutline,
                mdiLogoutVariant,
            },
            main:{
                user_name:'test'
            }
        }
    },
    methods: {
        logout: _.debounce(function() {
            var self = this;
            axios.post('/api/user/logout')
                .then(function(response) {
                    if (response.data.result == 'success') {
                        self.$router.push({ path: '/login' })
                    }
                })
                .catch(function(error) {
                    self.$router.push({ path: '/error-500' })
                });

        }, 1000, {
            'leading': true,
            'trailing': false,
        }),

    },
    created() {
        var self = this;
        axios.post('/api/user/get/auth')
            .then(function(response) {
                if (response.data.result == 'success') {
                    self.main.user_name = response.data.data.name;
                }
            })
            .catch(function(error) {
                location.href='/error-403'
            });
    }

}

</script>
<style lang="scss">
.user-profile-menu-content {
    .v-list-item {
        min-height: 2.5rem !important;
    }
}

</style>
