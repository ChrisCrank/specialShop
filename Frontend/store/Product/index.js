import actions from './actions'
import getters from './getters'
import mutations from './mutations'
export const state = () => ({
  categoryProductsChilds: [],
  recentProducts: {},

  productsCategoryBased: {}, // {<categoryId>: products}
  allProducts: [],
  detailedProducts: [],

  totalProductCount: 0,
  totalProductCountCategory: {},

  fetchMode: {},
  fetchModeCategory: {}
})

export default {
  state,
  actions,
  mutations,
  getters
}
