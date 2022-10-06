<template>
  <div class="position-relative p-4">
    <caption-title :text="$t('category.title')"/>
    <div class="container card">
      <div class="d-flex flex-column">
        <div class="w-100 d-flex">
          <b-form-input v-model="searchString" @keyup.native="onSearch()" :placeholder="$t('admin.search')"></b-form-input>
          <b-button variant="secondary" @click="onSearch()" :title="$t('admin.search')"><fa icon="search" /></b-button>
        </div>
        <div class="d-flex justify-content-end mt-2 mb-2">
          <b-button variant="primary" @click="$router.push(localePath('/Portal/Category/create'))">{{$t('category.add')}}</b-button>
        </div>
      </div>
      <b-table striped hover :items="categories" :fields="fields" :busy="!categoriesLoaded" :per-page="perPage" :current-page="currentPage">
        <template #cell(createdAt)="data">
          {{ (data.item.createdAt) ? data.item.createdAt.toLocaleString($i18n.locale) : '' }}
        </template>
        <template #cell(active)="data">
          <fa v-if="data.item.active" class="text-success" :icon="'check'" />
          <fa v-if="!data.item.active" class="text-danger" :icon="'times'" />
        </template>
        <template #row-details="data">
          <RecursiveCategory v-if="data.item.childs.length" :el="data.item.childs" :fields="fields" :path="'/Portal/Category'" />
        </template>
        <template #cell(actions)="data">
          <b-button variant="warning" title="show Products" @click="$router.push(localePath('/Portal/Product?categoryId='+data.item.id))"><fa :icon="'blog'" /></b-button>
          <b-button variant="success" title="edit" @click="$router.push(localePath('/Portal/Category/'+data.item.id+'/edit/'))"><fa :icon="'edit'" /></b-button>
          <b-button variant="danger" title="delete" @click="$router.push(localePath('/Portal/Category/'+data.item.id+'/delete/'))"><fa :icon="'trash'" /></b-button>
          <b-button v-if="data.item.childs.length" variant="info"  @click="data.toggleDetails">
            <fa v-if="data.detailsShowing" icon="eye-slash"/>
            <fa v-else icon="eye"/>
          </b-button>
        </template>
        <template #custom-foot>
          <tr>
            <td :colspan="fields.length">
              <b-pagination
                v-model="currentPage"
                :total-rows="categories.length"
                :per-page="perPage"
                align="right"
              ></b-pagination>
            </td>
          </tr>
        </template>
      </b-table>
    </div>
  </div>
</template>
<script>
import adminAuthRequiredPage from "~/mixins/Page/adminAuthRequiredPage";
import captionTitle from "~/components/Theme/Caption/caption";
import RecursiveCategory from "~/components/Portal/RecursiveCategory";
import {mapGetters} from "vuex";
export default {
  mixins: [adminAuthRequiredPage],
  components: {captionTitle, RecursiveCategory},
  layout: 'admin',
  data () {
    return {
      searchString: '',
      categories: [],
      fields: [
        { key: 'sort', label: 'sort', sortable: true },
        { key: 'name', label: 'name', sortable: true },
        { key: 'active', label: 'active', sortable: true },
        { key: 'createdAt', label: 'Datum', sortable: true },
        { key: 'actions', label: '' }
      ],
      perPage: 10,
      currentPage: 1,
      categoriesLoaded: false,
    }
  },
  methods: {
    onSearch(){
      this.categories = JSON.parse(JSON.stringify(this.categoriesSorted)).filter(e => e.name.toLowerCase().includes(this.searchString.toLowerCase()))
    },
  },
  computed: {
    ...mapGetters({
      categoriesSorted: 'Categories/getCategoriesSorted',
      categoriesSrc: 'Categories/getCategories',
    })
  },
  created() {
    this.categoriesLoaded = true;
    this.categories = JSON.parse(JSON.stringify(this.categoriesSorted));
  },
  async asyncData({store}) {
    // load all Categories (inactive categories also)
    await store.dispatch('Categories/load', {fetchInactive: 1, fetchTranslations: 1})
  },
}
</script>
