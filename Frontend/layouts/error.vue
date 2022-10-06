<template>
  <div>
    <div v-if="error.statusCode === 404" class="w-100 d-flex flex-column justify-content-center">
      <h1 class="text-center text-primary font-weight-bold">We Could not found the Page which you are looking for</h1>
      <div class="text-center">Dont be Sad we have sweet Cat Pics</div>
      <img src="http://placekitten.com/1920/600" alt="">
    </div>
    <div v-else>
      <h1>
        An error occurred
      </h1>
      <pre>{{ error }}</pre>
    </div>
    <div class="w-100 d-flex justify-content-center">
      <NuxtLink :to="localePath('/')" class="h1 text-primary">Back to Home</NuxtLink>
    </div>
  </div>
</template>

<script>
export default {
  props: ['error'],
  layout: 'error' // you can set a custom layout for the error page
}
</script>
