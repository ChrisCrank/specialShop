<template>
  <div class="position-relative p-4">
    <caption-title :text="$t('category-edit.title', category ? category.name: '')" />
    <div class="container">
      <b-button variant="primary" @click="$router.push(localePath('/Portal/Category'))">Back</b-button>
    </div>
    <div v-if="!success" class="container card shadow mt-4 mb-4">
      <ValidationObserver ref="observer" v-slot="{ passes }" tag="div">
        <form v-if="pageLoaded" @submit.prevent="passes(onEditCategory)" @reset="e => {e.preventDefault()}">
          <div class="w-100 p-4">
            <span v-for="(cat,index) in childCategories" :key="'category'+index">
              <NuxtLink :to="localePath('/Portal/Category/'+cat.id+'/edit/')"  :title="cat.name" >{{ cat.name }}</NuxtLink><span class="text-primary pl-1 pr-1" v-if="index !== childCategories.length-1">/</span>
            </span>
          </div>
          <div class="container">
            <h3>
              <i18n :path="'category-edit.title'" tag="span">
                <template v-slot:name>
                  <b>{{ category ? category.name: '' }}</b>
                </template>
              </i18n>
            </h3>
          </div>
          <div class="form-group row mb-3">
            <div class="col-12 col-md-6">
              <span class="text-muted mb-1">{{ $t('category-add.sort.title') }}</span><span class="text-primary">*</span>
              <ValidatedTextInput
                v-model="category.sort"
                type="number"
                rules="numeric|required"
                :placeholder="$t('category-add.sort.title')"
                :name="$t('category-add.sort.title')"
                classes="p-0"
                input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
            </div>
            <div class="col-12 col-md-6">
              <span class="text-muted mb-1">{{ $t('category-add.parent.title') }}</span>
              <ValidatedSelectInput
                v-model="category.parentId"
                rules=""
                :placeholder="$t('category-add.parent.title')"
                classes="col-12 p-0"
                input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" >
                <template #default>
                  <b-form-select-option v-for="port in availableCategories" :key="'category'+port.value" :value="port.value">{{ port.text }}</b-form-select-option>
                </template>
              </ValidatedSelectInput>
            </div>
          </div>
          <div class="form-group row mb-3">
            <div class="col-12 col-md-6">
              <span class="text-muted mb-1">{{ $t('category-add.thumbnail.title') }}</span><span class="text-primary">*</span>
              <div class="position-relative" v-if="category.calculatedImages && category.calculatedImages.length">
                <b-button class="position-absolute" variant="danger" @click="category.calculatedImages = []">use Custom</b-button>
                <img
                  :src="imgPath + 'img/uploads/'+getOrgImage(category.calculatedImages)"
                  :srcset="getImgSrcSet(imgPath + 'img/uploads/', category.calculatedImages)"
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
              <span class="text-muted mb-1">{{ $t('category-add.active.title') }}</span>
              <ValidatedCheckboxInput
                v-model="category.active"
                :placeholder="$t('category-add.active.title')"
                :name="$t('category-add.active.title')"
                :value="1"
                :unchecked-value="0"
                classes="p-0"
                input-classes="form-control px-4 text-primary" />
            </div>
          </div>
          <b-tabs content-class="mt-3">
            <b-tab :active="(locale.iso === 'en-EN')" v-for="locale in availableLocales" :key="'locale'+locale.shortName">
              <template #title>
                <img :src="'/lang/' + locale.shortName + '.png'" alt=""> {{ locale.shortName }} ({{locale.name}})
              </template>
              <div class="form-group row mb-3">
                <div class="col-12">
                  <span class="text-muted mb-1">{{ $t('category-add.name.title') }}</span><span v-if="locale.iso === 'en-EN'" class="text-primary">*</span>
                  <ValidatedTextInput
                    v-if="category.translations"
                    v-model="category.translations.name[locale.iso]"
                    type="text"
                    :rules="'max:100|' + (locale.iso === 'en-EN' ? 'min:3|required' : '')"
                    :placeholder="$t('category-add.name.placeholder')"
                    :name="$t('category-add.name.title') + '('+locale.iso+')'"
                    classes="p-0"
                    input-classes="form-control rounded-pill border-1 shadow-sm px-4 text-primary" />
                </div>
                <div class="col-12">
                  <span class="text-muted mb-1">{{ $t('category-add.shortDesc.title') }}</span><span v-if="locale.iso === 'en-EN'" class="text-primary">*</span>
                  <ValidatedTextAreaInput
                    v-if="category.translations"
                    v-model="category.translations.description[locale.iso]"
                    type="text"
                    :rules="'max:255|' + (locale.iso === 'en-EN' ? 'min:3|required' : '')"
                    :name="$t('category-add.shortDesc.title') + '('+locale.iso+')'"
                    :placeholder="$t('category-add.shortDesc.title')"
                    classes="col-12 p-0"
                    input-classes="form-control border-1 shadow-sm px-4 text-primary" >
                  </ValidatedTextAreaInput>
                </div>
              </div>
            </b-tab>
          </b-tabs>
          <div class="d-flex flex-column align-self-center" v-if="errors">
            <div class="text-danger d-flex justify-content-center"
                 v-for="(error, key) in errors"
                 :key="error"
                 v-if="key <= 3"
            >{{ key < 3 ? error : '...' }}</div>
          </div>
          <hr>
          <div class="w-100 d-flex justify-content-end pb-2">
            <b-button variant="secondary" @click="$router.push(localePath('/Portal/Category'))">{{ $t('category-edit.abort') }}</b-button>
            <b-button :disabled="errors && errors.length !== 0 || !pageLoaded || sending"
                      type="submit"
                      variant="success"
                      class="d-flex justify-content-center align-items-center ml-2">
              {{ $t('category-edit.edit') }}
              <b-spinner v-if="!pageLoaded || sending" class="ml-1" variant="light" small label="Spinning"></b-spinner>
            </b-button>
          </div>
        </form>
      </ValidationObserver>
    </div>
    <div v-else class="container mt-4 mb-4 card p-4">
      <b-alert variant="success" show>
        {{ $t('category-edit.success') }}
        <b-button variant="primary" @click="$router.push(localePath('/Portal/Category'))">Go Back</b-button>
      </b-alert>
    </div>
    <div v-if="bError" class="container mt-4 mb-4 card p-4">
      <b-alert variant="danger" show>
        {{ bError }}
        <b-button variant="primary" @click="$router.push(localePath('/Portal/Category'))">Go Back</b-button>
      </b-alert>
    </div>
  </div>
</template>
<script>
import adminAuthRequiredPage from "@/mixins/Page/adminAuthRequiredPage";
import captionTitle from "@/components/Theme/Caption/caption";
import ValidationForm from "@/mixins/Form/validationForm";
import Image from "@/mixins/Image/image";
import {mapGetters} from "vuex";
import category from "~/mixins/Portal/category";

export default {
  mixins: [adminAuthRequiredPage, ValidationForm, Image, category],
  components: {captionTitle},
  layout: 'admin',

  data () {
    return {
      sending:false,
      success: false,
      bError: null,
    }
  },
  computed: {
    ...mapGetters({
      categories: 'Categories/getCategories'
    })
  },
  methods: {
    onEditCategory(){
      this.bError = null;
      this.success = false
      this.sending = true;
      let formData = new FormData();
      formData.append('id', this.category.id)
      formData.append('sort', this.category.sort)
      formData.append('parent', this.category.parentId)
      formData.append('active', this.category.active)
      for(let i = 0; i < this.availableLocales.length; i++){
        formData.append('name['+this.availableLocales[i].iso+']', this.category.translations.name[this.availableLocales[i].iso] ?? null)
        formData.append('description['+this.availableLocales[i].iso+']', this.category.translations.description[this.availableLocales[i].iso] ?? null)
      }
      if(this.category.calculatedImages.length === 0)
        formData.append('img', document.getElementById('fileInput')?.files[0])

      this.$axios.post(process.env.baseUrl + 'Admin/Category/edit',formData,{
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'multipart/form-data'
        }
      }).then(r => {
        if(r?.data.success){
          this.success = true;
          this.$store.dispatch('Categories/addCategory')
        }else{
          if(r?.data?.codes?.includes('F1'))
            this.bError = this.$t('category-add.errors.F1');
          else if(r?.data?.codes?.includes('F2'))
            this.bError = this.$t('category-add.errors.F2');
          else if(r?.data?.codes?.includes('F3'))
            this.bError = this.$t('category-add.errors.F3', {language: r?.data?.language});
          else if(r?.data?.codes?.includes('F4'))
            this.bError = this.$t('category-add.errors.F4');
          else
            this.bError = this.$t('admin.errors.general')
        }

        this.sending = false;
      }).catch(e => {
        this.sending = false;
        this.bError = this.$t('admin.errors.general')
      })
    }
  },
  async mounted () {
    this.category = await this.$store.dispatch('Categories/getById', {id: this.$route.params.categoryid, fetchInactive: 1, fetchTranslations: 1});
    if(typeof this.category == 'undefined')
      this.$router.push(this.localePath('/Portal/Category'))
    else
      this.category = JSON.parse(JSON.stringify(this.category))
  }
}
</script>
<style>

</style>
