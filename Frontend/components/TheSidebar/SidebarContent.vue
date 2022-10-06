<template>
  <div class="w-100">
    <div :class="'customLinkItem ' + ($route.path === localePath(m.path) ? 'customLinkItemActove' : '')" v-for="m in menuPoints" :key="m.name" @click="$router.push(localePath(m.path))" v-if="m.if === true || user && user[m.if]">
      <div :class="'content ' + ($route.path === localePath(m.path) ? 'activeContent' : '')">
        <fa :icon="m.icon" />
        <span class="customMenuEntry">{{ m.name }}</span>
      </div>
      <div :class="'marker ' + ($route.path === localePath(m.path) ? 'activeMarker' : '')" :style="{backgroundColor: m.color}"></div>
    </div>
  </div>
</template>
<script>
import {mapGetters} from "vuex";
export default {
  computed:{
    ...mapGetters({
      user: 'User/getUser'
    })
  },
  data () {
    return {
      menuPoints: [
        { if: true, icon: "home", name: this.$t('sidebar.home'), color: "purple", path: "/Portal" },
        { if: 'isAdmin', icon: "blog", name: this.$t('sidebar.category'), color: "turquoise", path: "/Portal/Category" },
        { if: 'isAdmin', icon: "box", name: this.$t('sidebar.product'), color: "turquoise", path: "/Portal/Product" }
      ]
    }
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
.customLinkItem{
  margin:0.5rem;
  border-radius: 1.5rem;
  border:1px solid #ced4da;
  padding: 0.3rem 0.3rem 0.3rem 1rem;
  cursor: pointer;
}
.customLinkItemActove{
  background-color:$primary;
  color:var(--white);
}
.customLinkItem:hover{
  background-color:darken($primary,5);
  color:var(--white);
}
.customMenuEntry{

}
</style>
