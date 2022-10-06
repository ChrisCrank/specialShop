const getters = {
  getFetchMode: state => state.fetchMode,
  getFetchModeCategory: state => state.fetchModeCategory,
  getAllProducts: state => state.allProducts,
  getTotalProductCount: state => state.totalProductCount,
  getTotalProductCountByCategoryId: state => (categoryId) => state.totalProductCount?.[categoryId],
  getProductsByCategoryId: state => (categoryId, offset = 0, limit = 0) => {
    const products = state.productsCategoryBased[categoryId];
    if(offset === 0 && limit === 0) {
      return {
        categoryId: categoryId,
        products: products
      }
    }
    return {
      categoryId: categoryId,
      products: products?.slice(offset,limit)
    }
  },
  getRecentProducts: state => (offset, limit) => {
    if(state.recentProducts[offset]){
      const res = {};
      for(let i = offset; i < offset+limit; i++){
        if(state.recentProducts[i]){
          res[i] = state.recentProducts[i];
        }
      }
      return res;
    }else{
      return null;
    }
  },
  getProductById: state => (productId) => {return state.detailedProducts?.find(e => e?.id == productId)},
  getProductByName: state => (productId) => {return state.detailedProducts?.find(e => e?.name == productId)},
}

export default getters
