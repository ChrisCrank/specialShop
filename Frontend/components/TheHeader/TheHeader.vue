<template>
  <div v-if="user">
    <b-navbar type="light" variant="white" class="d-flex align-items-center justify-content-between flex-wrap flex-row">
      <div v-if="mobile" class="mr-2" v-b-toggle.sidebar-variant>
        <fa icon="bars" />
      </div>
      <b-navbar-brand href="#" @click="$router.push(localePath('/'))">
        <img src="/logo_black.png" class="caption-brand" alt="nos-index">
      </b-navbar-brand>
      <b-navbar-nav class="justify-content-end">
        <b-nav-item-dropdown :text="$t('header.user')" right menu-class="bg-white">
          <template #button-content>
            <em>{{ $t('header.user') }}</em>
          </template>
          <b-dropdown-item class="customNavItem" href="#" @click="$router.push(localePath('/Account'))"><fa icon="user-circle" class="mr-2" />{{ $t('header.user-account') }}</b-dropdown-item>
          <b-dropdown-item class="customNavItem" href="#" @click="logoutUser"><fa icon="sign-out-alt" class="mr-2" />{{ $t('header.user-logout') }}</b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav>
    </b-navbar>
  </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
  props: {
    mobile: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    ...mapGetters({
      user: 'User/getUser'
    })
  },
  methods: {
    logoutUser () {
      this.$store.dispatch('User/logoff').then(() => {
        this.$router.push(this.localePath('/login?logoff=true'));
      });
    }
  }
}
</script>
<style lang="scss">
  .caption-brand{
    height:25px;
  }
</style>
