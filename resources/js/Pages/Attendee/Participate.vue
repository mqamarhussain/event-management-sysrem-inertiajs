<template>
  <div>
    <Head :title="`${event.event}`" />
    <h1 class="mb-8 text-3xl font-bold">{{ event.event }}</h1>
    <div class="max-w-3xl mx-auto bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="confirm">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.amount_paid"
            :error="form.errors.amount_paid"
            class="pb-8 pr-6 w-full"
            label="Amount Paid"
            type="number"
            step="0.01"
          />
          <textarea-input
            v-model="form.content"
            :error="form.errors.content"
            class="pb-8 pr-6 w-full"
            label="Content / Description / Refrence"
          />
          <input type="hidden" v-model="form.event_id" />
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <Link
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            :href="route('attendee.upcomingEvents')"
          >
            Cancel
          </Link>
          <loading-button
            :loading="form.processing"
            class="btn-indigo ml-auto"
            type="submit"
            >Confirm</loading-button
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
import LoadingButton from "@/Shared/LoadingButton.vue";

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    TextareaInput,
    TextInput,
  },
  layout: Layout,
  remember: "form",
  props: {
    event: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        event_id: this.event.id,
        content: this.event.content,
        amount_paid: this.event.amount,
      }),
    };
  },
  methods: {
    confirm() {
      this.form.post(route("attendee.event.participate.confirm"));
    },
  },
};
</script>
