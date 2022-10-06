export const actions = {
  // nuxtServerInit is called by Nuxt.js before server-rendering every page
  async nuxtServerInit ({dispatch}) {
      await dispatch('Categories/load', {fetchInactive: 0, fetchTranslations: 0})
      await dispatch('User/getUser', {logoffOnFailure: false})
      await dispatch('Cart/load', true)
  }
}
