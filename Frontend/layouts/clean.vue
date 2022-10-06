<template>
  <div class="main-content">
    <div class="main-content-area">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 d-none d-md-flex bg-image image" />
          <nuxt/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'clean',
  data () {
    return {

    }
  }
}
</script>
<style lang="scss" scoped>
.image {
  min-height: 100vh;
}
.main-content{
  min-height: 100vh;
  display:flex;
  justify-content: center;
  flex-wrap:wrap;
  background-color: #ffffff;
}
.main-content-area{
  align-self: flex-start;
  width:100%;
}
@media screen and (min-width:641px),
screen and (min-width:961px),
screen and (min-width:1025px),
screen and (min-width:1281px)
{
  .main-content-area{
    min-height: 450px;
  }
}
</style>
