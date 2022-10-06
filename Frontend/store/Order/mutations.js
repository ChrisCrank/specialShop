const mutations = {
  ADD_ORDER (state, order) {
    state.orders.push(order);
  },
  SET_ORDERS (state, orders) {
    state.orders = orders
  }
}

export default mutations
