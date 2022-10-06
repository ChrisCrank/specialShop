const getters = {
  getFetchMode: state => state.fetchMode,
  getCategories: state => state.Categories,
  getCategoriesById: state => (id) => {return state.Categories.find(e => e.id == id)},
  getCategoriesSorted: state => state.CategoriesSorted
}

export default getters
