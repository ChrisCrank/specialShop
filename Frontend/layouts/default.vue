<template>
  <div class="main-content">
    <the-menu />
    <div class="page MainContentPageContent position-relative">
      <nuxt />
    </div>
    <the-footer />
    <offcanvas-cart />
  </div>
</template>

<script>
  import TheMenu from "~/components/Menu/TheMenu";
  import TheFooter from "~/components/TheFooter/Footer";
  import OffcanvasCart from "~/components/Theme/OffcanvasCart";
  export default {
    name: 'Default',
    components: {
      TheMenu, TheFooter, OffcanvasCart
    },
    data () {
      return {
        showMainNavigation: false
      }
    },
    mounted () {
      // eslint-disable-next-line nuxt/no-env-in-hooks
      if (process.client) {
        const breakPoint = 992
        this.showMainNavigation = !(window.innerWidth < breakPoint)
        window.addEventListener('resize', () => {
          this.showMainNavigation = !(window.innerWidth < breakPoint)
        })
      } else {
        this.showMainNavigation = false
      }
    }
  }
</script>
<style>

</style>
