<template>
  <div class="col-md-6 bg-dark text-white d-flex justify-content-center flex-column">
    <div class="login d-flex align-items-center py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-10 col-xl-7 mx-auto">
            <h3 class="display-4 cursorPointer"><img class="logoImg" src="/logo.png" @click="$router.push(localePath('/'))" alt="logo"></h3>
            <p class="text-muted mb-4 text-white-50">{{ $t('login.subtitle') }}</p>
            <div v-if="$route.query.logoff">
              <b-alert class="mt-4 mb-4" variant="info" show fade>{{$t('login.logoff-hint')}}</b-alert>
            </div>
            <form @submit.prevent="onLogin" @reset="e => {e.preventDefault()}">
              <div class="form-group mb-3 row">
                <input id="email" type="text" v-model="email" :placeholder="$t('login.mailuser')" required="" autofocus="" class="cInput form-control rounded-pill border-1 shadow-sm px-4">
              </div>
              <div class="form-group mb-3 row">
                <input id="password" type="password" v-model="password" :placeholder="$t('login.password')" required="" class="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary">
              </div>
              <div class="custom-control custom-checkbox mb-3 row">
                <input id="rememberMe" type="checkbox" v-model="rememberMe" checked class="custom-control-input">
                <label for="rememberMe" class="custom-control-label">{{ $t('login.remember') }}</label>
              </div>
              <div class="row">
                <button :disabled="loading" type="submit" class="btn btn-primary btn-block text-uppercase mb-2 rounded-pill shadow-sm">
                  {{ $t('login.login-action') }}
                  <b-spinner v-if="loading" class="ml-1" variant="light" small label="Spinning"></b-spinner>
                </button>
              </div>
              <div class="">
                <i18n :path="'login.register-hint'" tag="span">
                  <template v-slot:link>
                    <nuxt-link :to="localePath('/register')">
                      {{ $t('login.register-action-link') }}
                    </nuxt-link>
                  </template>
                </i18n>
              </div>
              <div class="">
                <i18n :path="'login.forgot-password'" tag="span">
                  <template v-slot:link>
                    <nuxt-link :to="localePath('/login/forgot/mail')">
                      {{ $t('login.forgot-password-link') }}
                    </nuxt-link>
                  </template>
                </i18n>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex flex-column align-self-center messageContainer" v-if="errors">
      <div class="text-danger d-flex justify-content-center"
           v-for="(error, key) in errors"
           :key="error"
           v-if="key <= 3"
      >
        <i18n :path="'login.error.active'" tag="span" v-if="error === $t('login.error.active')">
          <template v-slot:link>
            <nuxt-link :to="localePath('/Register/Activate/resend')">
              {{ $t('login.error.active-link') }}
            </nuxt-link>
          </template>
        </i18n>
        <span v-else>
          {{ key < 3 ? error : '...' }}
        </span>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  head () {
    return this.$nuxtI18nHead({ addSeoAttributes: true })
  },
  layout: 'clean',
  data () {
    return {
      email: '',
      password: '',
      rememberMe: '',

      loading: true,
      errors: []
    }
  },
  mounted () {
    this.loading = false
  },
  methods: {
    onLogin() {
      this.errors = []
      this.loading = true
      this.$store.dispatch('User/login',{
        email: this.email,
        password: this.password,
        rememberMe: this.rememberMe,
      }).then(r => {
        if (r.success) {
          const locale = this.$i18n.locales.filter(i => i.iso === r?.user?.language?.iso)?.[0]?.code
          if(r?.user.isAdmin)
            this.$router.push(this.localePath('/Portal', locale))
          else
            this.$router.push(this.localePath('/', locale))
        } else {
          if(r?.codes?.includes('F1'))
            this.errors.push(this.$t('login.error.F1'))
          else if (r?.codes?.includes('F3'))
            this.errors.push(this.$t('login.error.F3'))
          else
            this.errors.push(this.$t('login.error.general'))
        }
        this.loading = false;
      }).catch(e => {
        console.log(e)
        console.log('errror')
        this.loading = false;
        this.errors.push(this.$t('login.error.general'))
      })
    }
  }
}
</script>
<style>
.messageContainer {
  min-height:7rem;
}
</style>
