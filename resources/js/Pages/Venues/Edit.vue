<template>
  <div>
    <Head :title="`${form.venue}`" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-2xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/venues">Venues</Link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.venue }}
      </h1>
    </div>
    <div class="max-w-3xl mx-auto bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.venue"
            :error="form.errors.venue"
            class="pb-8 pr-6 w-full"
            label="Venue"
          />
          <text-input
            v-model="form.address"
            :error="form.errors.address"
            class="pb-8 pr-6 w-full"
            label="Address"
          />
          <textarea-input
            v-model="form.description"
            :error="form.errors.venue"
            class="pb-8 pr-6 w-full"
            label="Description"
          />
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <loading-button
              :loading="form.processing"
              class="btn-indigo ml-auto"
              type="submit"
              >Update Venue</loading-button
            >
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import TextInput from "@/Shared/TextInput.vue";
import TextareaInput from "@/Shared/TextareaInput.vue";
import LoadingButton from "@/Shared/LoadingButton.vue";

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    TextareaInput,
  },
  layout: Layout,
  props: {
    filters: Object,
    venue: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        _method: "put",
        venue: this.venue.venue,
        address: this.venue.address,
        description: this.venue.description,
      }),
    };
  },
  methods: {
    update() {
      this.form.post(`/venues/${this.venue.id}`, {
        onSuccess: () => this.form.reset("password", "photo"),
      });
    },
    destroy() {
      if (confirm("Are you sure you want to delete this venue?")) {
        this.$inertia.delete(`/venues/${this.venue.id}`);
      }
    },
  },
};
</script>
