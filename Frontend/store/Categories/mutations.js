const mutations = {
  SET_FETCH_MODE (state, {fetchInactive, fetchTranslations}) {
    state.fetchMode = {
      fetchInactive,
      fetchTranslations
    }
  },
  SET_CATEGORIES (state, Categories) {
    state.Categories = JSON.parse(JSON.stringify(Categories));

    const addChilds = function (obj) {
      for(let i = 0; i < obj.length; i++){
        obj[i].childs = Categories.filter(e => e.parentId === obj[i].id)?.sort((a, b) => a.sort - b.sort) ?? []
        obj[i].childs = addChilds(obj[i].childs)
      }
      return obj;
    }
    state.CategoriesSorted = addChilds(
      Categories.filter(e => e.parentId == null)
    );
  }
}

export default mutations
