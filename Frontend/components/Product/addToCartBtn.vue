<template>
  <b-button
    variant="primary"
    class=" text-uppercase w-100"
    @click="addToCart"
    :disabled="$props.quantity > $props.stock"
    :title="$t('cart.outOfStock')"
  >{{ $tc('product-detail.addToCart') }}</b-button>
</template>
<script>
export default {
  props:{
    quantity: {
      type: Number,
      required: true
    },
    stock: {
      type: Number,
      required: true
    },
    productId: {
      type: String|Number,
      required: true
    }
  },
  methods: {
    addToCart(){
      this.$store.dispatch('Cart/openCart')
      this.$store.dispatch('Cart/add', {productId: this.$props.productId,quantity: parseInt(this.$props.quantity), set:false})
    }
  }
}
</script>
