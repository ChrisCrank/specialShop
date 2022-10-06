<template>
    <div class="col-md-6 bg-dark-custom text-white d-flex justify-content-center flex-column">
      <div class="d-flex align-items-center py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-10 col-xl-7 mx-auto">
            <h3 class="display-4 cursorPointer"><img class="logoImg" src="/logo.png" @click="$router.push(localePath('/'))" alt="logo"></h3>
            <p class="text-muted mb-4 text-white-50">{{ $t('login.subtitle') }}</p>
            <ValidationObserver ref="observer" v-slot="{ passes }">
              <form @submit.prevent="passes(onRegister)" @reset="e => {e.preventDefault()}">
                <div class="form-group mb-3 row">
                  <div class="col-12">
                    <div class="small text-light pl-4">{{ $t('login.mail') }}:</div>
                    <ValidatedTextInput
                      :disableAutocomplete="false"
                      v-model="email"
                      rules="required|email"
                      type="email"
                      :placeholder="$t('login.mail')"
                      autofocus=""
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4" />
                  </div>
                </div>
                <div class="form-group mb-3 row">
                  <div class="col-12">
                    <div class="small text-light pl-4">{{ $t('register.password') }}:</div>
                    <ValidatedTextInput
                      :disableAutocomplete="false"
                      v-model="password1"
                      rules="required|min:8"
                      type="password"
                      :placeholder="$t('register.password')"
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                  </div>
                </div>
                <div class="form-group mb-3 row">
                  <div class="col-12">
                    <div class="small text-light pl-4">{{ $t('register.password-retype') }}:</div>
                    <ValidatedTextInput
                      :disableAutocomplete="false"
                      v-model="password2"
                      rules="required|min:8"
                      :errors="(password1 !== password2) ? [$t('register.password-mismatch')] : []"
                      type="password"
                      :placeholder="$t('register.password-retype')"
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                  </div>
                </div>
                <div class="form-group mb-5 row">
                  <div class="col-12 col-md-5">
                    <div class="small text-light pl-4">{{ $t('register.language') }}:</div>
                    <ValidatedSelectInput
                      v-model="language"
                      rules="required"
                      :placeholder="$t('register.language')"
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" >
                      <template #default>
                        <b-form-select-option v-for="locale in availableLocales" :key="'language'+locale.value" :value="locale.value">{{ locale.text }}</b-form-select-option>
                      </template>
                    </ValidatedSelectInput>
                  </div>
                </div>
                <hr class="w-100 border-primary">
                <div class="form-group mb-3 row">
                  <div class="col-12 col-md-5">
                    <div class="small text-light pl-4">{{ $t('register.gender') }}:</div>
                    <ValidatedSelectInput
                      v-model="gender"
                      rules="required"
                      :placeholder="$t('register.gender')"
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" >
                      <template #default>
                        <b-form-select-option v-for="gender in genderOptions" :key="'gender'+gender.value" :value="gender.value">{{ gender.text }}</b-form-select-option>
                      </template>
                    </ValidatedSelectInput>
                  </div>
                </div>
                <div class="form-group mb-3 row">
                  <div class="col-12 col-md-6">
                    <div class="small text-light pl-4">{{ $t('register.firstname') }}:</div>
                    <ValidatedTextInput
                      :disableAutocomplete="false"
                      v-model="firstname"
                      rules="required|min:3"
                      type="text"
                      :placeholder="$t('register.firstname')"
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="small text-light pl-4">{{ $t('register.lastname') }}:</div>
                    <ValidatedTextInput
                      :disableAutocomplete="false"
                      v-model="lastname"
                      rules="required|min:3"
                      type="text"
                      :placeholder="$t('register.lastname')"
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                  </div>
                </div>
                <div class="form-group mb-3 row">
                  <div class="col-12">
                    <div class="small text-light pl-4">{{ $t('register.street') }}:</div>
                    <ValidatedTextInput
                      :disableAutocomplete="false"
                      v-model="street"
                      rules="required|min:3"
                      type="text"
                      :placeholder="$t('register.street')"
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                  </div>
                </div>
                <div class="form-group mb-3 row">
                  <div class="col-12 col-md-6">
                    <div class="small text-light pl-4">{{ $t('register.postcode') }}:</div>
                    <ValidatedTextInput
                      :disableAutocomplete="false"
                      v-model="postcode"
                      rules="required|integer"
                      type="number"
                      :placeholder="$t('register.postcode')"
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="small text-light pl-4">{{ $t('register.city') }}:</div>
                    <ValidatedTextInput
                      :disableAutocomplete="false"
                      v-model="city"
                      rules="required|min:3"
                      type="text"
                      :placeholder="$t('register.city')"
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                  </div>
                </div>
                <div class="form-group mb-3 row">
                  <div class="col-12">
                    <div class="small text-light pl-4">{{ $t('register.country') }}:</div>
                    <ValidatedTextInput
                      :disableAutocomplete="false"
                      v-model="country"
                      rules="required|min:3"
                      type="text"
                      :placeholder="$t('register.country')"
                      classes="col-12 p-0"
                      input-classes="cInput form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                  </div>
                </div>
                <div class="form-group mb-3 row">
                  <ValidatedCheckboxInput
                    v-model="agb"
                    :name="$t('register.agb-link')"
                    :placeholder="'register.agb'"
                    rules="required"
                    classes=""
                  />
                </div>
                <div class="row">
                  <button :disabled="errors.length !== 0 || loading"
                          type="submit"
                          class="btn btn-primary btn-block text-uppercase mb-2 rounded-pill shadow-sm
                                 d-flex justify-content-center align-items-center">
                    {{ $t('register.register-action') }}
                    <b-spinner v-if="loading" class="ml-1" variant="light" small label="Spinning"></b-spinner>
                  </button>
                </div>
                <div class="">
                  <i18n :path="'register.login-hint'" tag="span">
                    <template v-slot:link>
                      <nuxt-link :to="localePath('/login')">
                        {{ $t('register.login-action-link') }}
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
    <div class="d-flex flex-column align-self-center messageContainer" v-if="errors">
      <div class="text-danger d-flex justify-content-center"
           v-for="(error, key) in errors"
           :key="error"
           v-if="key <= 3"
      >{{ key < 3 ? error : '...' }}</div>
      <div class="text-danger d-flex justify-content-center"
           v-for="(error, key) in serverErrors"
           :key="error"
           v-if="key <= 3"
      >{{ key < 3 ? error : '...' }}</div>
    </div>
  </div>
</template>
<script>
import validationForm from "../../mixins/Form/validationForm";
export default {
  layout: 'clean',
  mixins:[validationForm],
  head() {
    const i18nHead = this.$nuxtI18nHead({ addSeoAttributes: true })
    return  {
      title: this.$t('register.title'),
      htmlAttrs: i18nHead.htmlAttrs,
      meta: i18nHead.meta,
      link: [
        ...i18nHead.link
      ]
    }
  },
  data() {
    return {
      agb: false,
      email: null,
      firstname: null,
      lastname: null,
      street: null,
      city: null,
      country: null,
      postcode: null,
      password1: null,
      password2: null,
      language: this.$i18n.locales.find(e => e.code === this.$i18n.locale).iso,
      gender: 'd',
      genderOptions: [
        { value: 'd', text: this.$tc('register.genderOptions.diverse') },
        { value: 'm', text: this.$tc('register.genderOptions.male') },
        { value: 'f', text: this.$tc('register.genderOptions.female') }
      ],
      loading: false,
      serverErrors: []
    }
  },
  computed: {
    availableLocales() {
      const options = [];
      for(let i = 0; i < this.$i18n.locales.length; i++)
        options.push({value: this.$i18n.locales[i].iso, text: this.$i18n.locales[i].name})

      return options
    }
  },
  methods: {
    onRegister () {
      this.errors = []
      this.serverErrors = []
      if(this.password1 !== this.password2){
        this.errors.push(this.$t('register.password-mismatch'))
      }
      this.loading = true;
      this.$store.dispatch('User/register', {
        email: this.email,
        password: this.password1,
        lang: this.language,
        gender: this.gender,
        firstname: this.firstname,
        lastname: this.lastname,
        street: this.street,
        country: this.country,
        postcode: this.postcode,
        city: this.city,
      }).then(r => {
        if(r?.success){
          this.$router.push(this.localePath('/register/activate/'+r?.activationToken))
        } else {
          if (r?.codes?.includes('F1')){
            this.serverErrors.push(this.$t('register.error.F1'))
          } else if (r?.codes?.includes('F2')) {
            this.serverErrors.push(this.$t('register.error.F2'))
          } else if (r?.codes?.includes('F3')) {
            this.serverErrors.push(this.$t('register.error.F3'))
          } else if (r?.codes?.includes('F4')) {
            this.serverErrors.push(this.$t('register.error.F4'))
          } else if (r?.codes?.includes('F5')) {
            this.serverErrors.push(this.$t('register.error.F5'))
          } else if (r?.codes?.includes('F6')) {
            this.serverErrors.push(this.$t('register.error.F6'))
          } else if (r?.codes?.includes('F7')) {
            this.serverErrors.push(this.$t('register.error.F7'))
          } else{
            this.serverErrors.push(this.$t('register.error.general'))
          }
        }
        this.loading = false;
      }).catch(e => {
        console.log(e)
        this.serverErrors.push(this.$t('register.error.general'))
        this.loading = false;
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
