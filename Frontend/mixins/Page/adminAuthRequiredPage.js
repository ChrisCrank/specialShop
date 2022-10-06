import {mapGetters} from "vuex";
export default {
  computed: {
    ...mapGetters({
      user: 'User/getUser'
    })
  },
  async asyncData({store, redirect, req}) {
    let loaded = false;
    // request user
    await store.dispatch('User/getUser',{logoffOnFailure: true});
    const user = store.getters['User/getUser']
    if (!user || user?.length === 0 || !user?.isAdmin ){
      redirect('/')
    } else {
      loaded = true
    }
    return {
      pageLoaded: loaded
    }
  }
}
