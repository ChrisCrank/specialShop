<template>
  <div class="container-lg">
    <h1 class="text-primary text-uppercase font-weight-bold">{{ $tc('checkout.title') }}</h1>
    <div v-if="user" class="d-flex flex-column">
      <template v-if="!orderSuccess">
        <div class="w-100 row">
          <div class="col-12 col-md-6">
            <h4 class="text-secondary">{{ $tc('checkout.shippingAddress') }}</h4>
            <hr class="w-100">
            {{ gender }} {{ user.firstname }} {{ user.lastname }} <br>
            {{ user.street}} <br>
            {{ user.postcode }} {{ user.city }} <br>
            {{ user.country }} <br>
            <b-button variant="primary" @click="$router.push('/Account')">Change User Data</b-button>
          </div>
          <div class="col-12 col-md-6">
            <h4 class="text-secondary">{{ $tc('checkout.billingAddress') }}</h4>
            <hr class="w-100">
            {{ gender }} {{ user.firstname }} {{ user.lastname }} <br>
            {{ user.street}} <br>
            {{ user.postcode }} {{ user.city }} <br>
            {{ user.country }} <br>
            <b-button variant="primary" @click="$router.push('/Account')">Change User Data</b-button>
          </div>
        </div>
        <div class="w-100 row mt-4">
          <div class="col-12 col-md-6">
            <h4 class="text-secondary">{{ $tc('checkout.paymentMethod.title') }}</h4>
            <hr class="w-100">
            <div class="d-flex flex-column">
              <b-form-group label="" v-slot="{ ariaDescribedby }">
                <div class="mt-2 d-flex flex-column" v-for="option in paymentMethodOptions" :key="'payment'+option.value">
                  <b-form-radio
                    v-model="paymentMethod"
                    :aria-describedby="ariaDescribedby"
                    name="paymentMethod"
                    size="lg"
                    :value="option.value">
                    {{ option.label }}
                  </b-form-radio>
                  <small class="text-muted">{{ option.description }}</small>
                </div>
              </b-form-group>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <h4 class="text-secondary">{{ $tc('checkout.shippingMethod.title') }}</h4>
            <hr class="w-100">
            <div class="d-flex flex-column">
              <b-form-group label="" v-slot="{ ariaDescribedby }">
                <div class="mt-2 d-flex flex-column" v-for="option in shippingMethodOptions" :key="'shipping'+option.value">
                  <b-form-radio
                    v-model="shippingMethod"
                    :aria-describedby="ariaDescribedby"
                    name="shippingMethod"
                    size="lg"
                    :value="option.value">
                    {{ option.label }}
                  </b-form-radio>
                  <small class="text-muted">{{ option.description }}</small>
                </div>
              </b-form-group>
            </div>
          </div>
        </div>
        <b-table show-empty striped hover responsive="sm" ref="table" :items="products" :fields="fields" :busy="loadingCart">
          <template #empty="scope">
            <b-alert variant="info" show fade>{{ $t('checkout.noProducts') }}</b-alert>
          </template>
          <template #cell(product)="row">
            <div class="d-flex">
              <div>
                <img
                  v-if="row.item.product.calculatedImages"
                  :src="imgPath + 'img/uploads/'+getOrgImage(row.item.product.calculatedImages)"
                  :srcset="getImgSrcSet(imgPath + 'img/uploads/', row.item.product.calculatedImages)"
                  :sizes="getSizes(20, 20, 20 ,20)"
                  class="mr-2"
                  style="max-width:100px;"
                  :alt="row.item.product.name + '-Thumbnail'"
                >
              </div>
              <div class="d-flex flex-column">
                <NuxtLink :to="localePath('/Product/'+encodeURI(row.item.product.name))" class="font-weight-bold">
                  {{ row.item.product.name }}
                </NuxtLink>
                <div class="text-muted">
                  {{ row.item.product.productNumber }}
                </div>
              </div>
            </div>
          </template>
          <template #cell(quantity)="row">
            <b-form-input
              id="quantity"
              :value="row.item.quantity"
              min="0"
              @change="quantity => onChangeQuantity(row.item.product.id, quantity)"
              :max="row.item.product.stock"
              type="number"
              style="max-width:70px"
            />
          </template>
          <template #cell(vat)="row">
            {{ (((row.item.product.price*row.item.quantity) / 119)*19).toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2}) }}
          </template>
          <template #cell(subTotal)="row">
            <div class="font-weight-bold">
              {{ (row.item.product.price*row.item.quantity).toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2}) }}
            </div>
          </template>
          <template #cell(close)="row">
            <b-button variant="danger" @click="removeItem(row.item.productId)"><fa icon="xmark" /></b-button>
          </template>
        </b-table>
        <div class="w-100 d-flex justify-content-end mb-4">
          <div class="col-12 col-md-6 p-0">
            <div class="bg-light p-2 mb-3">
              <h3>{{$tc('checkout.summery')}}</h3>
              <div class="row">
                <div class="col-6">{{$tc('checkout.total')}}</div>
                <div class="col-6">{{ totalCost }}</div>
              </div>
              <div class="row">
                <div class="col-6">{{$tc('checkout.shipping')}}</div>
                <div class="col-6">{{ shippingCost }}</div>
              </div>
              <hr class="w-100">
              <div class="row">
                <div class="col-6"><h4>{{$tc('checkout.grandTotal')}}</h4></div>
                <div class="col-6"><div class="h3">{{ grandTotal }}</div></div>
                <div class="col-6">{{$tc('checkout.netTotal')}}</div>
                <div class="col-6"><b>{{ netTotal }}</b></div>
                <div class="col-6">{{$tc('checkout.vatTotal')}}</div>
                <div class="col-6"><b>{{ vatTotal }}</b></div>
              </div>
            </div>
            <b-alert variant="danger" fade show v-for="(error,index) in cartErrors" :key="'error-'+index">
              {{ $t('cart.error.'+error.msgCode, {...error.payload}) }}
            </b-alert>
            <b-alert variant="danger" fade show v-for="error in errorMsg" :key="'error'+error">
              {{ error }}
            </b-alert>
            <b-button class="w-100" variant="primary" :disabled="!!(loadingCart || orderPlacing || cartErrors.length || products.length === 0)" @click="onOrderPlace">
              {{ $tc('checkout.checkout') }}
              <b-spinner v-if="orderPlacing" small></b-spinner>
            </b-button>
          </div>
        </div>
      </template>
      <div v-if="orderSuccess" class="w-100">
        <b-alert variant="success" show fade>
          {{ $tc('checkout.success') }}
          <b-button variant="primary" @click="$router.push(localePath('/'))">
            {{$tc('checkout.goBack')}}
          </b-button>
        </b-alert>
      </div>
    </div>
    <div v-else>
      <b-alert variant="info" show fade>
        <b>{{ $tc('checkout.logIn') }}</b> <br>
        <div class="w-100 d-flex justify-content-end">
          <b-button variant="primary" class="mr-2" @click="$router.push(localePath('/Login'))">{{$tc('checkout.loginAction')}}</b-button>
          <b-button variant="primary" @click="$router.push(localePath('/Register'))">{{$tc('checkout.registerAction')}}</b-button>
        </div>
      </b-alert>
    </div>
  </div>
</template>
<script>
import {mapGetters} from "vuex";
import Image from "@/mixins/Image/image";

export default {
  data () {
    return {
      orderPlacing: false,
      orderSuccess: false,
      paymentMethod:'c',
      paymentMethodOptions:[
        {
          label: this.$tc('checkout.paymentMethod.PayPal.title'),
          description: this.$tc('checkout.paymentMethod.PayPal.description'),
          value: 'p'
        },
        {
          label: this.$tc('checkout.paymentMethod.Cash.title'),
          description: this.$tc('checkout.paymentMethod.Cash.description'),
          value: 'c'
        },
        {
          label: this.$tc('checkout.paymentMethod.Invoice.title'),
          description: this.$tc('checkout.paymentMethod.Invoice.description'),
          value: 'i'
        },
      ],
      shippingMethod:'d',
      shippingMethodOptions:[
        {
          label: this.$tc('checkout.shippingMethod.express.title'),
          description: this.$tc('checkout.shippingMethod.express.description'),
          value: 'p'
        },
        {
          label: this.$tc('checkout.shippingMethod.default.title'),
          description: this.$tc('checkout.shippingMethod.default.description'),
          value: 'd'
        }
      ],

      fields: [
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
        },
        {
          key: 'close',
          label: ''
        }
      ],
      errorMsg: []
    }
  },
  mixins: [Image],
  computed: {
    ...mapGetters({
      user: 'User/getUser',
      products: 'Cart/getItems',
      loadingCart: 'Cart/getLoading',
      cartErrors: 'Cart/getErrors'
    }),
    gender () {
      switch(this.user.gender){
        case 'm':
          return this.$tc('register.genderOptions.male')
        case 'f':
          return this.$tc('register.genderOptions.female')
        default:
          return  '';
      }
    },
    totalCost() {
      // this.products is empty initinal, to keep the reactivity on quantity change listen to loading property
      this.loadingCart
      let total = 0;
      for(let i=0; i<this.products.length;i++){
        total += this.products[i].product.price * this.products[i].quantity
      }
      return total.toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2})
    },
    shippingCost(){
      let total = 0;
      return total.toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2})
    },
    grandTotal () {
      return this.totalCost
    },
    netTotal () {
      // this.products is empty initinal, to keep the reactivity on quantity change listen to loading property
      this.loadingCart
      let total = 0;
      for(let i=0; i<this.products.length;i++){
        total += ((this.products[i].product.price * this.products[i].quantity) / 119)*100
      }
      return total.toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2})
    },
    vatTotal () {
      // this.products is empty initinal, to keep the reactivity on quantity change listen to loading property
      this.loadingCart
      let total = 0;
      for(let i=0; i<this.products.length;i++){
        total += ((this.products[i].product.price * this.products[i].quantity) / 119)*19
      }
      return total.toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2})
    }
  },
  methods: {
    async onChangeQuantity(productId, quantity){
      await this.$store.dispatch('Cart/add', {productId, quantity: parseInt(quantity), set:true})
      this.$refs.table.refresh();
    },
    async removeItem(productId){
      await this.$store.dispatch('Cart/remove', productId)
      this.$refs.table.refresh();
    },
    async onOrderPlace () {
      this.orderPlacing = true;
      this.errorMsg = [];
      const products = [];
      for(let i = 0; i < this.products.length; i++)
        products.push({
          productId: this.products[i].productId,
          quantity: this.products[i].quantity
        })
      const r = await this.$store.dispatch('Order/addOrder', {
        products,
        shippingMethod: this.shippingMethod,
        paymentMethod: this.paymentMethod
      })
      if(r?.success){
        this.orderSuccess = true;
        // clear cart
        for(let i = 0; i < this.products.length; i++){
          await this.$store.dispatch('Cart/clearCart', this.products[i].productId)
        }
      }else{
        if(r?.codes?.includes('F1')){
          this.errorMsg.push(this.$tc('checkout.error.F1'))
        } else if(r?.codes?.includes('F2')){
          this.errorMsg.push(this.$tc('checkout.error.F2'))
        } else if(r?.codes?.includes('F3')){
          this.errorMsg.push(this.$tc('checkout.error.F3'))
        } else if(r?.codes?.includes('F4')){
          for(let i = 0; i < r?.productIds?.length; i++) {
            this.errorMsg.push(this.$tc('checkout.error.F4',{name: this.products.find(e => e.productId === r?.productIds[i]).product.name}))
          }
        } else {
          this.errorMsg.push(this.$tc('checkout.error.general'))
        }
      }
      this.orderPlacing = false;
    }
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
</style>
