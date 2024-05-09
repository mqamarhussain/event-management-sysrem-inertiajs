<template>
  <div>
    <Head :title="`${role.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/roles">Roles</Link>
      <span class="text-indigo-400 font-medium">/</span> {{ role.name }}
    </h1>
    <div class="max-w-3xl mx-auto bg-white rounded-md shadow overflow-hidden">
      <div class="grid grid-cols-2 gap-6 p-8">
        <div class="text-lg font-semibold">Role:</div>
        <div class="text-lg">{{ role.name }}</div>
        <div class="text-lg font-semibold">Permissions:</div>
        <div>
          <div class="flex flex-wrap">
            <span
              v-for="(permission, index) in role.permissions"
              :key="index"
              class="px-2 py-1 mr-2 mb-2 bg-blue-200 text-blue-800 rounded-full"
            >
              {{ permission.name }}
            </span>
          </div>
        </div>
      </div>

      <div
        class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
      >
        <button class="text-red-600 hover:underline" @click="destroy()">
          Delete Role
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";

export default {
  components: {
    Head,
    Link,
  },
  layout: Layout,
  props: {
    role: Object,
  },
  methods: {
    destroy() {
      if (confirm("Are you sure you want to delete the selected roles?")) {
        this.$inertia.delete(route("roles.destroy", this.role.id));
      }
    },
  },
};
</script>
