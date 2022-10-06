<template>
  <div class="main-content">
    <div :class="'main-content-header shadow ' + (!showMainNavigation ? 'mobileHeader' : '' )">
      <the-header :mobile="!showMainNavigation" />
    </div>
    <the-sidebar-mobile v-if="!showMainNavigation" />
    <div class="main-content-container">
      <the-sidebar class="shadow-right-sidebar" v-if="showMainNavigation"/>
      <div class="main-content-area" style="min-height:80vh">
        <nuxt/>
      </div>
    </div>
    <div class="main-content-footer">
      <the-footer />
    </div>
  </div>
</template>

<script>
  import TheMenu from "~/components/Menu/TheMenu";
  import TheFooter from "~/components/TheFooter/Footer";
  import TheSidebar from '~/components/TheSidebar/TheSidebar';
  import TheHeader from '~/components/TheHeader/TheHeader';
  import TheSidebarMobile from '~/components/TheSidebar/TheSidebarMobile';
  export default {
    name: 'Default',
    components: {
      TheMenu, TheFooter, TheSidebar, TheHeader, TheSidebarMobile
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
<style scoped lang="scss">
@import '~/assets/main.scss';
.mobileHeader{
  position:sticky;
  top:0;
}
.main-content{
  display: flex;
  flex-direction: column;
  background-color: $primary;
  background: linear-gradient(174deg, $primary 0%, $secondary 5%, $primary 62%, $secondary 99%);
  font-size: 0.875rem;
  position:relative;
}
.main-content-header{
  width:100%;
  z-index:700;
}
.main-content-container{
  flex: auto;
  display:flex;
  flex-direction: row;
  flex-wrap:nowrap;
  width:100%;
}
.main-content-area{
  align-self: flex-start;
  width:100%;
}
.main-content-footer{
  width:100%;
  z-index: 700;
}
.shadow-right-sidebar{
  box-shadow: 0rem 1rem 1rem rgba(0, 0, 0, 0.15) !important;
  z-index:700;
}
@media screen and (min-width:641px),
screen and (min-width:961px),
screen and (min-width:1025px),
screen and (min-width:1281px)
{
  .main-content-area{
    min-height: 450px;
  }
}
</style>
