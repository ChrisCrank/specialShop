import actions from './actions'
import getters from './getters'
import mutations from './mutations'
export const state = () => ({
  Categories: [],
  CategoriesSorted: [],
  fetchMode: {}
})

export default {
  state,
  actions,
  mutations,
  getters
}
