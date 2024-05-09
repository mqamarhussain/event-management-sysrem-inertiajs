<template>
  <div>
    <Head :title="`${form.event}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/events">Events</Link>
      <span class="text-indigo-400 font-medium">/</span> {{ form.event }}
    </h1>
    <div class="max-w-3xl mx-auto bg-white rounded-md shadow overflow-hidden">
      <div class="pt-4 w-full">
        <img
          v-if="event.banner_url"
          class="block mx-auto w-auto h-40 border-gray-500 border border-solid"
          :src="event.banner_url"
        />
      </div>
      <form @submit.prevent="update">
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
            label="Photo"
          />

          <q-textarea
            v-model="form.description"
            :error="form.errors.description"
            class="pb-8 pr-6 w-full min-h-80 mb-20"
            label="Description"
          />
          <div></div>
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
            Delete Event
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-indigo ml-auto"
            type="submit"
            >Update Event</loading-button
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
import SelectInput from "@/Shared/SelectInput.vue";
import LoadingButton from "@/Shared/LoadingButton.vue";
import VSelectInput from "@/Shared/VSelectInput.vue";
import QTextarea from "@/Shared/QTextarea.vue";
import moment from "moment";
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
    QTextarea,
    DatetimePicker,
  },
  layout: Layout,
  props: {
    event: Object,
    venues: Array,
  },
  remember: "form",
  data() {
    console.log(moment(this.event.event_date).format("YYYY-MM-DDTHH:mm:ss"));
    console.log(this.event.event_date);
    return {
      form: this.$inertia.form({
        _method: "put",
        venue_id: this.event.venue_id,
        event: this.event.event,
        description: this.event.description,
        event_date: this.event.event_date,
        type: this.event.type,
        audience_capacity: this.event.audience_capacity,
        payment_type: this.event.payment_type,
        amount: this.event.amount,
        banner: null,
      }),
    };
  },
  methods: {
    update() {
      this.form.post(route("events.update", this.event.id), {
        onSuccess: () => this.form.reset("banner"),
      });
    },
    destroy() {
      if (confirm("Are you sure you want to delete this event?")) {
        this.$inertia.delete(route("events.destroy", this.event.id));
      }
    },
  },
};
</script>
