<template>
  <div>
    <Head title="Roles" />
    <h1 class="mb-8 text-3xl font-bold">Roles</h1>
    <div class="flex items-center justify-between mb-6">
      <input-filter
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      ></input-filter>
      <Link class="btn-indigo" :href="route('roles.create')">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Role</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">
            <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" />
          </th>
          <th class="pb-4 pt-6 px-6">Id</th>
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Permissions</th>
          <th class="pb-4 pt-6 px-6">Actions</th>
        </tr>
        <tr
          v-for="role in roles.data"
          :key="role.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t px-6">
            <input
              type="checkbox"
              class="form-checkbox"
              :value="role.id"
              v-model="selectedRoles"
            />
          </td>
          <td class="border-t px-4">{{ role.id }}</td>
          <td class="border-t px-4">{{ role.name }}</td>
          <td class="border-t px-4">
            <div class="flex flex-wrap">
              <span
                v-for="(permission, index) in role.permissions"
                :key="index"
                class="px-2 py-1 mr-2 mb-2 bg-blue-200 text-blue-800 rounded-full"
              >
                {{ permission.name }}
              </span>
            </div>
          </td>
          <td class="w-px border-t">
            <div class="flex flex-row text-center justify-center">
              <Link
                :href="route('roles.show', role.id)"
                class="items-center p-3 inline hover:bg-gray-400 rounded-full"
                tabindex="-1"
              >
                <icon
                  name="view"
                  class="block w-4 h-4 fill-blue-400 hover:fill-blue-800"
                />
              </Link>
              <Link
                :href="route('roles.edit', role.id)"
                class="items-center p-3 inline hover:bg-gray-400 rounded-full"
                tabindex="-1"
              >
                <icon
                  name="edit"
                  class="block w-4 h-4 fill-blue-400 hover:fill-blue-800"
                />
              </Link>
              <button
                @click="destroy(role.id)"
                class="items-center p-3 inline hover:bg-gray-400 rounded-full"
                tabindex="-1"
              >
                <icon
                  name="trash"
                  class="block w-4 h-4 fill-red-400 hover:fill-red-800"
                />
              </button>
            </div>
          </td>
        </tr>
        <tr v-if="roles.data.length === 0">
          <td class="px-6 py-10 border-t text-center" colspan="5">No roles found.</td>
        </tr>
      </table>
    </div>
    <button
      v-if="this.selectedRoles.length"
      @click="deleteSelectedRoles"
      class="text-red-600 mt-4 hover:underline"
      tabindex="-1"
      type="button"
    >
      Delete Selected Roles
    </button>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Icon from "@/Shared/Icon.vue";
import pickBy from "lodash/pickBy";
import Layout from "@/Shared/Layout.vue";
import throttle from "lodash/throttle";
import mapValues from "lodash/mapValues";
import InputFilter from "@/Shared/InputFilter.vue";

export default {
  components: {
    Head,
    Icon,
    Link,
    InputFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    roles: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
      },
      selectedRoles: [],
      selectAll: false,
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/roles", pickBy(this.form), { preserveState: true });
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },

    destroy(role) {
      if (confirm("Are you sure you want to delete this role?")) {
        this.$inertia.delete(route("roles.destroy", role));
      }
    },
    deleteSelectedRoles() {
      if (this.selectedRoles.length > 0) {
        if (confirm("Are you sure you want to delete the selected roles?")) {
          this.$inertia.delete(route("roles.delete", { roleIds: this.selectedRoles }));
        }
      }
    },

    toggleSelectAll() {
      if (this.selectAll) {
        this.selectedRoles = this.roles.data.map((role) => role.id);
      } else {
        this.selectedRoles = [];
      }
    },
  },
};
</script>
