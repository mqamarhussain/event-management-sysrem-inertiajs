<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <quill-editor
      :toolbar="[
        ['bold', 'italic', 'underline', 'strike'], // toggled buttons
        ['blockquote', 'code-block'],
        ['link', 'image', 'video', 'formula'],

        [{ header: 1 }, { header: 2 }], // custom button values
        [{ list: 'ordered' }, { list: 'bullet' }, { list: 'check' }],
        [{ script: 'sub' }, { script: 'super' }], // superscript/subscript
        [{ indent: '-1' }, { indent: '+1' }], // outdent/indent
        [{ direction: 'rtl' }], // text direction

        [{ size: ['small', false, 'large', 'huge'] }], // custom dropdown
        [{ header: [1, 2, 3, 4, 5, 6, false] }],

        [{ color: [] }, { background: [] }], // dropdown with defaults from theme
        [{ font: [] }],
        [{ align: [] }],

        ['clean'], // remove formatting button
      ]"
      @textChange="textChange()"
      ref="q"
      theme="snow"
    />
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>
<script>
import { v4 as uuid } from "uuid";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

export default {
  components: {
    QuillEditor,
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
    modelValue: [String, Number, Boolean],
  },
  emits: ["update:modelValue"],
  data() {
    return {
      selected: this.modelValue,
    };
  },
  mounted() {
    this.$refs.q.setHTML(this.modelValue);
    console.log(`the component is now mounted.`);
  },
  methods: {
    textChange() {
      this.$emit("update:modelValue", this.$refs.q.getHTML());
    },
  },
};
</script>
