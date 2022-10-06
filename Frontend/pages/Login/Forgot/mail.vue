<template>
  <div class="col-md-6 bg-dark-custom text-white d-flex justify-content-center flex-column">
    <div class="login d-flex align-items-center py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-10 col-xl-7 mx-auto">
            <h3 class="display-4 cursorPointer"><img class="logoImg" src="/logo.png" @click="$router.push(localePath('/'))" alt="logo"></h3>
            <p class="text-muted mb-4 text-white-50">{{ $t('login.subtitle') }}</p>
            <form @submit.prevent="onForgot" @reset="e => {e.preventDefault()}">
              <div class="form-group mb-3 row">
                <input id="email" type="email" v-model="email" :placeholder="$t('login.mail')" required="" autofocus="" class="cInput form-control rounded-pill border-1 shadow-sm px-4">
                <small class="p-2">
                  {{ $t('forgot.hint') }}
                </small>
              </div>
              <div class="row">
                <button :disabled="loading && email.length === 0" type="submit" class="btn btn-primary btn-block text-uppercase mb-2 rounded-pill shadow-sm">
                  {{ $t('forgot.send-action') }}
                  <b-spinner v-if="loading" class="ml-1" variant="light" small label="Spinning"></b-spinner>
                </button>
              </div>
              <div class="">
                <i18n :path="'forgot.login'" tag="span">
                  <template v-slot:link>
                    <nuxt-link :to="localePath('/login')">
                      {{ $t('forgot.login-link') }}
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
      >{{ key < 3 ? error : '...' }}</div>
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
    onForgot() {
      this.errors = []
      this.loading = true
      this.$store.dispatch('User/sendForgotPasswordMail',this.email).then(r => {
        if (r.success) {
          this.$router.push(this.localePath('/Login/Forgot/'+r?.token))
        } else {
          if(r?.codes?.includes('F1'))
            this.errors.push(this.$t('forgot.error.emailSend'))
          else
            this.errors.push(this.$t('forgot.error.general'))
        }
        this.loading = false;
      }).catch(e => {
        this.loading = false;
        this.errors.push(this.$t('forgot.error.general'))
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
