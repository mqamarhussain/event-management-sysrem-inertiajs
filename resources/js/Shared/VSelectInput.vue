<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <v-select
      :id="id"
      ref="input"
      v-model="selected"
      v-bind="{ ...$attrs, class: null }"
      class=""
      :class="{ error: error }"
      :label="vlabel"
    />
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import { v4 as uuid } from "uuid";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  components: {
    vSelect,
  },
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `select-input-${uuid()}`;
      },
    },
    error: String,
    label: String,
    vlabel: String,
    modelValue: [String, Number, Boolean, Array],
  },
  emits: ["update:modelValue"],
  data() {
    return {
      selected: this.modelValue,
    };
  },
  watch: {
    selected(selected) {
      this.$emit("update:modelValue", selected);
    },
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    },
    select() {
      this.$refs.input.select();
    },
  },
};
</script>
<style type="css">
.vs__dropdown-menu {
  z-index: 9999 !important;
  position: relative;
}
</style>
