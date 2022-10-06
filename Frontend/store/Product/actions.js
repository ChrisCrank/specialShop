const formatProductCollection = function (products, fetchTranslations = false, ) {
   for (let i = 0; i < products.length; i++) {
     products[i].categories = products[i].categoryIds
     products[i].createdAt = new Date(products[i].createdAt)
     if (fetchTranslations === 1) {
       for (let y = 0; y < products[i]?.translation.length; y++) {
         const iso = products[i].translation[y].language.iso;
         if (!products[i].translations) {
           products[i].translations = {
             name: {},
             description: {},
             shortDescription: {}
           }
         }
         products[i].translations.name[iso] = products[i].translation[y].name;
         products[i].translations.description[iso] = products[i].translation[y].description;
         products[i].translations.shortDescription[iso] = products[i].translation[y].shortDescription;
       }
     }
   }

  return products
}

const actions = {
  async getAll(context, {fetchInactive= 0, fetchTranslations = 0}){
    if(
      context.getters.getAllProducts.length === 0 ||
      context.getters.getFetchMode.fetchInactive !== fetchInactive ||
      context.getters.getFetchMode.fetchTranslations !== fetchTranslations
    ) {
      let res = await this.$axios.get(this.app.context.env.baseUrl + 'Product/Product/all/' + fetchInactive + '/' + fetchTranslations )
      const r   = res?.data;
      if(r?.success) {
        const products = formatProductCollection(r?.Products, fetchTranslations);
        await context.commit('SET_ALL_PRODUCTS', products.sort((a, b) => {
          return a.sort - b.sort
        }))
        await context.commit('SET_FETCH_MODE', {fetchInactive, fetchTranslations})
        return products
      }
    }else{
      return context.getters.getAllProducts
    }
  },
  async getTotalProductCount(context, {fetchInactive= 0}) {
    if(
      context.getters.getTotalProductCount !== 0
    ){
      let res = await this.$axios.get(this.app.context.env.baseUrl + 'Product/Product/count/' + fetchInactive )
      const r   = res?.data;
      if(r?.success) {
        await context.commit('SET_PRODUCT_TOTAL_COUNT', r?.ProductCount)
        return r?.ProductCount
      }
    }else{
      return context.getters.getTotalProductCount
    }
  },
  async getTotalProductCountByCategoryId(context, {fetchInactive= 0, categoryId}) {
    if(
      context.getters.getTotalProductCountByCategoryId(categoryId) !== 0
    ){
      let res   = await this.$axios.get(this.app.context.env.baseUrl + 'Product/Product/count/' + fetchInactive + '/' + categoryId )
      const r   = res?.data;
      if(r?.success) {
        await context.commit('SET_PRODUCT_TOTAL_COUNT_CATEGORY', r?.ProductCount)
        return r?.ProductCount
      }
    }else{
      return context.getters.getTotalProductCountByCategoryId(categoryId)
    }
  },
  async getByCategoryId(context, {fetchInactive= 0, fetchTranslations = 0, fetchCategoryId = 0, offset= 0, limit= 0}){
    if(
      context.getters['getProductsByCategoryId'](fetchCategoryId, offset, limit)?.products?.length === 0 ||
      context.getters.getFetchModeCategory.fetchInactive !== fetchInactive ||
      context.getters.getFetchModeCategory.fetchTranslations !== fetchTranslations ||
      context.getters.getFetchModeCategory.fetchCategoryId !== fetchCategoryId
    ) {
      let res = await this.$axios.get(
        this.app.context.env.baseUrl + 'Product/Product/all/' +
        fetchInactive + '/' +
        fetchTranslations + '/' +
        fetchCategoryId  + '/' +
        offset + '/' +
        limit
      )
      const r   = res?.data;
      if(r?.success) {
        const products = formatProductCollection(r?.Products, fetchTranslations);
        const resObj = {
          categoryId: fetchCategoryId,
          products
        }
        await context.commit('SET_PRODUCT_CATEGORY_BASED', resObj)
        await context.commit('SET_FETCH_MODE_CATEGORY', {fetchInactive, fetchTranslations, fetchCategoryId})
        return products ?? []
      }
    }else{
      return context.getters['getProductsByCategoryId'](fetchCategoryId, offset, limit)?.products ?? []
    }
  },

  async getById(context, {id ,fetchInactive= 0, fetchTranslations = 0}){
    let product = null
    if(!isNaN(id))
      product = context.getters['getProductById'](id);
    else
      product = context.getters['getProductByName'](id);
    if(!product?.length) {
      let res = await this.$axios.get(this.app.context.env.baseUrl + 'Product/Product/id/' + id + '/' + fetchInactive + '/' + fetchTranslations)
      const r = res?.data;
      if(r?.success) {
        product = formatProductCollection([r?.Product], fetchTranslations)[0];
        context.commit('ADD_PRODUCT', product)
        return product;
      }
      res = res?.data?.product;
      return res;
    }else{
      return product
    }
  },
  async getRecentProducts(context, {offset = 0, limit = 0}){
    let recentProducts = context.getters['getRecentProducts'](offset, limit);
    // load if not all products exist inside store
    if(!recentProducts?.length || recentProducts?.length !== limit) {
      let res = await this.$axios.get(this.app.context.env.baseUrl + 'Product/Product/recent/' + offset + '/' + limit)
      const r = res?.data;
      const obj = {};
      let posCounter = offset;
      if(r?.success) {
        recentProducts = formatProductCollection(r?.Products);
        for(let i = 0; i < recentProducts.length; i++) {
          obj[posCounter] = recentProducts[i];
          posCounter++;
        }
        context.commit('ADD_RECENT_PRODUCTS', obj)
      }
      return obj;
    }else{
      return recentProducts
    }
  },
  async clearProductStore(context){
      context.commit('SET_ALL_PRODUCTS', [])
      context.commit('SET_PRODUCT_CATEGORY_BASED', {})
  },
}

export default actions
