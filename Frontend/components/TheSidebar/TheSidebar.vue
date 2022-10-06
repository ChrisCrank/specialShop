<template>
  <div class="bg-white desktopSidebar">
    <SidebarContent />
  </div>
</template>
<script>
import SidebarContent from './SidebarContent'
export default {
  components: {SidebarContent}
}
</script>
<style>
  .desktopSidebar{
    min-width:300px;
  }
</style>
