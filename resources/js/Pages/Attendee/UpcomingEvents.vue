<template>
  <div class="max-w-6xl mx-auto p-6 bg-white rounded-md shadow overflow-hidden">
    <Head title="Events" />
    <h1 class="mb-8 text-3xl font-bold">Upcoming Events</h1>
    <div class="flex items-center justify-between mb-6">
      <input-filter
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      ></input-filter>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <div
        v-for="event in upcomingEvents.data"
        :key="event.id"
        class="bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
      >
        <img
          class="object-cover w-full h-64 md:h-48 rounded-t-lg"
          :src="event.banner_url"
          alt=""
        />
        <div class="p-6">
          <h5 class="text-lg font-semibold text-gray-800">{{ event.event }}</h5>

          <div class="flex items-center mt-2">
            <icon
              name="map"
              class="mr-2 w-4 h-4 fill-indigo-400 group-hover:fill-white"
            />
            <p class="text-sm text-gray-600">
              {{ event.venue.venue }}, {{ event.venue.address }}
            </p>
          </div>
          <div class="flex items-center mt-2">
            <icon
              name="dollar"
              class="mr-2 w-4 h-4 fill-indigo-400 group-hover:fill-white"
            />
            <p class="text-sm text-gray-600">
              {{ event.payment_type == "1" ? "Free" : event.amount }}
            </p>
          </div>

          <div class="flex items-center mt-2">
            <icon
              name="clock"
              class="mr-2 w-4 h-4 fill-indigo-400 group-hover:fill-white"
            />
            <p class="text-sm text-gray-600">{{ formatDate(event.event_date) }}</p>
          </div>

          <div class="mt-4">
            <Link
              :href="route('attendee.event.show', event.id)"
              class="block text-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none"
            >
              View more
              <span v-if="myEventsIds.includes(event.id)"> (Request sent)</span>
            </Link>
          </div>
        </div>
      </div>
    </div>
    <div
      v-if="upcomingEvents.data.length === 0"
      class="p-20 text-center text-red-500 bg-red-300"
    >
      No Upcoming Events
    </div>
    <div class="flex justify-center content-center">
      <pagination class="mt-6" :links="upcomingEvents.links" />
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import Icon from "@/Shared/Icon.vue";
import InputFilter from "@/Shared/InputFilter.vue";
import Pagination from "@/Shared/Pagination.vue";
import mapValues from "lodash/mapValues";

import pickBy from "lodash/pickBy";
import throttle from "lodash/throttle";
import moment from "moment";

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
    upcomingEvents: Object,
    myEventsIds: Array,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(route("attendee.upcomingEvents"), pickBy(this.form), {
          preserveState: true,
        });
      }, 150),
    },
  },
  methods: {
    formatDate(dateString) {
      return moment(dateString).format("dddd, MMMM Do YYYY, h:mm a");
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>

<style>
/* Add your custom styles here */
</style>
