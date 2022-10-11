import Vue from 'vue'
import Vuetify from 'vuetify/lib/framework'
import preset from './default-preset/preset'

Vue.use(Vuetify)

import zhHant from 'vuetify/lib/locale/zh-Hant'

export default new Vuetify({
    lang: {
        locales: { zhHant },
        current: 'zhHant',
    },
    preset,
    icons: {
        iconfont: 'mdiSvg',
    },
    theme: {
        options: {
            customProperties: true,
            variations: false,
        },
    },
})
