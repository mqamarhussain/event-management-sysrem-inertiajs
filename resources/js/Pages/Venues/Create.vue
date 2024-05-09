<template>
  <div>
    <Head title="Create Venue" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/venues">Venues</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl mx-auto bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.venue"
            :error="form.errors.venue"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Venue"
          />
          <text-input
            v-model="form.address"
            :error="form.errors.address"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Address"
          />
          <textarea-input
            v-model="form.description"
            :error="form.errors.description"
            class="pb-8 pr-6 w-full"
            label="Description"
          />
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button :loading="form.processing" class="btn-indigo" type="submit"
            >Create Venue</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import FileInput from "@/Shared/FileInput.vue";
import TextInput from "@/Shared/TextInput.vue";
import TextareaInput from "@/Shared/TextareaInput.vue";
import SelectInput from "@/Shared/SelectInput.vue";
import LoadingButton from "@/Shared/LoadingButton.vue";

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextareaInput,
  },
  layout: Layout,
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        venue: "",
        address: "",
        description: "",
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/venues");
    },
  },
};
</script>
