const mutations = {
  SET_FETCH_MODE (state, {fetchInactive, fetchTranslations}) {
    state.fetchMode = {
      fetchInactive,
      fetchTranslations
    }
  },
  SET_FETCH_MODE_CATEGORY (state, {fetchInactive, fetchTranslations, fetchCategoryId}) {
    state.fetchModeCategory = {
      fetchInactive,
      fetchTranslations,
      fetchCategoryId
    }
  },

  SET_ALL_PRODUCTS (state, products){
    state.allProducts = products
  },
  SET_PRODUCT_CATEGORY_BASED (state, categoryProducts){
    // if emty clear complete object
    if(!categoryProducts)
      state.productsCategoryBased = {};
    if(state.productsCategoryBased[categoryProducts.categoryId]) {
      for(let i = 0; i < categoryProducts.products.length; i++){
        const id = categoryProducts.products[i].id
        // check if product already exist
        if(!state.productsCategoryBased[categoryProducts.categoryId].find(e => e.id === id))
          state.productsCategoryBased[categoryProducts.categoryId].push(categoryProducts.products[i])
      }
    }
    else
      state.productsCategoryBased[categoryProducts.categoryId] = categoryProducts.products
  },

  SET_PRODUCT_TOTAL_COUNT (state, totalProductCount){
      state.totalProductCount = totalProductCount
  },
  SET_PRODUCT_TOTAL_COUNT_CATEGORY (state, {totalProductCount, categoryId}){
    state.totalProductCountCategory[categoryId] = totalProductCount
  },
  ADD_RECENT_PRODUCTS (state, recentProducts) {
    state.recentProducts = {...state.recentProducts, ...recentProducts}
  },
  ADD_PRODUCT (state, product) {
    state.detailedProducts.push(product)
  }
}

export default mutations
