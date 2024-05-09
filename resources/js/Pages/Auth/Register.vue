<template>
  <Head title="Register" />
  <div class="flex items-center justify-center p-6 min-h-screen bg-indigo-800">
    <div class="w-full max-w-2xl">
      <logo class="block mx-auto w-full fill-white" height="50" />
      <form
        class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden"
        @submit.prevent="register"
      >
        <div class="px-10 py-12">
          <h1 class="text-center text-3xl font-bold">Welcome!</h1>
          <div class="mt-6 mx-auto w-24 border-b-2" />
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.first_name"
              :error="form.errors.first_name"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="First name"
              type="text"
              autofocus
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
              class="pb-8 pr-6 w-full"
              label="Email"
              type="email"
            />
            <text-input
              v-model="form.password"
              :error="form.errors.password"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Password"
              type="password"
            />

            <text-input
              v-model="form.password_confirmation"
              :error="form.errors.password_confirmation"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Confirm Password"
              type="password"
            />

            <label class="flex items-center mt-6 select-none" for="remember">
              <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
              <span class="text-sm">Remember Me</span>
            </label>
          </div>
        </div>
        <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
          <Link :href="route('login')">Already have account?</Link>
          <loading-button
            :loading="form.processing"
            class="btn-indigo ml-auto"
            type="submit"
            >Register</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Logo from "@/Shared/Logo.vue";
import TextInput from "@/Shared/TextInput.vue";
import LoadingButton from "@/Shared/LoadingButton.vue";

export default {
  components: {
    Head,
    LoadingButton,
    Logo,
    TextInput,
    Link,
  },
  data() {
    return {
      form: this.$inertia.form({
        first_name: "",
        last_name: "",
        email: "",
        password: "",
        password_confirmation: "",
        remember: false,
      }),
    };
  },
  methods: {
    register() {
      this.form.post(route("register.create"));
    },
  },
};
</script>
