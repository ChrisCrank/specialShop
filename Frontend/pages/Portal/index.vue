<template>
  <div class="w-100 h-100 d-flex justify-content-center align-items-center">
    Shop Statisics
  </div>
</template>
<script>
import adminAuthRequiredPage from "~/mixins/Page/adminAuthRequiredPage";
export default {
  mixins: [adminAuthRequiredPage],
  layout: 'admin'
}
</script>
