<template>
  <div>
    <Head title="Attendees" />
    <h1 class="mb-8 text-3xl font-bold">Attendees</h1>

    <div class="bg-white rounded-md shadow overflow-x-auto min-h-100">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Customer</th>
          <th class="pb-4 pt-6 px-6">Event</th>
          <th class="pb-4 pt-6 px-6">Event Amount</th>
          <th class="pb-4 pt-6 px-6">Content</th>
          <th class="pb-4 pt-6 px-6">Amount Paid</th>
          <th class="pb-4 pt-6 px-6">Rquest Status</th>
          <th class="pb-4 pt-6 px-6">Created at</th>
          <th class="pb-4 pt-6 px-6">Updated at</th>
          <th class="pb-4 pt-6 px-6">Actions</th>
        </tr>
        <tr
          v-for="attendee in attendees.data"
          :key="attendee.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t p-3">
            {{ attendee.customer.first_name }}
            {{ attendee.customer.last_name }}
          </td>
          <td class="border-t p-3">
            {{ attendee.event.event }}
          </td>

          <td class="border-t p-3">
            {{ attendee.event.amount }}
          </td>
          <td class="border-t p-3">
            <div v-text="attendee.content"></div>
          </td>
          <td class="border-t p-3">
            {{ attendee.amount_paid }}
          </td>
          <td class="border-t p-3">
            <div v-if="attendee.status == '0'">New</div>
            <div v-if="attendee.status == '1'">Accepted</div>
            <div v-if="attendee.status == '2'">Declined</div>
          </td>
          <td class="border-t p-3">
            {{ formatDate(attendee.created_at) }}
          </td>
          <td class="border-t p-3">
            {{ formatDate(attendee.updated_at) }}
          </td>
          <td class="w-px border-t p-3">
            <button
              v-if="
                attendee.status != '1' &&
                $page.props.auth.user.permissions.includes('accept_attendee_requests')
              "
              @click="acceptRequest(attendee.id)"
              class="items-center p-2 inline text-blue-800 bg-blue-300 rounded"
              tabindex="-1"
            >
              Accept Rquest
            </button>
          </td>
        </tr>
        <tr v-if="attendees.data.length === 0">
          <td class="px-6 py-10 border-t text-center" colspan="8">No attendees found.</td>
        </tr>
      </table>
    </div>
    <div class="flex justify-center items-center">
      <pagination class="mt-6" :links="attendees.links" />
    </div>
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
import DropdownAction from "../../Shared/DropdownAction.vue";
import moment from "moment";
export default {
  components: {
    Head,
    Icon,
    Link,
    InputFilter,
    Pagination,
    DropdownAction,
  },
  layout: Layout,
  props: {
    filters: Object,
    attendees: Object,
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
        this.$inertia.get(route("attendees.requests"), pickBy(this.form), {
          preserveState: true,
        });
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
    destroy(attendee) {
      if (confirm("Are you sure you want to delete this attendee?")) {
        this.$inertia.delete(route("attendees.destroy", attendee));
      }
    },
    acceptRequest(attendee) {
      if (confirm("Are you sure you want to accept this request?")) {
        this.$inertia.post(route("attendees.requests.accept", { attendee }));
      }
    },
    formatDate(date) {
      return moment(date).format("YYYY-MM-DD HH:mm");
    },
  },
};
</script>
