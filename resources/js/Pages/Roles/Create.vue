<template>
  <div>
    <Head title="Create Role" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" :href="route('roles.index')"
        >Roles</Link
      >
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl mx-auto bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.name"
            :error="form.errors.name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Role"
          />
          <v-select-input
            :options="permissions"
            :reduce="(permission) => permission.id"
            v-model="form.permissions"
            :error="form.errors.permissions"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Permissions"
            vlabel="name"
            multiple
          />
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button :loading="form.processing" class="btn-indigo" type="submit"
            >Create Role</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import TextInput from "@/Shared/TextInput.vue";
import VSelectInput from "@/Shared/VSelectInput.vue";
import LoadingButton from "@/Shared/LoadingButton.vue";

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    VSelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    permissions: Array,
    role_date: String,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        name: "",
        permissions: [],
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/roles");
    },
  },
};
</script>
