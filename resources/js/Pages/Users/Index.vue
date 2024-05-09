<template>
  <div>
    <Head title="Users" />
    <h1 class="mb-8 text-3xl font-bold">Users</h1>
    <div class="flex items-center justify-between mb-6">
      <input-filter
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      ></input-filter>
      <Link v-if="$page.props.auth.user.permissions.includes('create_user')" class="btn-indigo" :href="route('users.create')">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;User</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Email</th>
          <th class="pb-4 pt-6 px-6">Roles</th>
          <th class="pb-4 pt-6 px-6">Actions</th>
        </tr>
        <tr
          v-for="user in users.data"
          :key="user.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100 border-t px-4"
        >
          <td class="p-4">
            <img
              v-if="user.photo"
              class="block -my-2 mr-2 w-5 h-5 rounded-full"
              :src="user.photo"
            />
            {{ user.name }}
            <icon
              v-if="user.deleted_at"
              name="trash"
              class="shrink-0 ml-2 w-3 h-3 fill-gray-400"
            />
          </td>
          <td class="p-4">
            {{ user.email }}
          </td>
          <td class="border-t px-4">
            <div class="flex flex-wrap">
              <span
                v-for="(role, index) in user.roles"
                :key="index"
                class="px-2 py-1 mr-2 mb-2 bg-blue-200 text-blue-800 rounded-full"
              >
                {{ role.name }}
              </span>
            </div>
          </td>
          <td class="w-px">
            <td class="w-px ">
            <div class="flex flex-row text-center justify-center">

              <Link v-if="$page.props.auth.user.permissions.includes('update_user')"
                :href="route('users.edit', user.id)"
                class="items-center p-3 inline hover:bg-gray-400 rounded-full"
                tabindex="-1"
              >
                <icon
                  name="edit"
                  class="block w-4 h-4 fill-blue-400 hover:fill-blue-800"
                />
              </Link>
              <button v-if="$page.props.auth.user.permissions.includes('delete_user')"
                @click="destroy(user.id)"
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
          </td>
        </tr>
        <tr v-if="users.length === 0">
          <td colspan="4">No users found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="users.links" />
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
import Pagination from "@/Shared/Pagination.vue";

export default {
  components: {
    Head,
    Icon,
    Link,
    InputFilter,
    Pagination,
  },
  layout: Layout,
  props: {
    filters: Object,
    users: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        user: this.filters.user,
        trashed: this.filters.trashed,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/users", pickBy(this.form), { preserveState: true });
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
