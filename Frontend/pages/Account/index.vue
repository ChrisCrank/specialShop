<template>
  <div>
    <div class="wrapperClass d-none d-md-block">
      <ul class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
      </ul>
    </div>
    <div style="z-index:2" class="container-lg mt-5 mb-5 shadow border bg-white position-relative">
      <b-tabs content-class="mt-3">
        <b-tab title="General" class="p-2" active>
          <div class="row">
            <div class="col-12">
              <ValidationObserver ref="observer" v-slot="{ passes }">
                <form @submit.prevent="passes(onChangeData)" @reset="e => {e.preventDefault()}" v-if="userObj">
                  <div class="form-group mb-3 row">
                    <div class="col-12">
                      <div class="small pl-4">{{ $t('login.mail') }}:</div>
                      <ValidatedTextInput
                        :disableAutocomplete="false"
                        v-model="userObj.email"
                        rules="required|email"
                        type="email"
                        :placeholder="$t('login.mail')"
                        autofocus=""
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4" />
                    </div>
                  </div>
                  <b-form-checkbox v-model="changePassword" name="check-button" switch>
                    {{ $t('account.changePassword') }}
                  </b-form-checkbox>
                  <div class="form-group mb-3 row" v-if="changePassword">
                    <div class="col-12">
                      <div class="small pl-4">{{ $t('account.oldPassword') }}:</div>
                      <ValidatedTextInput
                        :disableAutocomplete="true"
                        v-model="oldPassword"
                        rules="required"
                        type="password"
                        :placeholder="$t('account.oldPassword')"
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                    </div>
                  </div>
                  <div class="form-group mb-3 row" v-if="changePassword">
                    <div class="col-12">
                      <div class="small pl-4">{{ $t('account.newPassword') }}:</div>
                      <ValidatedTextInput
                        :disableAutocomplete="true"
                        v-model="newPassword"
                        rules="required|min:8"
                        type="password"
                        :placeholder="$t('account.newPassword')"
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                    </div>
                  </div>
                  <div class="form-group mb-5 row">
                    <div class="col-12 col-md-5">
                      <div class="small pl-4">{{ $t('register.language') }}:</div>
                      <ValidatedSelectInput
                        v-model="userObj.language.iso"
                        rules="required"
                        :placeholder="$t('register.language')"
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" >
                        <template #default>
                          <b-form-select-option v-for="locale in availableLocales" :key="'language'+locale.value" :value="locale.value">{{ locale.text }}</b-form-select-option>
                        </template>
                      </ValidatedSelectInput>
                    </div>
                  </div>
                  <hr class="w-100 border-primary">
                  <div class="form-group mb-3 row">
                    <div class="col-12 col-md-5">
                      <div class="small pl-4">{{ $t('register.gender') }}:</div>
                      <ValidatedSelectInput
                        v-model="userObj.gender"
                        rules="required"
                        :placeholder="$t('register.gender')"
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" >
                        <template #default>
                          <b-form-select-option v-for="gender in genderOptions" :key="'gender'+gender.value" :value="gender.value">{{ gender.text }}</b-form-select-option>
                        </template>
                      </ValidatedSelectInput>
                    </div>
                  </div>
                  <div class="form-group mb-3 row">
                    <div class="col-12 col-md-6">
                      <div class="small pl-4">{{ $t('register.firstname') }}:</div>
                      <ValidatedTextInput
                        :disableAutocomplete="false"
                        v-model="userObj.firstname"
                        rules="required|min:3"
                        type="text"
                        :placeholder="$t('register.firstname')"
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                    </div>
                    <div class="col-12 col-md-6">
                      <div class="small pl-4">{{ $t('register.lastname') }}:</div>
                      <ValidatedTextInput
                        :disableAutocomplete="false"
                        v-model="userObj.lastname"
                        rules="required|min:3"
                        type="text"
                        :placeholder="$t('register.lastname')"
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                    </div>
                  </div>
                  <div class="form-group mb-3 row">
                    <div class="col-12">
                      <div class="small pl-4">{{ $t('register.street') }}:</div>
                      <ValidatedTextInput
                        :disableAutocomplete="false"
                        v-model="userObj.street"
                        rules="required|min:3"
                        type="text"
                        :placeholder="$t('register.street')"
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                    </div>
                  </div>
                  <div class="form-group mb-3 row">
                    <div class="col-12 col-md-6">
                      <div class="small pl-4">{{ $t('register.postcode') }}:</div>
                      <ValidatedTextInput
                        :disableAutocomplete="false"
                        v-model="userObj.postcode"
                        rules="required|integer"
                        type="number"
                        :placeholder="$t('register.postcode')"
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                    </div>
                    <div class="col-12 col-md-6">
                      <div class="small pl-4">{{ $t('register.city') }}:</div>
                      <ValidatedTextInput
                        :disableAutocomplete="false"
                        v-model="userObj.city"
                        rules="required|min:3"
                        type="text"
                        :placeholder="$t('register.city')"
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                    </div>
                  </div>
                  <div class="form-group mb-3 row">
                    <div class="col-12">
                      <div class="small pl-4">{{ $t('register.country') }}:</div>
                      <ValidatedTextInput
                        :disableAutocomplete="false"
                        v-model="userObj.country"
                        rules="required|min:3"
                        type="text"
                        :placeholder="$t('register.country')"
                        classes="col-12 p-0"
                        input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                    </div>
                  </div>
                </form>
              </ValidationObserver>
            </div>
          </div>
          <div class="row justify-content-end mt-2">
            <b-button @click="onChangeData" variant="primary" class="text-uppercase" :disabled="!!errors.length || dataExchanging || !userObj">
              {{ $t('account.save') }}
              <b-spinner small v-if="dataExchanging"></b-spinner>
            </b-button>
          </div>
        </b-tab>
        <b-tab title="Orders">
          <div v-for="order in orders" class="border-bottom border-primary d-flex align-items-center flex-column mb-2">
            <div class="d-flex flex-column w-100">
              <div class="row">
                <h2 class="w-100 text-center text-primary">Order #{{order.id}}</h2>
              </div>
              <div class="row">
                <div class="col-3">
                  OrderDate
                </div>
                <div class="col-3 font-weight-bold">
                  <b>{{(new Date(order.createdAt)).toLocaleString()}}</b>
                </div>
              </div>
              <div class="row">
                <div class="col-3">
                  Price
                </div>
                <div class="col-3 font-weight-bold">
                  <b>{{order.price.toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2})}}</b>
                </div>
              </div>
              <div class="row">
                <div class="col-3">
                  Tax
                </div>
                <div class="col-3 font-weight-bold">
                  <b>{{order.vat}}%</b>
                </div>
              </div>
            </div>
            <b-table :fields="orderItemFields" :items="order.payload.products">
              <template #cell(product)="row">
                <div class="d-flex">
                  <div>
                    <img
                      v-if="row.item.calculatedImages"
                      :src="imgPath + 'img/uploads/'+getOrgImage(row.item.calculatedImages)"
                      :srcset="getImgSrcSet(imgPath + 'img/uploads/', row.item.calculatedImages)"
                      :sizes="getSizes(20, 20, 20 ,20)"
                      class="mr-2"
                      style="max-width:100px;"
                      :alt="row.item.name + '-Thumbnail'"
                    >
                  </div>
                  <div class="d-flex flex-column">
                    <NuxtLink :to="localePath('/Product/'+row.item.id)" class="font-weight-bold">
                      <span v-if="row.item.translation.find(e => e.language.iso == currentLocale)">
                        {{ row.item.translation.find(e => e.language.iso == currentLocale).name }}
                      </span>
                      <span v-else>
                        {{ row.item.name }}
                      </span>
                    </NuxtLink>
                    <div class="text-muted">
                      {{ row.item.productNumber }}
                    </div>
                  </div>
                </div>
              </template>
              <template #cell(quantity)="row">
                {{ row.item.price*row.item.quantity }}
              </template>
              <template #cell(vat)="row">
                {{ (((row.item.price*row.item.quantity) / 119)*19).toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2}) }}
              </template>
              <template #cell(subTotal)="row">
                <div class="font-weight-bold">
                  {{ (row.item.price*row.item.quantity).toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2}) }}
                </div>
              </template>
            </b-table>
          </div>
        </b-tab>
        <b-tab title="Settings" disabled><p>I'm a disabled tab!</p></b-tab>
      </b-tabs>
      <div class="d-flex flex-column justify-content-center text-danger p-3">
        <div v-for="error in errors" :key="'error'+error" class="text-center w-100">
          {{ error }}
        </div>
        <b-alert variant="info" :show="success" dismissible fade>
          {{ $t('account.successGeneral') }}
        </b-alert>
      </div>
    </div>
  </div>
</template>
<script>
import authRequiredPage from "~/mixins/Page/authRequiredPage";
import Image from "~/mixins/Image/image";
import validationForm from "../../mixins/Form/validationForm";
import {mapGetters} from "vuex";
export default {
  head () {
    return this.$nuxtI18nHead({ addSeoAttributes: true })
  },
  mixins: [authRequiredPage, Image, validationForm],
  data () {
    return {
      userObj: null,
      oldPassword: '',
      newPassword: '',
      genderOptions: [
        { value: 'd', text: this.$tc('register.genderOptions.diverse') },
        { value: 'm', text: this.$tc('register.genderOptions.male') },
        { value: 'f', text: this.$tc('register.genderOptions.female') }
      ],
      changePassword: false,
      dataExchanging: false,
      success: false,
      orders: [],
      orderItemFields: [
        {
          key: 'product',
          label: this.$tc('checkout.list.product')
        },
        {
          key: 'quantity',
          label: this.$tc('checkout.list.quantity')
        },
        {
          key: 'vat',
          label: this.$tc('checkout.list.vat')
        },
        {
          key: 'subTotal',
          label: this.$tc('checkout.list.subTotal')
        }
      ]
    }
  },
  computed: {
    currentLocale() {
      return this.$i18n.locales.find(e => e.code === this.$i18n.locale).iso
    },
    availableLocales() {
      const options = [];
      for(let i = 0; i < this.$i18n.locales.length; i++)
        options.push({value: this.$i18n.locales[i].iso, text: this.$i18n.locales[i].name})

      return options
    },
    ...mapGetters({
      user: "User/getUser"
    })
  },
  methods: {
    onChangeData(){
      this.success = false
      this.dataExchanging = true
      const user = JSON.parse(JSON.stringify(this.userObj))
      user.lang = user.language.iso
      this.$store.dispatch('User/update', {
        ...user,
        changePassword: this.changePassword,
        newPassword: this.newPassword,
        oldPassword: this.oldPassword
      }).then(r => {
        if(r?.success){
          this.success = true
        } else {
          if(r?.codes.includes('F1'))
            this.errors.push(this.$t('account.error.F1'))
          else if (r?.codes.includes('F2'))
            this.errors.push(this.$t('account.error.F2'))
          else if (r?.codes.includes('F3'))
            this.errors.push(this.$t('account.error.F3'))
          else if (r?.codes.includes('F4'))
            this.errors.push(this.$t('account.error.F4'))
          else if (r?.codes.includes('F5'))
            this.errors.push(this.$t('account.error.F5'))
          else if (r?.codes.includes('F6'))
            this.errors.push(this.$t('account.error.F5'))
          else if (r?.codes.includes('F7'))
            this.errors.push(this.$t('account.error.F5'))
          else if (r?.codes.includes('F8'))
            this.errors.push(this.$t('account.error.F5'))
          else
            this.errors.push(this.$t('account.error.general'))
        }
        this.dataExchanging = false;
      })
    }
  },
  async created(){
    this.userObj = JSON.parse(JSON.stringify(this.user));
    await this.$store.dispatch('Order/load')
    this.orders = JSON.parse(JSON.stringify(this.$store.getters['Order/getOrders']));
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
.profileImage{
  width:200px;
  height:200px;
  border-radius: 100%;
  border:1px solid $primary;
}

*{
  box-sizing: border-box;
  margin: 0;
  padding: 0;

  font-weight: 300;
}

.wrapperClass{
  background: $primary;
  background: -webkit-linear-gradient(top, lighten($primary,100%) 0%, $primary 100%);
  background: -moz-linear-gradient(top, lighten($primary,30%) 0%, $primary 100%);
  background: -o-linear-gradient(top, lighten($primary,30%) 0%, $primary 100%);
  background: linear-gradient(to bottom, lighten($primary,100%) 0%, $primary 100%);
  width: 100%;
  height:100%;
  //margin-top: -80px;
  //padding-top: 80px;
  overflow: hidden;
  position:absolute;
  top:0;
  left:0;

  &.form-success{
    .container{
      h1{
        transform: translateY(85px);
      }
    }
  }
}

.bg-bubbles{
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;

  z-index: 1;

  li{
    position: absolute;
    list-style: none;
    display: block;
    width: 40px;
    height: 40px;
    background-color: rgba(white, 0.15);
    bottom: -160px;

    -webkit-animation: square 25s infinite;
    animation:         square 25s infinite;

    -webkit-transition-timing-function: linear;
    transition-timing-function: linear;

    &:nth-child(1){
      left: 10%;
    }

    &:nth-child(2){
      left: 20%;

      width: 80px;
      height: 80px;

      animation-delay: 2s;
      animation-duration: 17s;
    }

    &:nth-child(3){
      left: 25%;
      animation-delay: 4s;
    }

    &:nth-child(4){
      left: 40%;
      width: 60px;
      height: 60px;

      animation-duration: 22s;

      background-color: rgba(white, 0.25);
    }

    &:nth-child(5){
      left: 70%;
    }

    &:nth-child(6){
      left: 80%;
      width: 120px;
      height: 120px;

      animation-delay: 3s;
      background-color: rgba(white, 0.2);
    }

    &:nth-child(7){
      left: 32%;
      width: 160px;
      height: 160px;

      animation-delay: 7s;
    }

    &:nth-child(8){
      left: 55%;
      width: 20px;
      height: 20px;

      animation-delay: 15s;
      animation-duration: 40s;
    }

    &:nth-child(9){
      left: 25%;
      width: 10px;
      height: 10px;

      animation-delay: 2s;
      animation-duration: 40s;
      background-color: rgba(white, 0.3);
    }

    &:nth-child(10){
      left: 90%;
      width: 160px;
      height: 160px;

      animation-delay: 11s;
    }
  }
}

@-webkit-keyframes square {
  0%   { transform: translateY(0); }
  100% { transform: translateY(-700px) rotate(600deg); }
}
@keyframes square {
  0%   { transform: translateY(0); }
  100% { transform: translateY(-700px) rotate(600deg); }
}
</style>
