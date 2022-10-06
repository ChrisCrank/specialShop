const actions = {
  register (context, payload) {
    return new Promise((resolve, reject) => {
      this.$axios.post(this.app.context.env.baseUrl + 'User/Register/register',payload).then(r => {
        resolve(r?.data)
      }).catch(e => {
        reject(e)
      })
    })
  },
  async changeLocale (context, localeIso) {
    if(context.getters.getUser) {

      const r = await this.$axios.put(this.app.context.env.baseUrl + 'User/User/locale', {
        language: localeIso
      })
      if(r.data.success){
        context.commit('SET_USER_LANGUAGE', localeIso)
      }
    } else {
      return null
    }
  },
  activate (context, code) {
    return new Promise((resolve, reject) => {
      this.$axios.post(this.app.context.env.baseUrl + 'User/Register/activate', {code}).then(r => {
        resolve(r?.data)
      }).catch(e => {
        reject(e)
      })
    })
  },
  resendCode (context, email) {
    return new Promise((resolve, reject) => {
      this.$axios.post(this.app.context.env.baseUrl + 'User/Register/resend', {email}).then(r => {
        resolve(r?.data)
      }).catch(e => {
        reject(e)
      })
    })
  },
  login (context, payload) {
    return new Promise((resolve, reject) => {
      this.$axios.post(this.app.context.env.baseUrl + 'User/Login/login', payload, { withCredentials: true }).then(r => {
        if(r.data.success){
          context.dispatch('setUser', r.data.user)
          // reload cart
          context.dispatch('Cart/load',{},{root:true})
        }
        resolve(r?.data)
      }).catch(e => {
        reject(e)
      })
    })
  },

  sendForgotPasswordMail (context, email) {
    return new Promise((resolve, reject) => {
      this.$axios.post(this.app.context.env.baseUrl + 'User/Login/Forgot', {email}).then(r => {
        resolve(r?.data)
      }).catch(e => {
        reject(e)
      })
    })
  },
  resetPassword (context, {token, password}){
    return new Promise((resolve, reject) => {
      this.$axios.post(this.app.context.env.baseUrl + 'User/Login/Reset', {token, password}).then(r => {
        resolve(r?.data)
      }).catch(e => {
        reject(e)
      })
    })
  },

  async getUser (context,{logoffOnFailure = true}) {
    if(context.getters.getUser)
      return context.getters.getUser

    const isTokenSet = this.app.$cookiz.get('access-token') ?? ''
    if(isTokenSet || process.server) {
      const r = await this.$axios.get(this.app.context.env.baseUrl + 'User/User/index', {
        logoffOnFailure: logoffOnFailure
      }).catch(e => {

      })
      if (r?.data?.success) {
        await context.dispatch('setUser', r.data.user)
      }
    }
    return context.getters.getUser
  },
  async logoff (context) {
    await this.$axios.post(this.app.context.env.baseUrl + 'User/User/logoff', {
      logoffOnFailure: true
    }).catch(e => {
      // if token is not valid remove local token
      this.app.$cookiz.remove('access-token', {
        path: '/'
      })
    })
    await context.dispatch('Cart/clearLocalCart',{},{root:true})
    this.app.$cookiz.remove('access-token', {
      path: '/'
    })
    context.commit('SET_USER', null)
  },
  async update (context, payload) {
    return new Promise((resolve, reject) => {
      this.$axios.post(this.app.context.env.baseUrl + 'User/User/update',payload).then(r => {
        if(r.data.success){
          context.dispatch('setUser', r.data.user)
        }
        resolve(r?.data)
      }).catch(e => {
        reject(e)
      })
    })
  },


  setUser (context, user) {
    context.commit('SET_USER', user)
  }
}

export default actions
