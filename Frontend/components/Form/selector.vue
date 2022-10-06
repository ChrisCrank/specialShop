<template>
  <div>
    <div class="custom-selector" ref="elem" :tabindex="tabindex" @blur="open = false">
      <b-form-input
        v-if="allowTextInput"
        v-model="selected"
        class="selected"
        :class="{ open: open }"
        @blur="$refs.elem.focus()"
        @value="e => $emit('input',e)" @click="open = !open"
      />
      <div v-else class="selected" :class="{ open: open }" @click="open = !open">
        <template v-if="multiple">
          <div class="d-flex align-items-center flex-row flex-wrap">
            <div v-for="value in selectedValue.split(',')" class="valuePill">
              {{value}}
            </div>
          </div>
        </template>
        <template v-else>
          {{ selectedValue }}
        </template>
      </div>
      <div class="items" :class="{ selectHide: !open }">
        <div
          v-for="(option, i) of options"
          :key="i"
          :class="(selected.split($props.valueDelimiter).includes(option.value) ? 'activeItem' : '')"
          @click="
          selectItem(option.value)
        "
        >
          {{ option.name }}
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    value: {
      type: String,
      required: true
    },
    options: {
      type: Array,
      required: true,
      default: null
    },
    default: {
      type: String,
      required: false,
      default: null,
    },
    tabindex: {
      type: Number,
      required: false,
      default: 0,
    },
    multiple: {
      type: Boolean,
      required: false,
      default: false,
    },
    allowTextInput: {
      type: Boolean,
      required: false,
      default: false
    },
    valueDelimiter: {
      type: String,
      required: false,
      default: ','
    }
  },
  data() {
    return {
      open: false,
      selected: this.$props.value ? this.$props.value : (this.default ?? 'DE')
    };
  },
  computed: {
    selectedValue: {
      get: function () {
        const selected = this.selected.split(this.$props.valueDelimiter)
        let res = ''
        for(let i = 0; i < selected.length; i++){
          if(i !== 0)
            res += this.$props.valueDelimiter
          if(this.allowTextInput)
            res += this.options.find(e => e.value === selected[i])?.value
          else
            res += this.options.find(e => e.value === selected[i])?.name
        }
        return res
      },
      set: function (newValue) {
        if(this.allowTextInput) {
          this.selected = newValue
        }
      }
    },
  },
  methods: {
    selectItem (value) {
      if(this.$props.multiple){
        let match = false
        let selected = this.selected.split(this.$props.valueDelimiter)
        let res = ''
        for(let i = 0; i < selected.length; i++){
          if(value === selected[i])
            match = true
        }
        if(match){
          // remove item if not last
          if(selected.length !== 1)
            selected.splice(selected.findIndex(e => e === value),1)
        }else{
          selected.push(value)
        }
        this.selected = selected.join(this.$props.valueDelimiter)
      } else {
        this.selected = value;
        this.open = false;
      }
      this.$emit('input', value);
    }
  },
  watch: {
    selected (newVal) {
      this.$emit("input", newVal);
    }
  },
  mounted() {
    this.$emit("input", this.selected)
  }
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
.custom-selector {
  position: relative;
  width: 100%;
  text-align: left;
  outline: none;
  height: 47px;
  line-height: 47px;
}

.custom-selector .selected {
  background-color: var(--white);
  border-radius: 6px;
  border: 1px solid #ced4da;
  color: var(--dark);
  padding-left: 1em;
  cursor: pointer;
  user-select: none;
  transition:border 0.5s;
  min-height:100%;
}

.custom-selector .selected.open {
  border: 1px solid var(--primary);
  border-radius: 6px 6px 0px 0px;
}

.custom-selector .selected:after {
  position: absolute;
  content: "";
  top: 22px;
  right: 1em;
  width: 0;
  height: 0;
  border: 5px solid transparent;
  border-color: var(--dark) transparent transparent transparent;
}

.custom-selector .items {
  color: var(--dark);
  border-radius: 0px 0px 6px 6px;
  overflow: hidden;
  border-right: 1px solid var(--primary);
  border-left: 1px solid var(--primary);
  border-bottom: 1px solid var(--primary);
  border-color: var(--primary);
  position: absolute;
  background-color: var(--white);
  left: 0;
  right: 0;
  z-index: 99999;
  height:6em;
  overflow-y:auto;
  transition:0.3s;
}

.custom-selector .items div {
  color: var(--dark);
  padding-left: 1em;
  cursor: pointer;
  user-select: none;
}

.custom-selector .items div:hover {
  background-color: var(--primary);
  color: var(--light);
}

.selectHide {
  height: 0 !important;
  overflow:hidden !important;
  border-color: var(--dark)!important;
  border-bottom: 1px solid transparent !important;
}
.activeItem{
  background-color: darken($primary,10%) !important;
  color: var(--light) !important;
}
.valuePill{
  border-radius: 0.2rem;
  background-color:$primary;
  color:$light;
  margin:0.2rem;
  padding:0.2rem;
  width:max-content;
  line-height:normal;
}
</style>
