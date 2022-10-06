<template>
  <b-table striped hover :items="categories" :fields="$props.fields">
    <template #cell(createdAt)="data">
      {{ (data.item.createdAt) ? data.item.createdAt.toLocaleString($i18n.locale) : '' }}
    </template>
    <template #cell(active)="data">
      <fa v-if="data.item.active" class="text-success" :icon="'check'" />
      <fa v-if="!data.item.active" class="text-danger" :icon="'times'" />
    </template>
    <template #row-details="data">
      <recursive-category v-if="data.item.childs && data.item.childs.length" :el="data.item.childs" :fields="$props.fields" :path="$props.path" />
    </template>
    <template #cell(actions)="data">
      <b-button variant="warning" title="Products" @click="$router.push(localePath('/Portal/Product?categoryId='+data.item.id))"><fa :icon="'blog'" /></b-button>
      <b-button variant="success" title="edit" @click="$router.push(localePath($props.path+'/'+data.item.id+'/edit/'))"><fa :icon="'edit'" /></b-button>
      <b-button variant="danger" title="delete" @click="$router.push(localePath($props.path+'/'+data.item.id+'/delete/'))"><fa :icon="'trash'" /></b-button>
      <b-button v-if="data.item.childs && data.item.childs.length" variant="info"  @click="data.toggleDetails">
        <fa v-if="data.detailsShowing" icon="eye-slash"/>
        <fa v-else icon="eye"/>
      </b-button>
    </template>
  </b-table>
</template>
<script>

export default{
  name: "recursive-category",
  props: {
    el: {
      type: Array,
      required: true
    },
    fields: {
      type: Array,
      required: true
    },
    path: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      categories: []
    }
  },
  mounted() {
    this.categories = JSON.parse(JSON.stringify(this.$props.el))
  }
}
</script>
