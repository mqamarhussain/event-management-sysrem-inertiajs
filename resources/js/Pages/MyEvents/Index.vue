<template>
  <div>
    <Head title="My Events" />
    <h1 class="mb-8 text-3xl font-bold">My Events</h1>

    <div class="bg-white rounded-md shadow overflow-x-auto min-h-40">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Venue</th>
          <th class="pb-4 pt-6 px-6">Event</th>
          <th class="pb-4 pt-6 px-6">Event date</th>
          <th class="pb-4 pt-6 px-6">Type</th>
        </tr>
        <tr
          v-for="attendee in attendees.data"
          :key="attendee.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t p-4">
            {{ attendee.venue }}
          </td>
          <td class="border-t p-4">
            {{ attendee.event }}
          </td>
          <td class="border-t p-4">
            {{ attendee.event_date }}
          </td>
          <td class="border-t p-4 text-center">
            <span
              class="rounded bg-yellow-200 text-yellow-800 text-center p-2"
              v-if="attendee.status == '0'"
            >
              Pending
            </span>
            <span
              class="rounded bg-green-200 text-green-800 text-center p-2"
              v-if="attendee.status == '1'"
            >
              Accepted
            </span>
            <span
              class="rounded bg-red-200 text-red-800 text-center p-2"
              v-if="attendee.status == '2'"
            >
              Declined
            </span>
          </td>
        </tr>
        <tr v-if="attendees.data.length === 0">
          <td class="px-6 py-10 border-t text-center" colspan="8">No events found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="attendees.links" />
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Icon from "@/Shared/Icon.vue";
import Layout from "@/Shared/Layout.vue";
import InputFilter from "@/Shared/InputFilter.vue";
import Pagination from "@/Shared/Pagination.vue";

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
  },
  layout: Layout,
  props: {
    attendees: Object,
  },
};
</script>
