<template>
    <div id="misc">
        <img class="misc-mask" height="226" :src="require(`@/assets/images/misc/misc-mask-${$vuetify.theme.dark ? 'dark' : 'light'}.png`).default" />
        <v-img class="misc-tree" :src="require('@/assets/images/misc/tree-4.png').default"></v-img>
        <div class="page-title text-center px-5">
            <h2 class="text-2xl font-weight-semibold text--primary d-flex align-center justify-center">
                <span class="me-2">403</span>
                <v-icon color="warning">
                    {{ icons.mdiAlert }}
                </v-icon>
            </h2>
            <p class="text-sm">抱歉 ! 您無此權限 </p>
            <div class="misc-character d-flex justify-center">
                <v-img max-width="500" :src="require('@/assets/images/3d-characters/error.png').default"></v-img>
            </div>
            <v-btn color="primary" to="/login" class="mb-4"> 登入畫面 </v-btn>
        </div>
    </div>
</template>
<script>
import { mdiAlert } from '@mdi/js'

export default {
    setup() {
        return {
            icons: {
                mdiAlert,
            },
        }
    },
}

</script>
<style lang="scss">
@import '~@resources/sass/preset/misc.scss';

</style>
