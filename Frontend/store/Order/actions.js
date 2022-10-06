
const actions = {
  async load (context){
    const user = context.rootGetters['User/getUser']
    if(!user) {
      return false;
    }else{
      // get Orders from User
      const res = await this.$axios.get(this.app.context.env.baseUrl + 'User/Order/get')
      const orders = res?.data?.orders;
      context.commit('SET_ORDERS', orders)
    }
  },
  async addOrder (context, {
    products,
    shippingMethod,
    paymentMethod
  }) {
    if(products){
      // get Orders from User
      const res = await this.$axios.post(this.app.context.env.baseUrl + 'User/Order/add', {
        products,
        shippingMethod,
        paymentMethod
      })
      return res?.data;
    }
  }
}

export default actions
