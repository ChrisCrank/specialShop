<template>
  <div class="position-relative p-4">
    <caption-title :text="$t('product-edit.title', {name: product ? product.name : ''})"/>
    <div class="container">
      <b-button variant="primary" @click="$router.push(localePath('/Portal/Product/'))">Back</b-button>
    </div>
    <div v-if="!success" class="container card shadow mt-4 mb-4">
      <ValidationObserver ref="observer" v-slot="{ passes }" tag="div">
        <form v-if="productsLoaded" @submit.prevent="passes(onEditProduct)" @reset="e => {e.preventDefault()}">
          <div class="form-group row mb-3">
            <div class="col-12 col-md-6">
              <span class="text-muted mb-1">{{ $t('product-add.sort.title') }}</span><span class="text-primary">*</span>
              <ValidatedTextInput
                v-model="product.sort"
                type="number"
                rules="numeric|required"
                :placeholder="$t('product-add.sort.title')"
                :name="$t('product-add.sort.title')"
                classes="p-0"
                input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
            </div>
            <div class="col-12 col-md-6">
              <span class="text-muted mb-1">{{ $t('product-add.parent.title') }}</span>
              <ValidatedSelectInput
                v-model="product.categories"
                :multiple="true"
                rules=""
                :placeholder="$t('product-add.parent.title')"
                classes="col-12 p-0"
                input-classes="form-control rounded border-1 shadow-sm px-4 text-primary" >
                <template #default>
                  <b-form-select-option v-for="cat in availableCategories" :key="'Product'+cat.value" :value="cat.value">{{ cat.text }}</b-form-select-option>
                </template>
              </ValidatedSelectInput>
            </div>
          </div>
          <div class="form-group row mb-3">
            <div class="col-12 col-md-4">
              <span class="text-muted mb-1">{{ $t('product-add.stock.title') }}</span><span class="text-primary">*</span>
              <ValidatedTextInput
                v-model="product.stock"
                type="number"
                rules="numeric|required"
                :placeholder="$t('product-add.stock.title')"
                :name="$t('product-add.stock.title')"
                classes="p-0"
                input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
            </div>
            <div class="col-12 col-md-4">
              <span class="text-muted mb-1">{{ $t('product-add.price.title') }}</span><span class="text-primary">*</span>
              <ValidatedTextInput
                v-model="product.price"
                type="number"
                rules="decimal:2|min:1|required"
                step=".01"
                :placeholder="$t('product-add.price.title')"
                :name="$t('product-add.price.title')"
                classes="p-0"
                input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
            </div>
            <div class="col-12 col-md-4">
              <span class="text-muted mb-1">{{ $t('product-add.productNumber.title') }}</span><span class="text-primary">*</span>
              <ValidatedTextInput
                v-model="product.productNumber"
                type="text"
                rules="min:3|required"
                :placeholder="$t('product-add.productNumber.placeholder')"
                :name="$t('product-add.productNumber.title')"
                classes="p-0"
                input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
            </div>
          </div>

          <div class="form-group row mb-3">
            <div class="col-12 col-md-6">
              <span class="text-muted mb-1">{{ $t('product-add.thumbnail.title') }}</span><span class="text-primary">*</span>
              <div class="position-relative" v-if="product.calculatedImages && product.calculatedImages.length">
                <b-button class="position-absolute" variant="danger" @click="product.calculatedImages = []">use Custom</b-button>
                <img
                  :src="imgPath + 'img/uploads/'+getOrgImage(product.calculatedImages)"
                  :srcset="getImgSrcSet(imgPath + 'img/uploads/', product.calculatedImages)"
                  :sizes="getSizes(100, 50, 50 ,50)"
                  width="500"
                  style="width:100%;"
                  alt=""
                >
              </div>
              <ValidatedTextInput
                v-else
                :id="'fileInput'"
                type="file"
                accept="image/png, image/jpeg, image/jpg, image/gif"
                :rules="'required'"
                classes="p-0"
                input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
            </div>
            <div class="col-12 col-md-3">
              <span class="text-muted mb-1">{{ $t('product-add.active.title') }}</span>
              <ValidatedCheckboxInput
                v-model="product.active"
                :placeholder="$t('product-add.active.title')"
                :name="$t('product-add.active.title')"
                :value="1"
                :unchecked-value="0"
                classes="p-0"
                input-classes="form-control px-4 text-primary" />
            </div>
          </div>
          <b-tabs content-class="mt-3">
            <b-tab :active="(locale.iso === 'en-EN')" v-for="locale in $i18n.locales" :key="'locale'+locale.shortName">
              <template #title>
                <img :src="'/lang/' + locale.shortName + '.png'" alt=""> {{ locale.shortName }} ({{locale.name}})
              </template>
              <div class="form-group row mb-3">
                <div class="col-12">
                  <span class="text-muted mb-1">{{ $t('product-add.name.title') }}</span><span v-if="locale.iso === 'en-EN'" class="text-primary">*</span>
                  <ValidatedTextInput
                    v-model="product.translations.name[locale.iso]"
                    type="text"
                    :rules="'max:100|' + (locale.iso === 'en-EN' ? 'min:3|required' : '')"
                    :placeholder="$t('product-add.name.placeholder')"
                    :name="$t('product-add.name.title') + '('+locale.iso+')'"
                    classes="p-0"
                    input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                </div>
                <div class="col-12">
                  <span class="text-muted mb-1">{{ $t('product-add.description.title') }}</span><span v-if="locale.iso === 'en-EN'" class="text-primary">*</span>
                  <ValidatedTextAreaInput
                    v-model="product.translations.shortDescription[locale.iso]"
                    :placeholder="$t('product-add.shortDescription.placeholder')"
                    :name="$t('product-add.shortDescription.title') + '('+locale.iso+')'"
                    :rows="4"
                    :rules="'max:255|' + (locale.iso === 'en-EN' ? 'min:3|required' : '' )"
                    classes="p-0"
                    input-classes="form-control border-1 shadow-sm px-4 text-primary"
                  />
                </div>
                <div class="col-12">
                  <span class="text-muted mb-1">{{ $t('product-add.description.title') }}</span><span v-if="locale.iso === 'en-EN'" class="text-primary">*</span>
                  <client-only placeholder="Loading...">
                    <Editor v-model="product.translations.description[locale.iso]"></Editor>
                  </client-only>
                </div>
              </div>
            </b-tab>
          </b-tabs>
          <hr>
          <div class="w-100 d-flex justify-content-end pb-2">
            <b-button :disabled="errors && errors.length !== 0 || !pageLoaded || sending"
                      type="submit"
                      class="d-flex justify-content-center align-items-center ml-2">
              {{ $t('category-edit.edit') }}
              <b-spinner v-if="!pageLoaded || sending" class="ml-1" variant="light" small label="Spinning"></b-spinner>
            </b-button>
          </div>
          <div class="d-flex flex-column align-self-center" v-if="errors">
            <div class="text-danger d-flex justify-content-center"
                 v-for="(error, key) in errors"
                 :key="error"
                 v-if="key <= 3"
            >{{ key < 3 ? error : '...' }}</div>
          </div>
        </form>
      </ValidationObserver>
    </div>
    <div v-else class="container mt-4 mb-4 card p-4">
      <b-alert variant="success" show>
        {{ $t('product-edit.success') }}
        <b-button variant="primary" @click="$router.push(localePath('/Portal/Product/'))">Go Back</b-button>
      </b-alert>
    </div>
    <div v-if="bError" class="container mt-4 mb-4 card p-4">
      <b-alert variant="danger" show>
        {{ bError }}
        <b-button variant="primary" @click="$router.push(localePath('/Portal/Product/'))">Go Back</b-button>
      </b-alert>
    </div>
  </div>
</template>
<script>
import adminAuthRequiredPage from "@/mixins/Page/adminAuthRequiredPage";
import captionTitle from "@/components/Theme/Caption/caption";
import ValidationForm from "@/mixins/Form/validationForm";
import product from "~/mixins/Portal/product";
import Editor from "@/components/HtmlEditor/Editor";
import Image from "@/mixins/Image/image";
export default {
  mixins: [adminAuthRequiredPage, ValidationForm,product, Image],
  components: {
    captionTitle,
    Editor
  },
  layout: 'admin',

  data () {
    return {
      product: null,
      productsLoaded: false,
      sending:false,
      success: false,
      bError: null
    }
  },
  methods: {
    onEditProduct(){
      this.bError = null;
      this.success = false
      this.sending = true;
      let formData = new FormData();
      formData.append('id', this.product.id)
      formData.append('sort', this.product.sort)
      formData.append('stock', this.product.stock)
      formData.append('price', this.product.price)
      formData.append('productNumber', this.product.productNumber)
      formData.append('categories', this.product.categories)
      formData.append('active', this.product.active)
      for(let i = 0; i < this.$i18n.locales.length; i++){
        formData.append('name['+this.$i18n.locales[i].iso+']', this.product.translations.name[this.$i18n.locales[i].iso] ?? null)
        formData.append('description['+this.$i18n.locales[i].iso+']', this.product.translations.description[this.$i18n.locales[i].iso] ?? null)
        formData.append('shortDescription['+this.$i18n.locales[i].iso+']', this.product.translations.shortDescription[this.$i18n.locales[i].iso] ?? null)
      }

      if(this.product.calculatedImages.length === 0)
        formData.append('img', document.getElementById('fileInput')?.files[0])

      this.$axios.post(process.env.baseUrl + 'Admin/Product/edit',formData,{
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'multipart/form-data'
        }
      }).then(r => {
        if(r?.data.success){
          // remove all Products from store so they get freshly loaded with updated date in the backend
          this.$store.dispatch('Product/clearProductStore')
          this.success = true;
        }else{
          if(r?.data?.codes?.includes('F1'))
            this.bError = this.$t('product-add.errors.F1');
          else if(r?.data?.codes?.includes('F2'))
            this.bError = this.$t('product-add.errors.F2');
          else if(r?.data?.codes?.includes('F3'))
            this.bError = this.$t('product-add.errors.F3');
          else if(r?.data?.codes?.includes('F4'))
            this.bError = this.$t('product-add.errors.F4');
          else if(r?.data?.codes?.includes('F5'))
            this.bError = this.$t('product-add.errors.F5', {language: r?.data?.language});
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
    this.product = await this.$store.dispatch('Product/getById', {id: this.$route.params.productid, fetchInactive: 1, fetchTranslations: 1});
    if(typeof this.product == 'undefined')
      this.$router.push(this.localePath('/Portal/Product'))
    else
      this.product = JSON.parse(JSON.stringify(this.product))
    this.productsLoaded = true
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';

</style>
