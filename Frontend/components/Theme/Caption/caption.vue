<template>
  <div class="col-12 d-flex justify-content-center mb-4">
    <h4 class="customPageHeader text-white text-uppercase pl-4 pr-4 pt-2 pb-2"><slot></slot>{{ text }}</h4>
  </div>
</template>
<script>
export default {
  props: {
    text: {
      type: String,
      required: true,
    }
  }
}
</script>
<style scoped>
.customPageHeader{
  font-size:3em;
  font-weight:bold;
}
</style>
