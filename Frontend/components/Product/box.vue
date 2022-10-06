<template>
  <div :class="$props.classes " :id="$props.id">
    <div class="h-100 card shadow myCustomCard m-2 overflow-hidden">
      <div class="d-flex flex-column justify-content-center align-items-center">
        <NuxtLink v-if="productCategory" :to="localePath('/Category/'+encodeURI(productCategory.name))" class="myCustomCategory mt-3 mb-3">
          {{ productCategory.name }}
        </NuxtLink>
      </div>
      <div class="d-flex justify-content-center align-items-center" @click="$router.push(localePath('/Product/'+encodeURI($props.product.name)))">
        <img
          v-if="$props.product.calculatedImages"
          :src="imgPath + 'img/uploads/'+getOrgImage($props.product.calculatedImages)"
          :srcset="getImgSrcSet(imgPath + 'img/uploads/', $props.product.calculatedImages)"
          :sizes="getSizes(100, 50, 50 ,50)"
          class=""
          height="200px"
          :alt="$props.product.name + '-Thumbnail'"
        >
      </div>
      <div class="p-4 text-center mt-2 d-flex justify-content-between align-items-center flex-column h-100">
        <div class="d-flex flex-column w-100">
          <NuxtLink :to="localePath('/Product/'+encodeURI($props.product.name))" class="h3 text-primary font-weight-bold text-decoration-none">
            {{ $props.product.name }}
          </NuxtLink>
          <div class="w-100 d-flex justify-content-between align-items-center">
            <h4><b>{{$props.product.price.toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2})}}</b></h4>
          </div>
          <div class="mt-2 mb-2" style="height:50px;">
            {{$props.product.shortDescription}}
          </div>
        </div>
        <div class="d-flex flex-column w-100">
          <addToCartBtn
            :quantity="1"
            :productId="$props.product.id"
            :stock="$props.product.stock"
          />
          <template v-if="$props.showShare">
          <hr class="w-100">
            <div class="text-uppercase text-muted">{{ $tc('product-detail.share') }}</div>
            <div class="w-100 d-flex justify-content-center align-items-center flex-row flex-nowrap">
              <b-icon class="m-3 customShareIcons " icon="facebook" font-scale="1.5"></b-icon>
              <b-icon class="m-3 customShareIcons " icon="twitter" font-scale="1.5"></b-icon>
              <b-icon class="m-3 customShareIcons " icon="instagram" font-scale="1.5"></b-icon>
              <b-icon class="m-3 customShareIcons " icon="link" font-scale="1.5"></b-icon>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Image from "@/mixins/Image/image";
import addToCartBtn from "@/components/Product/addToCartBtn";
import {mapGetters} from "vuex";

export default {
  props: {
    id: {
      type: String,
      required: false
    },
    product: {
      type: Object,
      required: true
    },
    showShare: {
      type: Boolean,
      required: false,
      default: () => true
    },
    classes: {
      type: String,
      required: false,
      default: () => ''
    },
  },
  mixins:[Image],
  components: {
    addToCartBtn
  },
  computed: {
    ...mapGetters({
      Categories: 'Categories/getCategories'
    }),
    productCategory(){
      return this.Categories?.find(e => e.id === this.product?.categories?.[0])
    }
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
.myCustomCard{
  min-width:400px !important;
  transition:border 0.2s, filter 0.5s;
  cursor:pointer;
  border:1px solid $gray-400 !important;
  border-radius: 15px;
}
.myCustomCard:hover{
  border:1px solid $primary !important;
  z-index:5;
}
.customProductSliderBtn{
  position:absolute;
  top:50%;
  transform: translateY(-50%);
  color:$primary;
  z-index:100;
  cursor:pointer;
  transition:0.5s;
  padding:0.5em;
  font-size:3em;
}
.customProductSliderBtn:hover{
  color: darken($primary, 30%);
}
.myCustomCategory{
  position:relative;
  display:flex;
  justify-content: center;
  align-items:center;
  text-transform:uppercase;
  letter-spacing: 3px;
  color:$gray-300;
  text-decoration: none;
  cursor:pointer;
  transition:0.3s;
  &:before{
    position: absolute;
    top: 50%;
    left: -120px;
    right: 0;
    bottom: 0;
    content: '';
    width: 100px;
    height: 1px;
    background: rgba(0, 0, 0, 0.08)
  }
  &:after{
    position: absolute;
    top: 50%;
    right: -120px;
    bottom: 0;
    content: '';
    width: 100px;
    height: 1px;
    background: rgba(0, 0, 0, 0.09);
  }
}
.myCustomCategory:hover{
  color:$gray-600;
  text-decoration: none;
}
</style>
