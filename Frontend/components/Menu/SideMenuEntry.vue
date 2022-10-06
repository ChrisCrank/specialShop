<template>
  <ul>
    <li v-for="child in childs">
      <NuxtLink :exact="true" :to="localePath($props.route + '/' + encodeURI(child.name))" :class="'px-4 font-weight-bold p-2 subMenuEntry ' + ($route.path === localePath($props.route) ? 'activeMenuEntry' : '')">
        {{ child.name }}
      </NuxtLink>
      <side-entry-entry
        v-if="child.childs"
        :childs="child.childs"
        :route="$props.route"
      />
    </li>
  </ul>
</template>
<script>
export default {
  name: "side-entry-entry",
  props: {
    childs: {
      type: Array,
      required: true
    },
    route: {
      type: String,
      required: true
    }
  },
}
</script>
<style>

</style>
