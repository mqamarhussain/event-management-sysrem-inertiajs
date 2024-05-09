<template>
  <div>
    <Head title="Create Event" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/events">Events</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl mx-auto bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.event"
            :error="form.errors.event"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Event"
          />
          <v-select-input
            :options="venues"
            :reduce="(venue) => venue.id"
            v-model="form.venue_id"
            :error="form.errors.venue_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Venue"
            vlabel="venue"
          />
          <datetime-picker
            :error="form.errors.event_date"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Event date"
            v-model="form.event_date"
          />
          <text-input
            type="number"
            step="0.01"
            v-model="form.audience_capacity"
            :error="form.errors.audience_capacity"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Audience capacity"
          />
          <text-input
            type="number"
            step="0.01"
            v-model="form.amount"
            :error="form.errors.amount"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Amount"
          />

          <select-input
            v-model="form.payment_type"
            :error="form.errors.payment_type"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Payment type"
          >
            <option :value="null" />
            <option value="1">Free</option>
            <option value="2">Paid</option>
          </select-input>
          <select-input
            v-model="form.type"
            :error="form.errors.type"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Type"
          >
            <option :value="null" />
            <option value="1">Public</option>
            <option value="2">Private</option>
          </select-input>
          <file-input
            v-model="form.banner"
            :error="form.errors.banner"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label="Banner"
          />
          <q-textarea
            v-model="form.description"
            :error="form.errors.description"
            class="pb-8 pr-6 w-full mb-20"
            label="Description"
          />
          <div></div>
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button :loading="form.processing" class="btn-indigo" type="submit"
            >Create Event</loading-button
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
import VSelectInput from "@/Shared/VSelectInput.vue";
import LoadingButton from "@/Shared/LoadingButton.vue";
import QTextarea from "../../Shared/QTextarea.vue";
import DatetimePicker from "../../Shared/DatetimePicker.vue";

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    VSelectInput,
    TextInput,
    TextareaInput,
    QTextarea,
    DatetimePicker,
  },
  layout: Layout,
  props: {
    venues: Array,
    event_date: String,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        venue_id: "",
        event: "",
        description: "",
        event_date: this.event_date,
        type: "",
        audience_capacity: "",
        payment_type: "",
        amount: "",
        banner: null,
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/events");
    },
  },
};
</script>
