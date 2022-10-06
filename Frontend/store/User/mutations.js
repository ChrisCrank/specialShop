const mutations = {
  SET_USER (state, user) {
    state.user = user;
    if(user && user.language) {
      state.user.languageId = user.language.iso
    }
  },
  SET_USER_LANGUAGE (state, language) {
    state.user.languageId = language;
    if(state.user.language) {
      state.user.language.iso = language;
    }
  }
}

export default mutations
