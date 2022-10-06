<template>
  <div class="position-relative p-4">
    <caption-title :text="$t('product-add.title')"/>
    <div class="container">
      <b-button variant="primary" @click="$router.push(productOverviewUrl)">Back</b-button>
    </div>
    <div v-if="!success" class="container card shadow mt-4 mb-4">
      <ValidationObserver ref="observer" v-slot="{ passes }" tag="div">
        <form v-if="pageLoaded" @submit.prevent="passes(onaddProduct)" @reset="e => {e.preventDefault()}">
          <div class="form-group row mb-3">
            <div class="col-12 col-md-6">
              <span class="text-muted mb-1">{{ $t('product-add.sort.title') }}</span><span class="text-primary">*</span>
              <ValidatedTextInput
                v-model="sort"
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
                v-model="parentCategories"
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
                v-model="stock"
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
                v-model="price"
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
                v-model="productNumber"
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
              <span class="text-muted mb-1">{{ $t('product-add.thumbnail.title') }}</span><span class="text-primary">*</span>,
              <ValidatedTextInput
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
                v-model="active"
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
                    v-model="name[locale.iso]"
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
                    v-model="shortDescription[locale.iso]"
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
                    <Editor v-model="description[locale.iso]"></Editor>
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
              {{ $t('category-add.add') }}
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
        {{ $t('product-add.success') }}
        <b-button variant="primary" @click="$router.push(productOverviewUrl)">Go Back</b-button>
      </b-alert>
    </div>
    <div v-if="bError" class="container mt-4 mb-4 card p-4">
      <b-alert variant="danger" show>
        {{ bError }}
        <b-button variant="primary" @click="$router.push(productOverviewUrl)">Go Back</b-button>
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
export default {
  mixins: [adminAuthRequiredPage, ValidationForm,product],
  components: {
    captionTitle,
    Editor
  },
  layout: 'admin',

  data () {
    return {
      category: null,
      sending:false,
      success: false,
      bError: null,
      sort: 0,
      stock: 0,
      price: 0.0,
      productNumber: '',
      parentCategories: [this.$route.query.categoryId],
      active: 0,
      name: {},
      description: {},
      shortDescription: {}
    }
  },
  computed: {
    productOverviewUrl() {
        return this.localePath('/Portal/Product'+(this.$route.query.categoryId ? '?categoryId='+this.$route.query.categoryId : ''))
    }
  },
  methods: {
    onaddProduct(){
      this.bError = null;
      this.success = false
      this.sending = true;
      let formData = new FormData();
      formData.append('sort', this.sort)
      formData.append('stock', this.stock)
      formData.append('price', this.price)
      formData.append('productNumber', this.productNumber)
      formData.append('categories', this.parentCategories)
      formData.append('active', this.active)
      for(let i = 0; i < this.$i18n.locales.length; i++){
        formData.append('name['+this.$i18n.locales[i].iso+']', this.name[this.$i18n.locales[i].iso] ?? null)
        formData.append('description['+this.$i18n.locales[i].iso+']', this.description[this.$i18n.locales[i].iso] ?? null)
        formData.append('shortDescription['+this.$i18n.locales[i].iso+']', this.shortDescription[this.$i18n.locales[i].iso] ?? null)
      }
      formData.append('img', document.getElementById('fileInput')?.files[0])

      this.$axios.post(process.env.baseUrl + 'Admin/Product/add',formData,{
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'multipart/form-data'
        }
      }).then(r => {
        if(r?.data.success){
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
    if(this.$route.query.categoryId) {
      this.category = await this.$store.dispatch('Categories/getById', {
        id: this.$route.query.categoryId,
        fetchInactive: 1,
        fetchTranslations: 0
      });
      if(this.category)
        this.category = JSON.parse(JSON.stringify(this.category))
      else
        this.category = [];
    }
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';

</style>
