<template>
  <div class="mb-2 position-relative">
    <div style="margin-top:-80px;height:500px;clip-path:inset(0);" class="d-flex justify-content-center align-items-center position-relative">
      <img
        v-if="category.calculatedImages"
        :src="imgPath + 'img/uploads/'+getOrgImage(category.calculatedImages)"
        :srcset="getImgSrcSet(imgPath + 'img/uploads/', category.calculatedImages)"
        :sizes="getSizes(100, 100, 100 ,100)"
        :alt="category.name + '-Thumbnail'"
        class="w-100 cover"
        height="500px"
      >
      <div class="position-absolute d-flex justify-content-center align-items-center flex-column">
        <h1 style="font-size:4em;" class="text-primary font-weight-bold text-outline text-uppercase text-center">{{ category.name }}</h1>
        <div class="font-weight-bold text-uppercase CategoryDescription" v-if="category.description">{{ category.description }}</div>
      </div>
    </div>
    <div class="w-100 p-4 d-flex justify-content-between align-items-center flex-wrap">
      <div>
        <span v-for="(cat,index) in parentCategories" :key="'category'+index">
          <NuxtLink :to="localePath('/Category/'+encodeURI(cat.name))"  :title="cat.name" >{{ cat.name }}</NuxtLink><span class="text-primary pl-1 pr-1" v-if="index !== parentCategories.length-1">/</span>
        </span>
      </div>
      <div class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="d-flex justify-content-center align-items-center flex-nowrap">
          <b-form-input v-model="search" :placeholder="$t('product-listing.search.placeholder')" :title="$t('product-listing.search.title')"></b-form-input>
          <b-button variant="secondary" :title="$t('product-listing.search.title')"><fa icon="search" /></b-button>
        </div>
      </div>
    </div>

    <div class="row m-0 p-0">
      <div class="col-12 col-lg-3 m-0 p-0 flex-grow-1">
        <side-menu :route="localePath('/Category')" :entries="categoriesSort" classes="card shadow align-self-start m-2" />
      </div>
      <div class="col-12 col-lg-9">
        <div v-if="!paginationLoading" class="w-100 d-flex justify-content-center align-items-center flex-wrap">
          <ProductBox
            v-for="product in products"
            :key="'product-box'+product.id"
            :product="product"
            :show-share="false"
            classes=""
          />
          <div v-if="products.length == 0">
            {{$t('product-listing.noProductsFound')}}
          </div>
        </div>
        <div v-else class="w-100 d-flex justify-content-center align-items-center">
          <b-spinner variant="primary" size="lg" ></b-spinner>
        </div>
      </div>
    </div>
    <div class="w-100 d-flex justify-content-center align-items-center" v-if="pageNumbers !== 0">
      <b-button
        variant="white"
        :disabled="currentPage == 1"
        class="paginationItem"
        @click="$router.push(localePath('/Category/'+encodeURI(category.name)+'?page='+(parseInt(currentPage)-1)))"
      >
        <
      </b-button>
      <b-button
        variant="white"
        v-for="i in pageNumbers"
        :key="'pagination'+i"
        @click="$router.push(localePath('/Category/'+encodeURI(category.name)+'?page='+i))"
        :class="(currentPage == i ? 'paginationItemActive ' : '' )+'paginationItem'">
        {{ i }}
      </b-button>
      <b-button
        variant="white"
        :disabled="currentPage == pageNumbers || pageNumbers == 0"
        class="paginationItem"
        @click="$router.push(localePath('/Category/'+encodeURI(category.name)+'?page='+(parseInt(currentPage)+1)))"
      >
        >
      </b-button>
    </div>
  </div>
</template>
<script>
import {mapGetters} from "vuex";
import Image from "~/mixins/Image/image";
import ProductBox from "~/components/Product/box";
import SideMenu from "@/components/Menu/SideMenu";
export default {
  components: {ProductBox, SideMenu},
  data () {
    return {
      category: {},
      limit: 9,
      offset: 0,
      search: '',
      paginationLoading: false
    }
  },
  head() {
    const i18nHead = this.$nuxtI18nHead({ addSeoAttributes: true })
    const links = [];
    for(let i = 0; i <this.$i18n.locales.length; i++){
      links.push({
        hid: 'i18n-alt-'+this.$i18n.locales[i].code,
        rel: 'alternate',
        href:this.localePath('/Category/' + encodeURIComponent(this.category.translations?.name[this.$i18n.locales[i].iso] ?? ''), this.$i18n.locales[i].code),
        hreflang: this.$i18n.locales[i].code
      })
      links.push({
        hid: 'i18n-alt-'+this.$i18n.locales[i].iso,
        rel: 'alternate',
        href:this.localePath('/Category/' + encodeURIComponent(this.category.translations?.name[this.$i18n.locales[i].iso] ?? ''), this.$i18n.locales[i].code),
        hreflang: this.$i18n.locales[i].iso
      })
      if(this.$i18n.locales[i].iso === 'en-EN') {
        links.push({
          hid: 'i18n-xd',
          rel: 'alternate',
          href: this.localePath('/Category/' + encodeURIComponent(this.category.translations?.name[this.$i18n.locales[i].iso] ?? ''), this.$i18n.locales[i].code),
          hreflang: 'x-default'
        })
      }
      if(this.$i18n.locale === this.$i18n.locales[i].code) {
        links.push({
          hid: 'i18n-can',
          rel: 'canonical',
          href: this.localePath('/Category/' + encodeURIComponent(this.category.translations?.name[this.$i18n.locales[i].iso] ?? ''), this.$i18n.locales[i].code),
        })
      }
    }
    return {
      title: this.category?.name,
      htmlAttrs: i18nHead.htmlAttrs,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.category?.description
        },
        ...i18nHead.meta
      ],
      link: [
        ...links
      ]
    }
  },
  mixins:[Image],
  watch:{
    page (newVal) {
      if(newVal !== this.currenPage){
        this.currentPage = newVal
        this.paginationLoading = true
        this.$store.dispatch('Product/getByCategoryId',
          {
            fetchCategoryId: this.category.id,
            offset: ((this.currentPage-1) * this.productsPerPage),
            limit: this.productsPerPage
          }).then(r => {
            this.products = r
            this.paginationLoading = false
          })
      }
    }
  },
  computed: {
    ...mapGetters({
      categories: 'Categories/getCategories',
      categoriesSort: 'Categories/getCategoriesSorted',
    }),
    page () {
      return this.$route.query.page || 1
    },
    pageNumbers () {
      return Math.ceil(this.totalProductsCount / this.productsPerPage)
    },
    parentCategories(){
      const names = [];
      const category = this.category;
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
    let categoryId = route.params.id;
    let currentPage = route.query.page ?? 1;
    let productsPerPage = 9;

    let category = store.getters['Categories/getCategories']

    if(typeof category == 'undefined' || !category || category.length === 0 || typeof categoryId == 'undefined') {
      error({ statusCode: 404, message: 'Category not found' })
    }else{
      if(categoryId) {
        if(!isNaN(categoryId))
          category = category.find(e => e.id == categoryId)
        else
          category = category.find(e => e.name == decodeURI(categoryId))
      }
      else {
        error({ statusCode: 404, message: 'Category not found' })
      }
      const totalProductsCount = await store.dispatch('Product/getTotalProductCountByCategoryId', {fetchInactive: 0, categoryId: category.id})
      const products = JSON.parse(JSON.stringify(
        await store.dispatch('Product/getByCategoryId', {fetchCategoryId: category.id, offset: ((currentPage-1) * productsPerPage), limit: productsPerPage})
      ))
      return {
        category,
        products,
        totalProductsCount,
        currentPage,
        productsPerPage
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
