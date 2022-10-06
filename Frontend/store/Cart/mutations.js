const mutations = {
  SET_STATE (state, openState) {
    state.state = openState;
  },
  SET_LOADING (state, loading) {
    // check for Errors inside cart if loading finished
    if(!loading) {
      state.error = [];
      for (let i = 0; i < state.items.length; i++) {
        if (state.items[i].quantity > state.items[i].product.stock) {
          state.error.push({
              msgCode: 'F1',
              payload: {
                id: state.items[i].productId,
                name: state.items[i].product.name,
                quantity: state.items[i].quantity,
                stock: state.items[i].product.stock
              }
          })
        }
      }
    }

    state.loading = loading
  },
  ADD_CART_ITEM (state, item) {
    const itemIndex = state.items.findIndex(e => e.productId === item.productId);
    if(itemIndex !== -1){
      state.items[itemIndex].quantity = parseInt(state.items[itemIndex].quantity) + parseInt(item.quantity)
    }else{
      state.items.push(item)
    }
  },
  SET_CART_ITEM (state, item){
    const itemIndex = state.items.findIndex(e => e.productId === item.productId);
    if(itemIndex !== -1){
      state.items[itemIndex] = item
    }else{
      state.items.push(item)
    }
  },
  REMOVE_CART_ITEM (state, productId){
    const itemIndex = state.items.findIndex(e => e.productId === productId);
    if(itemIndex !== -1){
      state.items.splice(itemIndex, 1)
    }
  },
  CLEAR_CART (state) {
    state.items = []
  }
}

export default mutations
