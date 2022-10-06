<template>
  <div class="col-md-6 bg-dark-custom text-white d-flex justify-content-center flex-column">
    <div v-if="!success" class="login d-flex align-items-center py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-10 col-xl-7 mx-auto">
            <h3 class="display-4 cursorPointer"><img class="logoImg" src="/logo.png" @click="$router.push(localePath('/'))" alt="logo"></h3>
            <p class="text-muted mb-4 text-white-50">{{ $t('login.subtitle') }}</p>
            <ValidationObserver ref="observer" v-slot="{ passes }">
              <form v-if="!success" @submit.prevent="passes(onActivate)" @reset="e => {e.preventDefault()}">
                <div class="form-group mb-3 row">
                  <input id="email" type="text" v-model="code" :placeholder="$t('forgot.code')" required="" autofocus="" class="cInput form-control rounded-pill border-1 shadow-sm px-4">
                  <small class="p-2" v-if="code && code.length === 0">
                    {{ $t('forgot.reset-hint') }}
                  </small>
                </div>
                <div class="form-group mb-3 row">
                  <ValidatedTextInput
                    :disableAutocomplete="true"
                    v-model="password1"
                    rules="required|min:8"
                    type="password"
                    :placeholder="$t('register.password')"
                    classes="col-12 p-0"
                    input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                </div>
                <div class="form-group mb-5 row">
                  <ValidatedTextInput
                    :disableAutocomplete="true"
                    v-model="password2"
                    rules="required|min:8"
                    :errors="(password1 !== password2) ? [$t('register.password-mismatch')] : []"
                    type="password"
                    :placeholder="$t('register.password-retype')"
                    classes="col-12 p-0"
                    input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                </div>
                <div class="row">
                  <button :disabled="loading || errors.length !== 0 || (code && code.length === 0)"
                          type="submit"
                          class="btn btn-primary btn-block text-uppercase mb-2 rounded-pill shadow-sm
                                 d-flex justify-content-center align-items-center">
                    {{ $t('forgot.reset-action') }}
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
            </ValidationObserver>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="login d-flex align-items-center py-5 d-flex justify-content-center align-items-center">
      <b-alert variant="success" fade show>
        <i18n :path="'forgot.success'" tag="span">
          <template v-slot:link>
            <br/>
            <nuxt-link :to="localePath('/login')">
              {{ $t('forgot.success-link') }}
            </nuxt-link>
          </template>
        </i18n>
      </b-alert>
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
import ValidatedTextInput from "../../../components/Form/ValidatedTextInput"
import ValidatedSelectInput from "../../../components/Form/ValidatedSelectInput";
import { ValidationObserver } from 'vee-validate'
export default {
  head () {
    return this.$nuxtI18nHead({ addSeoAttributes: true })
  },
  layout: 'clean',
  components: {
    ValidationObserver,
    ValidatedTextInput,
    ValidatedSelectInput
  },
  data() {
    return {
      code: '',
      password1: '',
      password2: '',
      loading:true,
      success: false,
      errors: []
    }
  },
  mounted() {
    this.$watch(
      () => {
        return this.$refs.observer.errors
      },
      (val) => {
        this.errors = []
        for (const error in val) {
          if (val[error] && val[error].length) {
            this.errors.push(val[error][0])
          }
        }
      }
    )
    if(this.$route.params){
      this.code = this.$route.params.code
    }
    this.loading = false;
  },
  methods: {
    onActivate() {
      this.errors = [];
      this.loading = true;
      this.$store.dispatch('User/resetPassword', { token: this.code, password: this.password1 }).then(r => {
        if(r?.success){
          this.success = true;
        } else {
          if(r?.codes?.includes("F2")) {
            this.errors.push(this.$t('forgot.error.F2'))
          } else if(r?.codes?.includes("F3")) {
            this.errors.push(this.$t('forgot.error.F3'))
          } else {
            this.errors.push(this.$t('forgot.error.general'))
          }
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
