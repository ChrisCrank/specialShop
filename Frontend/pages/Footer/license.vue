<template>
  <div class="w-100">
    <div class="contentContainer container mb-5 mt-5">
      Work in progress
    </div>
  </div>
</template>
<script>
export default {
  head () {
    return this.$nuxtI18nHead({ addSeoAttributes: true })
  },
  layout: 'content',
}
</script>
<style scoped>
.contentContainer{
  padding-top:12em;
}
</style>
