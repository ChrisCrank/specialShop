export default function ({ app, error }) {
  const { API_URL } = app.context.env

  app.$axios.defaults.baseURL = API_URL

  // add access-token to request
  app.$axios.onRequest((config) => {
    // set active locate to header
    config.headers['Active-Locale'] = app.i18n?.locales?.find(e => e?.code === app.i18n?.locale)?.iso

    let token = app.$cookiz.get('access-token')

    if(!token && app?.context?.req?.headers?.cookie){
      // check if cookie is set inside request body of server (for request which happends inside nuxt asyncData())
      let strCookie = new RegExp('' + 'access-token' + '[^;]+').exec(app.context.req.headers.cookie)?.[0]
      token = unescape(strCookie ? strCookie.toString().replace(/^[^=]+./, '') : '')
    }

    if (
      token && token !== '%22undefined%22' &&
      token !== 'undefined' &&
      !config.auth && // if auth is not specified
      !config.headers['Bearer']
    ) {
      config.headers['Bearer'] = token
    }
    return config
  })

  // save response access-token to cookie
  app.$axios.onResponse((response) => {
    const contextToken = response.headers?.['access-token']

    if (contextToken !== undefined && contextToken.length) {
      app.$cookiz.set('access-token', contextToken, {
        path: '/',
        maxAge: 60 * 60 * 24 * 7
      })
    }
  })
  app.$axios.interceptors.response.use((response) =>  response, (error) => {
    // if not valid push to login page
    if(error?.response && error.response.status === 401 && error.response.config.logoffOnFailure){
      app.store.dispatch('User/logoff')
      return
    } else {
      return Promise.reject(error)
    }
  })
}
