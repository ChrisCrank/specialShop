<template>
  <div :class="'overflow-hidden w-100 position-relative ' + $props.classes">
    <div v-if="products.length > 1" class="customProductSliderBtn customProductSliderBtnL" @click="slideD('l')"><fa icon="angle-left"></fa></div>
    <div v-if="products.length > 1" class="customProductSliderBtn customProductSliderBtnR" @click="slideD('r')"><fa icon="angle-right"></fa></div>
    <div class="w-100 d-flex flex-row flex-nowrap minWIdth100Vw mb-3">
      <product-box
        v-for="(product, index) in products"
        :key="product.id"
        :id="index === 0 ? 'firstSliderElem' : ''"
        :product="product"
        classes="d-flex justify-content-center align-items-center minWIdth100Vw sliderElem"
      />
    </div>
    <div class="customProductSliderBtnStep" v-if="products.length > 1">
        <div :class="'sliderBtnItem ' + ((slide === index) ? 'sliderBtnItemActive' : '')" v-for="(product, index) in products" :key="product.id" @click="slideStep(index)"></div>
    </div>
  </div>
</template>
<script>
import Image from "@/mixins/Image/image";
import ProductBox from "@/components/Product/box";
import {mapGetters} from "vuex";

export default  {
  props: {
    products: {
      type: Array,
      required: false
    },
    classes: {
      type: String
    },
  },
  mixins:[Image],
  components: {
    ProductBox
  },
  data () {
    return {
      slide: 0
    }
  },
  methods: {
    slideStep(step){
      this.slide = step
      document.getElementById('firstSliderElem').style.marginLeft = '-'+ (100 * this.slide) +'vw'
    },
    slideD(direction){
      if(direction === 'r') {
        if(this.slide < this.products.length-1)
          this.slide += 1
        else
          this.slide = 0
      }else{
        if(this.slide > 0)
          this.slide -= 1
        else
          this.slide = this.products.length-1
      }
      document.getElementById('firstSliderElem').style.marginLeft = '-'+ (100 * this.slide) +'vw'
    }
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
.customProductSliderBtnStep{
  width:100%;
  position:absolute;
  bottom:0;
  left:0;
  display:flex;
  justify-content: center;
  align-items:center;
  .sliderBtnItem{
    width:3em;
    height:0.5em;
    background-color:$primary;
    transition:0.5s;
    cursor:pointer;
    margin-right:0.5em;
    margin-left:0.5em;
  }
  .sliderBtnItem:hover{
    background-color: darken($primary, 30%);
  }
  .sliderBtnItemActive{
    background-color: darken($primary, 30%);
  }
}
.customProductSliderBtnL{
  left:0;
}
.customProductSliderBtnR{
  right:0;
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
.sliderElem{
  transition:1s;
}
.minWIdth100Vw{
  min-width:100vw;
  max-width:100vw;
  width:100vw;
}
</style>
