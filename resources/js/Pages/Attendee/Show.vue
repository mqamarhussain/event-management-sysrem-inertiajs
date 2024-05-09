<template>
  <div>
    <Head :title="`${event.event}`" />
    <h1 class="mb-8 text-3xl font-bold">{{ event.event }}</h1>
    <div class="max-w-3xl mx-auto bg-white rounded-md shadow overflow-hidden">
      <div class="pt-4 w-full">
        <img
          v-if="event.banner"
          class="block mx-auto w-auto h-40 border-gray-500 border border-solid"
          :src="event.banner"
        />
      </div>
      <div class="grid grid-cols-2 gap-6 p-8">
        <div class="text-lg font-semibold">Event:</div>
        <div class="text-lg">{{ event.event }}</div>
        <div class="text-lg font-semibold">Venue:</div>
        <div class="text-lg">{{ getVenueName(event.venue_id) }}</div>
        <div class="text-lg font-semibold">Event date:</div>
        <div class="text-lg">{{ formatDate(event.event_date) }}</div>
        <div class="text-lg font-semibold">Audience capacity:</div>
        <div class="text-lg">{{ event.audience_capacity }}</div>
        <div class="text-lg font-semibold">Amount:</div>
        <div class="text-lg">{{ event.amount }}</div>
        <div class="text-lg font-semibold">Payment type:</div>
        <div class="text-lg">{{ getPaymentType(event.payment_type) }}</div>
        <div class="text-lg font-semibold">Type:</div>
        <div class="text-lg">{{ getEventType(event.type) }}</div>
      </div>
      <div class="p-8">
        <div class="text-lg font-semibold mb-2">Description:</div>
        <div v-html="event.description" class="text-lg"></div>
      </div>
      <div
        class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
      >
        <button
          type="button"
          v-if="myEventsIds.includes(event.id)"
          disabled
          class="block text-center cursor-not-allowed px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none"
        >
          Request sent
        </button>
        <Link
          v-else
          :href="route('attendee.event.participate', event.id)"
          class="block text-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none"
          >Prticipate</Link
        >
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import moment from "moment";

export default {
  components: {
    Head,
    Link,
  },
  layout: Layout,
  props: {
    event: Object,
    venues: Array,
    myEventsIds: Array,
  },
  methods: {
    getVenueName(venueId) {
      const venue = this.venues.find((v) => v.id === venueId);
      return venue ? venue.venue : "";
    },
    getPaymentType(paymentType) {
      return paymentType === 1 ? "Free" : "Paid";
    },
    getEventType(eventType) {
      return eventType === 1 ? "Public" : "Private";
    },
    formatDate(date) {
      return moment(date).format("YYYY-MM-DD HH:mm");
    },
  },
};
</script>
