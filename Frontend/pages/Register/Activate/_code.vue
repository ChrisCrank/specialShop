<template>
  <div class="col-md-6 bg-dark-custom text-white d-flex justify-content-center flex-column">
    <div class="login d-flex align-items-center py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-10 col-xl-7 mx-auto">
            <h3 class="display-4 cursorPointer"><img class="logoImg" src="/logo.png" @click="$router.push(localePath('/'))" alt="logo"></h3>
            <p class="text-muted mb-4 text-white-50">{{ $t('login.subtitle') }}</p>
            <form v-if="!success" @submit.prevent="onActivate" @reset="e => {e.preventDefault()}">
              <div class="form-group mb-3 row">
                <input id="email" type="text" v-model="code" :placeholder="$t('activate.code')" required="" autofocus="" class="cInput form-control rounded-pill border-1 shadow-sm px-4">
                <small class="p-2">
                  {{ $t('activate.hint') }}
                </small> <br>
                <small class="p-2">
                  <i18n :path="'activate.no-code'" tag="span">
                    <template v-slot:link>
                      <nuxt-link :to="localePath('/Register/activate/resend')">
                        {{ $t('activate.resend-action') }}
                      </nuxt-link>
                    </template>
                  </i18n>
                </small>
              </div>
              <div class="row">
                <button :disabled="loading || !code"
                        type="submit"
                        class="btn btn-primary btn-block text-uppercase mb-2 rounded-pill shadow-sm
                               d-flex justify-content-center align-items-center">
                  {{ $t('activate.activate-action-link') }}
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
            </form>
            <b-alert v-else variant="success" show fade>
              <i18n :path="'activate.success'" tag="span">
                <template v-slot:link>
                  <nuxt-link :to="localePath('/login')">
                    {{ $t('activate.login-action') }}
                  </nuxt-link>
                </template>
              </i18n>
            </b-alert>
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
  data() {
    return {
      code: '',
      loading:true,
      success: false,
      errors: []
    }
  },
  mounted() {
    if(this.$route.params){
      this.code = this.$route.params.code
    }
    this.loading = false;
  },
  methods: {
    onActivate() {
      this.errors = [];
      this.loading = true;
      this.$store.dispatch('User/activate', this.code).then(r => {
        if(r?.success){
          this.success = true;
        } else {
          this.errors.push(this.$t('activate.error'))
        }
        this.loading = false;
      }).catch(e => {
        this.errors.push(this.$t('activate.error'))
        this.loading = false;
      })
    }
  }
}
</script>
