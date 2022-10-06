<template>
  <div :class="classes">
    <div class="overflow-hidden m-2">
      <span class="myCustomCaptionHeader">{{ $t('sideMenu.title') }}</span>
    </div>
    <ul class="subMenu">
      <li v-for="(entry,index) in entries" :key="'menu' + index">
        <NuxtLink :to="localePath(route + '/' + encodeURI(entry.name))" :class="'px-4 font-weight-bold p-2 subMenuEntry '" :exact="true">{{ entry.name }}</NuxtLink>
        <side-menu-entry v-if="entry.childs" :route="route" :childs="entry.childs" />
      </li>
    </ul>
  </div>
</template>
<script>
import SideMenuEntry from "@/components/Menu/SideMenuEntry";
export default {
  props: {
    entries: {
      type: Array,
      required: true
    },
    route: {
      type: String,
      required: true
    },
    classes: {
      type: String,
      required: false,
      default: () => ''
    }
  },
  components: {
    SideMenuEntry
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
  .subMenu{
    list-style: none;
    padding:0;
    ul {
      list-style: none;
      padding-left:1em;
    }
    .subMenuEntry{
      text-decoration:none;
      color: lighten($primary,20%);
      transition: 0.2s;
    }
    .subMenuEntry:hover{
      color: $primary;
      font-weight:bold;
    }
  }
.myCustomCaptionHeader{
  position:relative;
  display:flex;
  justify-content: center;
  align-items:center;
  text-transform:uppercase;
  letter-spacing: 3px;
  &:before{
    position: absolute;
    top: 50%;
    left: -40px;
    right: 0;
    bottom: 0;
    content: '';
    width: 100px;
    height: 1px;
    background: rgba(0, 0, 0, 0.08)
  }
  &:after{
    position: absolute;
    top: 50%;
    right: -40px;
    bottom: 0;
    content: '';
    width: 100px;
    height: 1px;
    background: rgba(0, 0, 0, 0.09);
  }
}
</style>
