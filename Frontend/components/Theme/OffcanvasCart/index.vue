<template>
  <b-sidebar
    title="Cart"
    v-model="currentState"
    backdrop
    shadow
    right
    @hidden="onCloseModal"
  >
    <template v-if="!loading">
      <b-alert variant="danger" show fade v-for="(error,index) in errors" :key="'cartError'+index">
        {{ $t('cart.error.'+error.msgCode, {...error.payload}) }}
      </b-alert>
      <div v-for="item in items" :key="'item-'+item.product.id" class="row m-2 p-0 d-flex align-items-center border-bottom mb-2 pb-2 position-relative">
        <div class="removeProduct" @click="removeCartItem(item.product.id)"><fa icon="xmark"></fa></div>
        <img
          v-if="item.product.calculatedImages"
          :src="imgPath + 'img/uploads/'+getOrgImage(item.product.calculatedImages)"
          :srcset="getImgSrcSet(imgPath + 'img/uploads/', item.product.calculatedImages)"
          :sizes="getSizes(20, 20, 20 ,20)"
          class="col-4 p-0 m-0"
          :alt="item.product.name + '-Thumbnail'"
        >
        <div class="col-8 m-0 p-0 pl-2">
          <NuxtLink :to="localePath('/Product/'+encodeURI(item.product.name))" class="text-primary font-weight-bold">
            {{ item.product.name }}
          </NuxtLink>
          <div class="d-flex justify-content-between">
            <div class="font-weight-bold">{{(item.product.price*item.quantity).toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2})}}</div>
            <div>
              <b-form-input
                id="quantity"
                :value="item.quantity"
                min="0"
                @change="quantity => onChangeQuantity(item.product.id, quantity)"
                :max="item.product.stock"
                type="number"
                style="max-width:70px"
              />
            </div>
          </div>
        </div>
      </div>
    </template>
    <div v-else class="w-100 d-flex justify-content-center align-items-center">
      <b-spinner variant="primary" size="lg"></b-spinner>
    </div>
    <div v-if="items.length === 0">
      <b-alert variant="info" show>
        {{ $t('cart.noProducts') }}
      </b-alert>
    </div>
    <div v-else>
      <div class="w-100 pr-2 d-flex justify-content-end">
        <b>{{ completePrice }}</b>
      </div>
      <b-button variant="primary" class="w-100 mt-2" @click="$router.push(localePath('/Checkout/'))">{{ $t('cart.toCheckout') }}</b-button>
    </div>
  </b-sidebar>
</template>
<script>
import {mapGetters} from "vuex";
import Image from "@/mixins/Image/image";

export default {
  data () {
    return {
      currentState: false
    }
  },
  computed:{
    ...mapGetters({
      state: 'Cart/getState',
      items: 'Cart/getItems',
      loading: 'Cart/getLoading',
      errors: 'Cart/getErrors',
    }),
    completePrice () {
      this.loading
      let res = 0;
      for(let i = 0; i < this.items.length; i++){
        res += this.items[i].product.price * this.items[i].quantity
      }
      return res.toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2});
    }
  },
  watch: {
      state(newVal){
        this.currentState = newVal
      }
  },
  mixins:[Image],
  methods: {
    onChangeQuantity(productId, quantity){
      this.$store.dispatch('Cart/add', {productId, quantity: parseInt(quantity), set:true})
    },
    onCloseModal(){
      this.$store.dispatch('Cart/closeCart')
    },
    removeCartItem(productId){
      this.$store.dispatch('Cart/remove', productId)
    }
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
.removeProduct{
  position:absolute;
  top:0;
  right:0;
  padding-right:0.3em;
  padding-left:0.3em;
  cursor: pointer;
  color:$danger;
  transition: 0.3s;
  z-index:2;
}
.removeProduct:hover{
  color: darken($danger, 30%)
}
</style>
