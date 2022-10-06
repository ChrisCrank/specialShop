<template>
  <div class="d-flex flex-column justify-content-center align-items-center">
    <div class="col-12 col-md-8">
      <div>
        <span v-for="(cat,index) in parentCategories" :key="'category'+index">
          <NuxtLink :to="localePath('/Category/'+encodeURI(cat.name))"  :title="cat.name" >{{ cat.name }}</NuxtLink><span class="text-primary pl-1 pr-1" v-if="index !== parentCategories.length-1">/</span>
        </span>
      </div>
      <div class="col-12 row">
        <h1 class="text-primary font-weight-bold">{{ product.name }}</h1>
      </div>
      <div class="col-12 row d-flex align-items-end">
        <div class="col-12 col-md-6">
          <img
            @click="openImagePrev(imgPath + 'img/uploads/'+getOrgImage(product.calculatedImages), product.shortDescription)"
            v-if="product.calculatedImages"
            :src="imgPath + 'img/uploads/'+getOrgImage(product.calculatedImages)"
            :srcset="getImgSrcSet(imgPath + 'img/uploads/', product.calculatedImages)"
            :sizes="getSizes(100, 100, 50 ,50)"
            :alt="product.name + '-Thumbnail'"
            class="w-100 cursorPointer"
          >
        </div>
        <div class="col-12 col-md-6 d-flex flex-column h-100 mt-4 mt-md-0">
          <div class="col-12">
            <div class="row mt-2">
              <div class="col-6">
                {{ $t('product-detail.number') }}
              </div>
              <div class="col-6">
                <b>{{ product.productNumber }}</b>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                {{ $t('product-detail.quantity') }}
              </div>
              <div class="col-6">
                <b-form-input
                  id="quantity"
                  v-model="quantity"
                  min="0"
                  :max="product.stock"
                  :state="isQuantityValid"
                  aria-describedby="quantity-help quantity-feedback"
                  type="number"
                />
                <b-form-invalid-feedback id="quantity-feedback">
                  {{ $t('product-detail.quantity-error') }}
                </b-form-invalid-feedback>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-6">
                {{ $t('product-detail.stock') }}
              </div>
              <div class="col-6">
                <b :class="product.stock < 10 ? 'text-danger' : ''">{{ product.stock }}</b> {{ $t('product-detail.unit') }}
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-6">
                {{ $t('product-detail.price') }}
              </div>
              <div class="col-6">
                <b>{{ product.price.toLocaleString('de-DE', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2}) }}</b>
              </div>
            </div>
          </div>
          <div class="col-12 mt-4">
            <addToCartBtn
              :quantity="quantity"
              :product-id="product.id"
              :stock="product.stock"
            />
          </div>
        </div>
      </div>
      <hr class="w-100">
      <div class="col-12 row mt-4 mt-4"  v-html="product.description"></div>
    </div>
  </div>
</template>
<script>
import {mapGetters} from "vuex";
import Image from "~/mixins/Image/image";
import ImagePrev from "~/mixins/Image/imagePrev";
import addToCartBtn from "@/components/Product/addToCartBtn";
export default {
  data () {
    return {
      quantity: 1
    }
  },
  head() {
    const i18nHead = this.$nuxtI18nHead({ addSeoAttributes: true })
    const links = [];
    for(let i = 0; i <this.$i18n.locales.length; i++){
      links.push({
        hid: 'i18n-alt-'+this.$i18n.locales[i].code,
        rel: 'alternate',
        href:this.localePath('/Product/' + encodeURIComponent(this.product.translations?.name[this.$i18n.locales[i].iso] ?? ''), this.$i18n.locales[i].code),
        hreflang: this.$i18n.locales[i].code
      })
      links.push({
        hid: 'i18n-alt-'+this.$i18n.locales[i].iso,
        rel: 'alternate',
        href:this.localePath('/Product/' + encodeURIComponent(this.product.translations?.name[this.$i18n.locales[i].iso] ?? ''), this.$i18n.locales[i].code),
        hreflang: this.$i18n.locales[i].iso
      })
      if(this.$i18n.locales[i].iso === 'en-EN') {
        links.push({
          hid: 'i18n-xd',
          rel: 'alternate',
          href: this.localePath('/Product/' + encodeURIComponent(this.product.translations?.name[this.$i18n.locales[i].iso] ?? ''), this.$i18n.locales[i].code),
          hreflang: 'x-default'
        })
      }
      if(this.$i18n.locale === this.$i18n.locales[i].code) {
        links.push({
          hid: 'i18n-can',
          rel: 'canonical',
          href: this.localePath('/Product/' + encodeURIComponent(this.product.translations?.name[this.$i18n.locales[i].iso] ?? ''), this.$i18n.locales[i].code),
        })
      }
    }
    return {
      title: this.product?.name,
      htmlAttrs: i18nHead.htmlAttrs,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.product?.shortDescription
        },
        ...i18nHead.meta
      ],
      link: [
        ...links
      ]
    }
  },
  mixins:[Image, ImagePrev],
  components: {
    addToCartBtn
  },
  computed: {
    ...mapGetters({
      categories: 'Categories/getCategories',
      categoriesSort: 'Categories/getCategoriesSorted',
    }),
    isQuantityValid () {
      if(!(!isNaN(this.quantity) && this.quantity >= 1 && this.quantity <= this.product.stock))
        return false;
      return null;
    },
    parentCategories(){
      const names = [];
      const category = this.productCategory;
      const path = category?.pathArr ?? []
      for(let i = 0; i < path.length; i++){
        const el = this.categories.find(e => e.id == path[i])
        const name = el?.name ?? ''
        const id = el?.id ?? ''
        if(name.length)
          names.push({name: name, id:id})
      }
      names.push({name: category?.name, id:category?.id})

      return names
    }
  },
  async asyncData({app, store, route, redirect, error}) {
    let product = await store.dispatch('Product/getById', {id:route.params.id  ,fetchInactive: 0, fetchTranslations: 0})
    let productCategory = await store.dispatch('Categories/getById', {id: product.categories?.[0], fetchInactive: 0, fetchTranslations: 0})
    if(typeof product == 'undefined' || !product || product.length === 0) {
      error({ statusCode: 404, message: 'Producft not found' })
    }else{
      return {
        product,
        productCategory
      };
    }
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
  .cover{
    object-fit: cover;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
  }
  .CategoryDescription{
    background-color:rgba(0,0,0,.5);
    padding:0.5em;
    border-radius: 10px;
    color:white;
  }
  .paginationItem{
    padding:0.6em;
    border:1px solid $secondary;
    color:$primary;
    transition:0.3s;
    cursor:pointer;
  }
  .paginationItem:focus,.paginationItemActive:focus{
    outline:none !important;
    box-shadow:none !important;
  }
  .paginationItem:hover{
    color:$white;
    background-color:$primary;
  }
  .paginationItemActive{
    color:$white !important;
    background-color:$primary !important;
    font-weight:bold;
  }
</style>
