<template>
  <div class="position-relative p-4">
    <caption-title :text="$t('product-delete.title', {name: product ? product.name: ''})" />
    <div class="container">
      <b-button variant="primary" @click="$router.push(localePath('/Portal/Product'))">Back</b-button>
    </div>
    <div v-if="!success" class="container card shadow mt-4 mb-4 w-50">
      <ValidationObserver ref="observer" v-slot="{ passes }" tag="div">
        <form v-if="pageLoaded" @submit.prevent="passes(onDeleteProduct)" @reset="e => {e.preventDefault()}">
          <div class="container">
            <h3>
              <i18n :path="'product-delete.title'" tag="span">
                <template v-slot:name>
                  <b>{{ product ? product.name: '' }}</b>
                </template>
              </i18n>
            </h3>
            <div class="d-flex justify-content-center align-items-center mt-3 mb-3 flex-column">
              <i18n :path="'product-delete.description'" tag="div" class="w-50">
                <template v-slot:name>
                  <b>{{ product ? product.name: '' }}</b>
                </template>
              </i18n>
              <img
                v-if="product && product.calculatedImages"
                :src="imgPath + 'img/uploads/'+getOrgImage(product.calculatedImages)"
                :srcset="getImgSrcSet(imgPath + 'img/uploads/', product.calculatedImages)"
                :sizes="getSizes(30, 10, 10 ,10)"
                class="customCardImg"
                height="100px"
                :alt="product.name + '-Thumbnail'"
              >
            </div>
          </div>
          <hr>
          <div class="w-100 d-flex justify-content-end pb-2">
            <b-button variant="secondary" @click="$router.push(localePath('/Portal/Product'))">{{ $t('product-delete.abort') }}</b-button>
            <b-button :disabled="errors && errors.length !== 0 || !pageLoaded || sending"
                    type="submit"
                    variant="danger"
                    class="d-flex justify-content-center align-items-center ml-2">
              {{ $t('product-delete.delete') }}
              <b-spinner v-if="!pageLoaded || sending" class="ml-1" variant="light" small label="Spinning"></b-spinner>
            </b-button>
          </div>
        </form>
      </ValidationObserver>
    </div>
    <div v-else class="container mt-4 mb-4 card p-4">
      <b-alert variant="success" show>
        {{ $t('product-delete.success') }}
        <b-button variant="primary" @click="$router.push(localePath('/Portal/Product'))">Go Back</b-button>
      </b-alert>
    </div>
    <div v-if="bError" class="container mt-4 mb-4 card p-4">
      <b-alert variant="danger" show>
        {{ bError }}
        <b-button variant="primary" @click="$router.push(localePath('/Portal/Product'))">Go Back</b-button>
      </b-alert>
    </div>
  </div>
</template>
<script>
import adminAuthRequiredPage from "@/mixins/Page/adminAuthRequiredPage";
import captionTitle from "@/components/Theme/Caption/caption";
import ValidationForm from "@/mixins/Form/validationForm";
import {mapGetters} from "vuex";
import product from "~/mixins/Portal/product";
import Image from "@/mixins/Image/image";

export default {
  mixins: [adminAuthRequiredPage, ValidationForm,product,Image],
  components: {captionTitle},
  layout: 'admin',

  data () {
    return {
      sending:false,
      success: false,
      bError: null,
      product: {}
    }
  },
  methods: {
    onDeleteProduct(){
      this.sending = true;
      this.$axios.delete(process.env.baseUrl + 'Admin/Product/delete/'+this.product.id).then(r => {
        this.sending = true
        if(r?.data.success){
          this.success = true;
          this.$store.dispatch('Product/clearProductStore')
        }else{
          if(r?.data?.codes?.includes('F3'))
            this.bError = this.$t('category-product-delete.errors.F3');
          else
            this.bError = this.$t('admin.errors.general')
        }

        this.sending = false;
      }).catch(e => {
        this.sending = false;
        console.log(e)
      })
    }
  },

  async mounted () {
    // load category by Id
    this.product = await this.$store.dispatch('Product/getById', {id: this.$route.params.productid, fetchInactive: 1, fetchTranslations: 1});
    if(typeof this.product == 'undefined')
      this.$router.push(this.localePath('/Portal/Product'))
    else
      this.product = JSON.parse(JSON.stringify(this.product))
    this.productsLoaded = true
  }
}
</script>
<style>

</style>
