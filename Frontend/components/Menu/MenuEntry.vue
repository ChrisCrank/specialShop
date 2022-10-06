<template>
  <component :is="($props.isChild) ? 'ul' : 'div'" :class="(!$props.isChild) ? 'd-inline' :''">
    <li v-for="child in childs" >
      <NuxtLink :to="localePath($props.route + '/' + ($props.seo ? encodeURI(child.name) : child.id))" :class="$props.mobile ? 'w-100 d-block p-2 menuEntry mobileMenuEntry ' : 'px-4 font-weight-bold p-2 menuEntry ' + ($route.path === localePath($props.route) ? 'activeMenuEntry' : '')">
        {{ child.name }} <fa v-if="child.childs.length" icon="angle-right"/>
      </NuxtLink>
      <menu-entry
        v-if="child.childs"
        :childs="child.childs"
        :route="$props.route"
        :seo="$props.seo"
        :is-child="true"
        :mobile="$props.mobile"
      />
    </li>
  </component>
</template>
<script>
export default {
  name: "menu-entry",
  props: {
    childs: {
      type: Array,
      required: true
    },
    isChild: {
      type: Boolean,
      required: true
    },
    route: {
      type: String,
      required: true
    },
    mobile: {
      type: Boolean,
      required: false
    },
    seo: {
      type: Boolean,
      required: false,
      default: () => false
    }
  },
}
</script>
<style>

</style>
