const actions = {
  async load (context){
    context.commit('SET_LOADING', true)
    const user = context.rootGetters['User/getUser']
    if(!user) {
      // get Cart from cookie
      const items = this.app.$cookiz.get('cart-items') ?? [];
      for (let i = 0; i < items.length; i++) {
        // get product information
        items[i].product = await context.dispatch('Product/getById',{id:items[i].productId ,fetchInactive:0, fetchTranslations:0},{root:true})
        context.commit('SET_CART_ITEM', items[i])
      }
    }else{
      // get Cart from User
      const res = await this.$axios.get(this.app.context.env.baseUrl + 'User/Cart/get')
      const cartItems = res?.data?.cart;
      for(let i = 0; i < cartItems.length; i++){
        cartItems[i].product.quantity = cartItems.quantity
        context.commit('SET_CART_ITEM', {
          productId: cartItems[i].productId,
          quantity: cartItems[i].quantity,
          product: cartItems[i].product
        })
      }
    }
    context.commit('SET_LOADING', false)
  },
  openCart (context) {
    context.commit('SET_STATE', true)
  },
  closeCart (context) {
    context.commit('SET_STATE', false)
  },
  clearLocalCart (context) {
    this.app.$cookiz.remove('cart-items', {
      path: '/'
    });
    context.commit('CLEAR_CART')

  },
  async add (context, {productId, quantity, set=false}) {
    context.commit('SET_LOADING', true)
    let items = this.app.$cookiz.get('cart-items') ?? [];
    if(!items){
      // create cookie
      this.app.$cookiz.set('cart-items', JSON.stringify([
        {
          productId,
          quantity
        }
      ]), {
        path: '/',
        maxAge: 60 * 60 * 24 * 7 * 1000
      })
    }else{
      // update cookie
      const index = items.findIndex(e => e.productId === productId)
      if(index !== -1) {
        if(set)
          items[index].quantity = quantity
        else
          items[index].quantity += quantity;
      } else {
        items.push({
          productId,
          quantity
        })
      }
      this.app.$cookiz.set('cart-items', JSON.stringify(items), {
        path: '/',
        maxAge: 60 * 60 * 24 * 7 * 1000
      })
    }

    if(!set) {
      // add Item to store
      context.commit('ADD_CART_ITEM', {
        productId,
        quantity,
        product: await context.dispatch('Product/getById', {
          id: productId,
          fetchInactive: 0,
          fetchTranslations: 0
        }, {root: true})
      })
    } else {
      context.commit('SET_CART_ITEM', {
        productId,
        quantity,
        product: await context.dispatch('Product/getById', {
          id: productId,
          fetchInactive: 0,
          fetchTranslations: 0
        }, {root: true})
      })
    }
    // add item to User (if exist)
    if(context.rootGetters['User/getUser']){
      await this.$axios.post(this.app.context.env.baseUrl + 'User/Cart/add', {productId, quantity, set})
    }
    context.commit('SET_LOADING', false)
  },
  async remove (context, productId) {
    context.commit('SET_LOADING', true)
    let items = this.app.$cookiz.get('cart-items') ?? [];
    if(items){
      // update cookie
      const index = items.findIndex(e => e.productId === productId)
      if(index !== -1) {
        items.splice(index, 1)
      }
      this.app.$cookiz.set('cart-items', JSON.stringify(items), {
        path: '/',
        maxAge: 60 * 60 * 24 * 7 * 1000
      })
    }
    context.commit('REMOVE_CART_ITEM', productId)
    // remove item from User (if exist)
    if(context.rootGetters['User/getUser']){
      await this.$axios.post(this.app.context.env.baseUrl + 'User/Cart/remove', {productId})
    }
    context.commit('SET_LOADING', false)
  },
  async clearCart (context) {
    context.commit('SET_LOADING', true)
    await context.dispatch('clearLocalCart');
    // remove item from User (if exist)
    if(context.rootGetters['User/getUser']){
      await this.$axios.delete(this.app.context.env.baseUrl + 'User/Cart/clear')
    }
    context.commit('SET_LOADING', false)
  }
}

export default actions
