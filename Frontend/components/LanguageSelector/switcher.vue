<template>
  <b-nav-item-dropdown :text="$t('menu.lang')" right menu-class="bg-white" :class="($props.simply ? 'simply' : '' )" >
    <template #button-content>
      <img :src="'/lang/' + currentLanguage.shortName + '.png'" alt="">
    </template>
    <b-dropdown-item class="customNavItemActive"><img :src="'/lang/' + currentLanguage.shortName + '.png'" alt=""> {{ currentLanguage.shortName }}</b-dropdown-item>
    <b-dropdown-item class="customNavItem" v-for="locale in availableLocales" :key="locale.code" @click.prevent="changeLocale(locale)" :href="switchLocalePath(locale.code)">
      <img :src="'/lang/' + locale.shortName + '.png'" alt=""> {{ locale.shortName }} ({{locale.name}})
    </b-dropdown-item>
  </b-nav-item-dropdown>
</template>
<script>
export default {
  props: {
    simply: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    availableLocales () {
      return this.$i18n.locales.filter(i => i.code !== this.$i18n.locale)
    },
    currentLanguage () {
      return this.$i18n.locales.filter(i => i.code === this.$i18n.locale)?.[0]
    }
  },
  methods: {
    async changeLocale (locale) {
      await this.$store.dispatch('User/changeLocale', locale.iso)
      window.location.href = this.switchLocalePath(locale.code)
      // await this.$router.push(this.switchLocalePath(locale.code))
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~/assets/main.scss';
.simply{
  background-color:transparent;
  list-style: none;
}
</style>
