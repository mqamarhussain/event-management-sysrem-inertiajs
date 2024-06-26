<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/users">Users</Link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.first_name }} {{ form.last_name }}
      </h1>
      <img v-if="user.photo" class="block ml-4 w-8 h-8 rounded-full" :src="user.photo" />
    </div>
    <trashed-message
      v-if="user.deleted_at && $page.props.auth.user.permissions.includes('restore_user')"
      class="mb-6"
      @restore="restore"
    >
      This user has been deleted.
    </trashed-message>
    <div class="max-w-3xl mx-auto bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.first_name"
            :error="form.errors.first_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="First name"
          />
          <text-input
            v-model="form.last_name"
            :error="form.errors.last_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Last name"
          />
          <text-input
            v-model="form.email"
            :error="form.errors.email"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Email"
          />
          <text-input
            v-model="form.password"
            :error="form.errors.password"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="password"
            autocomplete="new-password"
            label="Password"
          />
          <v-select-input
            v-if="$page.props.auth.user.permissions.includes('update_user')"
            :options="roles"
            :reduce="(role) => role.id"
            v-model="form.roles"
            :error="form.errors.roles"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Roles"
            vlabel="name"
            multiple
            style="z-index: 999"
          />

          <file-input
            v-model="form.photo"
            :error="form.errors.photo"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label="Photo"
          />
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button
            :loading="form.processing"
            class="btn-indigo ml-auto"
            type="submit"
            >Update User</loading-button
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
import FileInput from "@/Shared/FileInput.vue";
import SelectInput from "@/Shared/SelectInput.vue";
import LoadingButton from "@/Shared/LoadingButton.vue";
import TrashedMessage from "@/Shared/TrashedMessage.vue";
import VSelectInput from "@/Shared/VSelectInput.vue";
export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    VSelectInput,
  },
  layout: Layout,
  props: {
    user: Object,
    roles: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        _method: "put",
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        email: this.user.email,
        password: "",
        roles: this.user.roles,
        photo: null,
      }),
    };
  },
  methods: {
    update() {
      this.form.post(`/users/${this.user.id}`, {
        onSuccess: () => this.form.reset("password", "photo"),
      });
    },
    restore() {
      if (confirm("Are you sure you want to restore this user?")) {
        this.$inertia.put(`/users/${this.user.id}/restore`);
      }
    },
  },
};
</script>
