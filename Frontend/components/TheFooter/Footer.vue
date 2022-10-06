<template>
  <div class="w-100 text-white bg-primary row m-0 p-3">
    <div class="col-4 d-flex justify-content-start align-items-center flex-column">
      <div class="font-weight-bold text-uppercase">Lorem ipsum.</div>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione, voluptates?</p>
    </div>
    <div class="col-4 d-flex justify-content-start align-items-center flex-column">
      <div class="font-weight-bold text-uppercase">Links</div>
      <NuxtLink
        :to="localePath(entry.route)"
        v-for="(entry,index) in entries"
        :key="'footer'+index"
        :active-class="''"
        class="font-weight-bold footerMenuEntry"
      >
        {{ entry.name }}
      </NuxtLink>
    </div>
    <div class="col-4 d-flex justify-content-start align-items-center flex-column">
      <div class="font-weight-bold text-uppercase">Contact Information</div>
      Westcoast, <br>
      Suite 721 New York NY 10016 <br>
      + 0180 54646<br>
      info@special.shop <br>
    </div>
    <div class="col-12">@copyright 2022 Christopher Wagner </div>
  </div>
</template>
<script>
export default {
  data () {
    return {
      entries: [
        { route: '/', name: this.$t('menu.home.name') },
        { route: '/Footer/agb', name: this.$t('menu.agb.name') },
        { route: '/Footer/license', name: this.$t('menu.license.name') },
        { route: '/Footer/data', name: this.$t('menu.data.name') },
      ]
    }
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
.footerMenuEntry{
  text-decoration:none;
  color: $white;
  transition: 0.2s;
}
.footerMenuEntry:hover{
  text-decoration: none;
  color:#779fc8;
}
</style>
