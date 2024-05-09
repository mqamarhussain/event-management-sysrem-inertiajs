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
      <form @submit.prevent="store" class="relative">
        <!-- Add relative positioning to the form -->
        <div class="flex flex-wrap -mb-8 -mr-6 p-8 pb-50">
          <text-input
            v-model="form.name"
            :error="form.errors.name"
            class="pb-8 pr-6 w-full"
            label="Role"
          />
          <v-select-input
            :options="permissions"
            :reduce="(permission) => permission.id"
            v-model="form.permissions"
            :error="form.errors.permissions"
            class="pb-8 pr-6 w-full"
            label="Permissions"
            vlabel="name"
            multiple
            style="z-index: 999"
          />
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <button
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            Delete Role
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-indigo ml-auto"
            type="submit"
          >
            Update Role
          </loading-button>
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
    role: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        _method: "put",
        name: this.role.name,
        permissions: this.role.permissions,
      }),
    };
  },
  methods: {
    store() {
      this.form.post(route("roles.update", this.role.id));
    },
    destroy() {
      if (confirm("Are you sure you want to delete this role?")) {
        this.$inertia.delete(route("roles.destroy", this.role.id));
      }
    },
  },
};
</script>
