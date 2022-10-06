const actions = {
  async load(context, {fetchInactive = 0, fetchTranslations = 0}) {
    if (
      context.getters.getCategories.length === 0 ||
      context.getters.getFetchMode.fetchInactive !== fetchInactive ||
      context.getters.getFetchMode.fetchTranslations !== fetchTranslations
    ) {
      const res = await this.$axios.get(this.app.context.env.baseUrl + 'Category/Category/all/' + fetchInactive + '/' + fetchTranslations)
      const r = res?.data;
      if (r?.success) {
        const categories = r?.Categories;
        if (fetchTranslations === 1) {
          for (let i = 0; i < categories.length; i++) {
            categories[i].createdAt = new Date(categories[i].createdAt)
            for (let y = 0; y < categories[i]?.translation.length; y++) {
              const iso = categories[i].translation[y].language.iso;
              if (!categories[i].translations) {
                categories[i].translations = {
                  name: {},
                  description: {}
                }
              }
              categories[i].translations.name[iso] = categories[i].translation[y].name;
              categories[i].translations.description[iso] = categories[i].translation[y].description;
            }
          }
        }
        await context.commit('SET_CATEGORIES', categories.sort((a, b) => {
          return a.sort - b.sort
        }))
        await context.commit('SET_FETCH_MODE', {fetchInactive, fetchTranslations})
        return categories
      }
    } else {
      return null
    }
  },

  async getById(context, {id, fetchInactive = 0, fetchTranslations = 0}) {
    if (
      context.getters.getCategories.length === 0 ||
      context.getters.getFetchMode.fetchInactive !== fetchInactive ||
      context.getters.getFetchMode.fetchInactive !== fetchTranslations
    ) {
      await context.dispatch('load', {fetchInactive, fetchTranslations})
    }
    return context.getters['getCategoriesById'](id);
  },
  addCategory(context) {
    context.commit('SET_CATEGORIES', [])
    context.dispatch('load', {fetchInactive: 1, fetchTranslations: 1})
  },
  deleteCategory(context) {
    context.commit('SET_CATEGORIES', [])
    context.dispatch('load', {fetchInactive: 1, fetchTranslations: 1})
  }
}

export default actions
