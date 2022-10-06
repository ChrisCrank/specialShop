<template>
  <div class="position-relative p-4">
    <caption-title :text="$t('category-delete.title', {name: category ? category.name: ''})" />
    <div class="container">
      <b-button variant="primary" @click="$router.push(localePath('/Portal/Category'))">Back</b-button>
    </div>
    <div v-if="!success" class="container card shadow mt-4 mb-4 w-50">
      <ValidationObserver ref="observer" v-slot="{ passes }" tag="div">
        <form v-if="pageLoaded" @submit.prevent="passes(onDeleteCategory)" @reset="e => {e.preventDefault()}">
          <div class="w-100 p-4">
            <span v-for="(cat,index) in childCategories" :key="'category'+index">
              <NuxtLink :to="localePath('/Portal/Category/'+cat.id+'/edit/')"  :title="cat.name" >{{ cat.name }}</NuxtLink><span class="text-primary pl-1 pr-1" v-if="index !== childCategories.length-1">/</span>
            </span>
          </div>
          <div class="container">
            <h3>
              <i18n :path="'category-delete.title'" tag="span">
                <template v-slot:name>
                  <b>{{ category ? category.name: '' }}</b>
                </template>
              </i18n>
            </h3>
            <div class="d-flex justify-content-center align-items-center mt-3 mb-3">
              <i18n :path="'category-delete.description'" tag="div" class="w-50">
                <template v-slot:name>
                  <b>{{ category ? category.name: '' }}</b>
                </template>
              </i18n>
            </div>
          </div>
          <hr>
          <div class="w-100 d-flex justify-content-end pb-2">
            <b-button variant="secondary" @click="$router.push(localePath('/Portal/Category'))">{{ $t('category-delete.abort') }}</b-button>
            <b-button :disabled="errors && errors.length !== 0 || !pageLoaded || sending"
                    type="submit"
                    variant="danger"
                    class="d-flex justify-content-center align-items-center ml-2">
              {{ $t('category-delete.delete') }}
              <b-spinner v-if="!pageLoaded || sending" class="ml-1" variant="light" small label="Spinning"></b-spinner>
            </b-button>
          </div>
        </form>
      </ValidationObserver>
    </div>
    <div v-else class="container mt-4 mb-4 card p-4">
      <b-alert variant="success" show>
        {{ $t('category-delete.success') }}
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
import {mapGetters} from "vuex";
import category from "~/mixins/Portal/category";

export default {
  mixins: [adminAuthRequiredPage, ValidationForm, category],
  components: {captionTitle},
  layout: 'admin',

  data () {
    return {
      sending:false,
      success: false,
      bError: null,
      category: {}
    }
  },
  computed: {
    ...mapGetters({
      categories: 'Categories/getCategories'
    })
  },
  methods: {
    onDeleteCategory(){
      this.sending = true;
      this.$axios.delete(process.env.baseUrl + 'Admin/Category/delete/'+this.category.id).then(r => {
        this.sending = true
        if(r?.data.success){
          this.success = true;
          this.$store.dispatch('Categories/deleteCategory')
        }else{
          if(r?.data?.codes?.includes('F3'))
            this.bError = this.$t('category-delete.errors.F3');
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
    this.category = await this.$store.dispatch('Categories/getById', {id: this.$route.params.categoryid, fetchInactive: 1});
    if(typeof this.category == 'undefined')
      this.$router.push(this.localePath('/Portal/Category'))
    else
      this.category = JSON.parse(JSON.stringify(this.category))
  }
}
</script>
<style>

</style>
