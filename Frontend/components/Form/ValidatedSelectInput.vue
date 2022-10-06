<template>
  <ValidationProvider
    ref="provider"
    v-slot="{ valid, errors }"
    :vid="vid"
    :class="classes + ' d-block'"
    :name="disableAutocomplete === true ? makeid(16) : ($attrs.name && $attrs.name.length ? $attrs.name : $attrs.placeholder)"
    :rules="rules">
    <b-form-select
      v-model="innerValue"
      v-bind="$attrs"
      :class="inputClasses + validationClass(getErrors(errors),valid)"
      :state="getErrors(errors)[0] ? false : null"
      :disabled="disabled"
      :name="disableAutocomplete === true ? makeid(16) : ($attrs.name && $attrs.name.length ? $attrs.name : $attrs.placeholder)"
      :autocomplete="disableAutocomplete === true ? 'false' : ''"
      :id="internalId"
      :multiple="$props.multiple"
    >
      <slot></slot>
    </b-form-select>
      <b-tooltip v-if="hasErrors(getErrors(errors))" :target="internalId" :class="invalidClass" variant="danger">{{ (invalidFeedback)?invalidFeedback:getErrors(errors)[0] }}</b-tooltip>
      <b-tooltip v-if="validFeedback && valid" :target="internalId" :class="validClass" variant="success">{{ validFeedback }}</b-tooltip>
  </ValidationProvider>
</template>

<script>
import { ValidationProvider } from 'vee-validate'

export default {
  components: {
    ValidationProvider
  },
  props: {
    vid: {
      type: String,
      default: ''
    },
    rules: {
      type: [Object, String],
      default: ''
    },
    value: {
      type: null,
      default: null
    },
    disableAutocomplete: {
      type: Boolean,
      default: false
    },
    hideErrors: {
      type: Boolean,
      default: false
    },
    validFeedback: {
      type: String,
      default: null
    },
    invalidFeedback: {
      type: String,
      default: null
    },
    multiple:{
      type: Boolean,
      default: false
    },
    classes: {
      type: String,
      default: ''
    },
    inputClasses: {
      type: String,
      default: ''
    },
    invalidClass: {
      type: String,
      default: ''
    },
    validClass: {
      type: String,
      default: ''
    },
    validInputClass: {
      type: String,
      default: 'validInput'
    },
    invalidInputClass: {
      type: String,
      default: 'invalidInput'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    id: {
      type: String,
      default: ''
    },
    errors: {
      type: Array,
      default: () =>  []
    },
    initialValidation: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    innerValue: null,
    internalId: '',
  }),
  watch: {
    innerValue (newVal) {
      this.$emit('input', newVal)
    },
    value (newVal) {
      this.innerValue = newVal
    },
    id (newVal) {
      this.internalId = (newVal?.length) ? newVal : this.makeid(16)
    }
  },
  methods: {
    validationClass (errors, valid) {
      return (errors && errors[0] ? ' '+this.$props.invalidInputClass : (valid ? ' '+this.$props.validInputClass : ''))
    },
    hasErrors(errors) {
      return !this.hideErrors && (this.invalidFeedback?.length || errors?.[0]?.length)
    },
    getErrors(errors) {
      return errors.concat(this.errors);
    },
    makeid (length) {
      let result = ''
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
      const charactersLength = characters.length
      for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
          charactersLength))
      }
      return result
    }
  },
  created () {
    if (this.value) {
      this.innerValue = this.value
    }
    this.internalId = (this.id?.length) ? this.id : this.makeid(16)
  },
  mounted () {
    if(this.$props.initialValidation) {
      this.$refs.provider.validate()
    }
  }
}
</script>
<style lang="scss">
  .invalidInput{
    border-color:var(--danger);
  }
  .validInput{
    border-color:var(--success);
  }
</style>
