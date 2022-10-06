<template>
  <div class="position-relative p-4">
    <caption-title v-if="Category && Category.name" :text="$t('product.titleCategory', {name: Category ? Category.name: ''})" />
    <caption-title v-else :text="$t('product.title')" />
    <div class="container card">
      <div class="d-flex flex-column">
        <div class="w-100 d-flex">
          <b-form-input v-model="searchString" @keyup.native="onSearch()" :placeholder="$t('admin.search')"></b-form-input>
          <b-button variant="secondary" @click="onSearch()" :title="$t('admin.search')"><fa icon="search" /></b-button>
        </div>
        <div class="d-flex justify-content-end mt-2 mb-2">
          <b-button variant="primary" @click="$router.push(localePath('/Portal/Product/create?categoryId='+$route.query.categoryId))">{{$t('product.add')}}</b-button>
        </div>
      </div>
      <b-table striped hover :items="products" :fields="fields" :busy="!productLoaded" :per-page="perPage" :current-page="currentPage">
        <template #cell(sort)="data">
          <b>{{ data.item.sort }}</b>
        </template>
        <template #cell(productNumber)="data">
          <b>{{ data.item.productNumber }}</b>
        </template>
        <template #cell(img)="data">
          <img
            :src="imgPath + 'img/uploads/'+getOrgImage(data.item.calculatedImages)"
            :srcset="getImgSrcSet(imgPath + 'img/uploads/', data.item.calculatedImages)"
            :sizes="getSizes(30, 10, 10 ,10)"
            class="customCardImg"
            height="50px"
            :alt="data.item.name + '-Thumbnail'"
          >
        </template>
        <template #cell(createdAt)="data">
          {{ (data.item.createdAt) ? data.item.createdAt.toLocaleString($i18n.locale) : '' }}
        </template>
        <template #cell(active)="data">
          <fa v-if="data.item.active" class="text-success" :icon="'check'" />
          <fa v-if="!data.item.active" class="text-danger" :icon="'times'" />
        </template>
        <template #cell(actions)="data">
          <b-button variant="success" title="edit" @click="$router.push(localePath('/Portal/Product/'+data.item.id+'/edit'))"><fa :icon="'edit'" /></b-button>
          <b-button variant="danger" title="delete" @click="$router.push(localePath('/Portal/Product/'+data.item.id+'/delete'))"><fa :icon="'trash'" /></b-button>
        </template>
        <template #custom-foot>
          <tr>
            <td :colspan="fields.length">
              <b-pagination
                v-model="currentPage"
                :total-rows="products.length"
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
import Image from "~/mixins/Image/image";
export default {
  mixins: [adminAuthRequiredPage, Image],
  components: {captionTitle, RecursiveCategory},
  layout: 'admin',
  data () {
    return {
      searchString: '',
      Category: null,
      products: [],
      fields: [
        { key: 'sort', label: 'sort', sortable: true },
        { key: 'productNumber', label: 'Product-Number', sortable: true },
        { key: 'active', label: 'active', sortable: true },
        { key: 'name', label: 'name', sortable: true },
        { key: 'img', label: 'Image', sortable: false },
        { key: 'createdAt', label: 'Datum', sortable: true },
        { key: 'actions', label: '' }
      ],
      perPage: 10,
      currentPage: 1,
      productLoaded: false,
    }
  },
  methods: {
    onSearch(){
      this.products = JSON.parse(JSON.stringify(this.products)).filter(e => e.name.toLowerCase().includes(this.searchString.toLowerCase()))
    },
  },
  async mounted () {
    if(this.$route.query.categoryId) {
      this.Category = await this.$store.dispatch('Categories/getById', {
        id: this.$route.query.categoryId,
        fetchInactive: 1,
        fetchTranslations: 0
      });
      if(this.Category)
        this.Category = JSON.parse(JSON.stringify(this.Category))

      this.products = await this.$store.dispatch('Product/getByCategoryId',{
        fetchCategoryId: this.$route.query.categoryId,
        fetchInactive: true,
        fetchTranslations: true,
        offset: 0,
        limit: 0
      })
    }else {
      this.products = await this.$store.dispatch('Product/getAll', {fetchInactive: true, fetchTranslations: true})
    }
    this.productLoaded = true
  }
}
</script>
