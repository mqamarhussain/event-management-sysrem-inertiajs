<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <VueDatePicker
      :id="id"
      ref="input"
      v-model="selected"
      v-bind="{ ...$attrs, class: null }"
      class=""
      :class="{ error: error }"
      :label="vlabel"
      :action-row="{ showNow: true }"
    />
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import { v4 as uuid } from "uuid";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default {
  components: {
    VueDatePicker,
  },
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `datetime-picker-input-${uuid()}`;
      },
    },
    error: String,
    label: String,
    vlabel: String,
    modelValue: [Date, String],
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
