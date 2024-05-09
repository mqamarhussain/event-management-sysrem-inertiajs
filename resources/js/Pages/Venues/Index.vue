<template>
  <div>
    <Head title="Venues" />
    <h1 class="mb-8 text-3xl font-bold">Venues</h1>
    <div class="flex items-center justify-between mb-6">
      <input-filter
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      ></input-filter>
      <Link class="btn-indigo" href="venues/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Venues</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Venue</th>
          <th class="pb-4 pt-6 px-6">Address</th>
          <th class="pb-4 pt-6 px-6">Description</th>
        </tr>
        <tr
          v-for="venue in venues.data"
          :key="venue.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-indigo-500"
              :href="`/venues/${venue.id}/edit`"
            >
              {{ venue.venue }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="`/venues/${venue.id}/edit`"
              tabindex="-1"
            >
              {{ venue.address }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="`/venues/${venue.id}/edit`"
              tabindex="-1"
            >
              {{ venue.description }}
            </Link>
          </td>

          <td class="w-px border-t">
            <Link
              class="flex items-center px-4"
              :href="`/venues/${venue.id}/edit`"
              tabindex="-1"
            >
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="venues.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No venues found.</td>
        </tr>
      </table>
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

export default {
  components: {
    Head,
    Icon,
    Link,
    InputFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    venues: Object,
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
        this.$inertia.get("/venues", pickBy(this.form), { preserveState: true });
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
