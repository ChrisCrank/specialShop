<template>
  <client-only placeholder="loading...">
    <div>
      <CKEditor v-model="contentHolder" :config="editorConfig" />
    </div>
  </client-only>
</template>

<script>
import CKEditor from "~/components/HtmlEditor/CKEditor";
export default {
  components: {
    CKEditor
    // 'ckeditor-nuxt': () => { if (process.client) { return import('@blowstack/ckeditor-nuxt') } },
  },
  props: {
    value: {
      type: String,
      default: '<h1>Überschrifft</h1><p>Paragraph1</p>',
    },
  },
  watch:{
    value(val){
      this.contentHolder = val
    },
    contentHolder(val){
      this.$emit('input', val)
      this.$emit('keyup', val)
    }
  },
  data() {
    return {
      editorConfig: {
        removePlugins: ['Title', 'ImageCaption'],
        simpleUpload: {
          uploadUrl: process.env.baseUrl + 'Admin/Image/add',
          headers: {
            'Bearer': this.$cookiz.get('access-token')
          }
        }
      },
      contentHolder: this.$props.value
    }
  },
  mount(){
    this.contentHolder = this.$props.value
  }
}
</script>
<style lang="scss">
.ck-toolbar-container, .ck-balloon-panel{
  z-index:200000 !important;
}
</style>
