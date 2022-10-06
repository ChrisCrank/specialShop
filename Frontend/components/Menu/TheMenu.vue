<template>
  <div class="w-100 d-flex justify-content-between menuContainer">
    <div class="d-none d-lg-flex w-100 flex-row flex-nowrap">
      <div class="d-flex justify-content-center align-items-center pl-3 cursorPointer" @click="$router.push(localePath('/'))">
        <img src="/logo.png" alt="logo" width="200">
      </div>
      <div class="justify-content-between flex-column w-100" style="margin-left:-100px">
        <div class="d-flex flex-row flex-nowrap login justify-content-end">
          <div @click="$store.dispatch('Cart/openCart')" class="mobileMenuEntry menuEntry d-flex justify-content-center align-items-center p-2 cursorPointer">
            <fa icon="cart-shopping"></fa>
          </div>
          <language-selector :simply="true" :class="'mobileMenuEntry menuEntry '" />
          <template v-if="!user">
            <NuxtLink :to="localePath('/Login')" :class="'mobileMenuEntry p-2 menuEntry '">{{ $t('menu.login.name')}}</NuxtLink>
            <NuxtLink :to="localePath('/Register')" :class="'mobileMenuEntry p-2 menuEntry '">{{ $t('menu.register.name')}}</NuxtLink>
          </template>
          <template v-if="user">
            <div :class="'cursorPointer mobileMenuEntry p-2 menuEntry '" @click="logoff" :title="$t('menu.logoff.name')"><fa icon="arrow-right-from-bracket" /></div>
            <NuxtLink :to="localePath('/Account')" :class="'mobileMenuEntry p-2 menuEntry '" :title="$t('menu.account.name')"><fa icon="user" /></NuxtLink>
          </template>
          <template v-if="user && user.isAdmin">
            <NuxtLink :to="localePath('/Portal')" :class="'mobileMenuEntry p-2 menuEntry '" :title="$t('menu.admin.name')"><fa icon="hammer" /></NuxtLink>
          </template>
        </div>
        <div class="main-navigation-container d-flex flex-row flex-nowrap menuItem w-100 justify-content-center align-items-center">
          <ul class="main-navigation customNavigation">
            <template v-for="(entry,index) in entries">
              <li v-if="!entry.childs" :key="'menu' + index">
                <NuxtLink v-if="!entry.childs" :to="localePath(entry.route)" :class="'px-4 font-weight-bold p-2 menuEntry '" :exact="entry.exact">{{ entry.name }} <fa v-if="entry.childs" icon="angle-down"></fa></NuxtLink>
              </li>
              <menu-entry v-else :is-child="false" :route="entry.route" :childs="entry.childs" :seo="entry.seo" />
            </template>
          </ul>
        </div>
      </div>
    </div>
    <div class="d-sm-flex d-lg-none d-flex justify-content-between align-items-center w-100">
      <fa icon="bars" class="cursorPointer p-2 mobileIcon" style="font-size:2em" v-b-toggle.mobileSidebar></fa>
      <div class="mobileTitle">Special Shop</div>
      <div class="d-flex flex-row flex-nowrap">
        <div @click="$store.dispatch('Cart/openCart')" class="mobileMenuEntry menuEntry d-flex justify-content-center align-items-center p-2 cursorPointer">
          <fa icon="cart-shopping"></fa>
        </div>
        <language-selector :simply="true" :class="'p-2 menuEntry mobileMenuEntry '" />
      </div>
      <b-sidebar id="mobileSidebar" title="SpecialShop" :backdrop-variant="'primary'" bg-variant="dark" backdrop shadow dark>
        <template #header>
          <img src="/logo.png" alt="logo" width="200">
        </template>
        <div class="py-2 d-flex flex-column">
          <ul class="p-0">
            <template v-for="(entry,index) in entries">
              <NuxtLink v-if="!entry.childs" :to="localePath(entry.route)" :class="'w-100 d-block p-2 menuEntry mobileMenuEntry '" :exact="entry.exact">{{ entry.name }} <fa v-if="entry.childs" icon="angle-down"></fa></NuxtLink>
              <menu-entry v-else :is-child="false" :route="entry.route" :childs="entry.childs" :seo="entry.seo" :mobile="true" />
            </template>
          </ul>
          <hr class="w-100 border-light">
          <template v-if="!user">
            <NuxtLink :to="localePath('/login')" :class="'mobileMenuEntry p-2 menuEntry '">{{ $t('menu.login.name')}}</NuxtLink>
            <NuxtLink :to="localePath('/register')" :class="'mobileMenuEntry p-2 menuEntry '">{{ $t('menu.register.name')}}</NuxtLink>
          </template>
          <template v-if="user">
            <div :class="'cursorPointer mobileMenuEntry p-2 menuEntry '" @click="logoff"><fa icon="arrow-right-from-bracket" /> {{ $t('menu.logoff.name')}}</div>
            <NuxtLink :to="localePath('/Account')" :class="'mobileMenuEntry p-2 menuEntry '"><fa icon="user" /> {{ $t('menu.account.name')}}</NuxtLink>
          </template>
          <template v-if="user && user.isAdmin">
            <NuxtLink :to="localePath('/Portal')" :class="'mobileMenuEntry p-2 menuEntry '"><fa icon="hammer" /> {{ $t('menu.admin.name')}}</NuxtLink>
          </template>
        </div>
      </b-sidebar>
    </div>
  </div>
</template>

<script>
import LanguageSelector from './../LanguageSelector/switcher'
import {mapGetters} from "vuex";
import MenuEntry from "@/components/Menu/MenuEntry";
export default {
  components: {
    LanguageSelector,
    MenuEntry
  },
  computed: {
    ...mapGetters({
      user: 'User/getUser',
      categoriesSorted: 'Categories/getCategoriesSorted',
    }),
    entries () {
      return [
        { route: '/', name: this.$t('menu.home.name'), exact: true,seo: false },
        { route: '/Category', name: this.$t('menu.category.name'), childs: this.categoriesSorted, exact: false, seo: true }
      ]
    }
  },
  methods: {
    async logoff(){
      await this.$store.dispatch('User/logoff')
      this.$router.push('/Login?logoff=true')
    }
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
  .menuEntry{
    text-decoration:none;
    color: $white;
    transition: 0.2s;
  }
  .nuxt-link-active{
    color:$primary !important;
  }
  .menuEntry:hover{
    text-decoration: none;
    color:lighten($primary,30%);
  }
  .mobileMenuEntry{
    transition: 0.2s;
  }
  .mobileMenuEntry:hover{
    background-color:lighten($primary, 30%);
    color:$white;
  }
  .menuContainer{
    position:sticky;
    top:0;
    background-color:rgba(0,0,0,.8);
    height:80px;
    z-index:200;
  }
  .mobileIcon{
    transition:0.2s;
  }
  .mobileIcon:hover{
    color:lighten($primary, 30%);
  }
  .mobileTitle{
    color: $primary;
    font-size:2em;
  }


.main-navigation-container {
  ul {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color:transparent;
  }
  ul li {
    display: block;
    position: relative;
    float: left;
    background-color:transparent;
  }
  li ul {
    display: none;
    padding-top:0.5em;
  }

  ul li a {
    display: block;
    padding: 1em;
    text-decoration: none;
    white-space: nowrap;
    color: #fff;
  }

  ul li a:hover {
    background: $secondary;
  }

  li:hover > ul {
    display: block;
    position: absolute;
  }

  li:hover li {
    float: none;
  }

  li:hover a {
    background-color:rgba(0,0,0,.8);
  }

  li:hover li a:hover {
    background: $secondary;
  }

  .main-navigation li ul li {
    border-top: 0;
  }

  ul ul ul {
    left: 100%;
    top: 0;
  }

  ul:before,
  ul:after {
    content: " "; /* 1 */
    display: table; /* 2 */
  }

  ul:after {
    clear: both;
  }
}
</style>
