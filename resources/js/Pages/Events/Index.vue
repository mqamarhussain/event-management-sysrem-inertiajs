<template>
  <div>
    <Head title="Events" />
    <h1 class="mb-8 text-3xl font-bold">Events</h1>
    <div class="flex items-center justify-between mb-6">
      <input-filter
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      ></input-filter>
      <Link class="btn-indigo" :href="route('events.create')">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Event</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Venue</th>
          <th class="pb-4 pt-6 px-6">Event</th>
          <th class="pb-4 pt-6 px-6">Banner</th>
          <th class="pb-4 pt-6 px-6">Event date</th>
          <th class="pb-4 pt-6 px-6">Type</th>
          <th class="pb-4 pt-6 px-6">Audience capacity</th>
          <th class="pb-4 pt-6 px-6">Payment type</th>
          <th class="pb-4 pt-6 px-6">Amount</th>
          <th class="pb-4 pt-6 px-6">Actions</th>
        </tr>
        <tr
          v-for="event in events.data"
          :key="event.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-indigo-500"
              :href="route('venues.edit', event.venue_id)"
            >
              {{ event.venue }}
            </Link>
          </td>
          <td class="border-t">
            {{ event.event }}
          </td>

          <td class="border-t">
            <img class="w-20 h-auto" :src="event.banner_url" :alt="event.event" />
          </td>
          <td class="border-t">
            {{ event.event_date }}
          </td>
          <td class="border-t">
            <div v-if="event.type == '1'">Public</div>
            <div v-if="event.type == '2'">Private</div>
          </td>
          <td class="border-t">
            {{ event.audience_capacity }}
          </td>
          <td class="border-t">
            {{ event.payment_type ? "Free" : "Paid" }}
          </td>
          <td class="border-t">
            {{ event.amount }}
          </td>

          <td class="w-px border-t">
            <div class="flex flex-row text-center justify-center">
              <Link
                :href="route('events.show', event.id)"
                class="items-center p-3 inline hover:bg-gray-400 rounded-full"
                tabindex="-1"
              >
                <icon
                  name="view"
                  class="block w-4 h-4 fill-blue-400 hover:fill-blue-800"
                />
              </Link>
              <Link
                :href="route('events.edit', event.id)"
                class="items-center p-3 inline hover:bg-gray-400 rounded-full"
                tabindex="-1"
              >
                <icon
                  name="edit"
                  class="block w-4 h-4 fill-blue-400 hover:fill-blue-800"
                />
              </Link>
              <button
                @click="destroy(event.id)"
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
        <tr v-if="events.data.length === 0">
          <td class="px-6 py-10 border-t text-center" colspan="8">No events found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="events.links" />
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
    events: Object,
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
        this.$inertia.get("/events", pickBy(this.form), { preserveState: true });
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
    destroy(event) {
      if (confirm("Are you sure you want to delete this event?")) {
        this.$inertia.delete(route("events.destroy", event));
      }
    },
  },
};
</script>
