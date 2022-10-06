import actions from './actions'
import getters from './getters'
import mutations from './mutations'
export const state = () => ({
  items: [],
  state: false,
  loading: false,
  error: []
})

export default {
  state,
  actions,
  mutations,
  getters
}
