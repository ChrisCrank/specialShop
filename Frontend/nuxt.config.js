export default {
  env: {
    baseUrl: (process.env.IS_DEV === 'true') ? process.env.API_URL_DEV : process.env.API_URL,
    docUrl: (process.env.IS_DEV === 'true') ? process.env.API_URL_BASE_DEV : process.env.API_URL_BASE,
  },
  router: {
    base:
      process.env.NODE_ENV === "development" ? process.env.BASE_URL : "/",
  },
  head: {
    title: 'Special Shop',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.png' }
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '@/assets/main.scss'
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    '@/plugins/axios',
    '@/plugins/vee-validate'
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/typescript
    '@nuxt/typescript-build',
    ['@nuxtjs/fontawesome', {
      component: 'fa',
      icons: {
        solid: true,
        regular: true
      },
    }]
  ],
  fontawesome: {
    icons: {
      solid: true,
      regular: true
    }
  },

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/bootstrap
    '@nuxtjs/axios',
    ['nuxt-lazy-load',{directiveOnly: true, defaultImage:'/loading.gif'}],
    ['cookie-universal-nuxt', { alias: 'cookiz' }],
    'bootstrap-vue/nuxt',
    [
      'nuxt-i18n',
      {
        locales: [
          { code: 'de', iso: 'de-DE', file: 'de-DE.json', name: "Deutsch", shortName: "DE" },
          { code: 'en', iso: 'en-EN', file: 'en-EN.json', name: "English", shortName: "EN" }
        ],
        langDir: 'lang/',
        defaultLocale: 'en',
        strategy: 'prefix_except_default',
        lazy: true,
        detectBrowserLanguage: {
          // If enabled, a cookie is set once a user has been redirected to his
          // preferred language to prevent subsequent redirections
          // Set to false to redirect every time
          useCookie: true,
          // Cookie name
          cookieKey: 'i18n_redirected',
          // Set to always redirect to value stored in the cookie, not just once
          fallbackLocale: 'en'
        },
        vueI18n: {
          fallbackLocale: 'en'
        }
      }
    ]
  ],
  bootstrapVue: {
    icons: true
  },
  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    transpile: ['vee-validate/dist/rules']
  }
}
