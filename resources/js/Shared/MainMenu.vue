<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('dashboard')">
        <icon
          name="dashboard"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('dashboard') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
          "
        >
          Dashboard
        </div>
      </Link>
    </div>
    <div
      class="mb-4"
      v-if="$page.props.auth.user.permissions.includes('manage_attendee')"
    >
      <Link class="group flex items-center py-3" :href="route('attendees.requests')">
        <icon
          name="dashboard"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('attendees/requests')
              ? 'fill-white'
              : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('attendees/requests')
              ? 'text-white'
              : 'text-indigo-300 group-hover:text-white'
          "
        >
          Attendees requests
        </div>
      </Link>
    </div>

    <div class="mb-4" v-if="$page.props.auth.user.permissions.includes('view_user_list')">
      <Link class="group flex items-center py-3" :href="route('users.index')">
        <icon
          name="users"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('users') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('users') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
          "
        >
          Users
        </div>
      </Link>
    </div>
    <div
      class="mb-4"
      v-if="$page.props.auth.user.permissions.includes('view_venue_list')"
    >
      <Link class="group flex items-center py-3" :href="route('venues.index')">
        <icon
          name="office"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('venues') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('venues') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
          "
        >
          Venues
        </div>
      </Link>
    </div>

    <div class="mb-4" v-if="$page.props.auth.user.permissions.includes('create_event')">
      <!-- Main menu item -->
      <div
        class="group flex items-center py-3 cursor-pointer"
        @click="toggleCreateEventSubMenu"
      >
        <icon
          name="office"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('events/create')
              ? 'fill-white'
              : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('events') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
          "
        >
          Events
        </div>
        <!-- Dropdown arrow icon -->
        <icon name="chevron-down" class="ml-auto w-4 h-4" />
      </div>
      <!-- Submenu items -->
      <div v-show="showCreateEventSubMenu" class="pl-8">
        <Link class="group flex items-center py-3" :href="route('events.index')">
          <div
            :class="
              isUrl('events') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
            "
          >
            List
          </div>
        </Link>
        <Link class="group flex items-center py-3" :href="route('events.create')">
          <div
            :class="
              isUrl('events/create')
                ? 'text-white'
                : 'text-indigo-300 group-hover:text-white'
            "
          >
            Create new
          </div>
        </Link>
        <!-- Add more submenu items here if needed -->
      </div>
    </div>

    <div class="mb-4" v-if="$page.props.auth.user.permissions.includes('view_user_list')">
      <Link class="group flex items-center py-3" :href="route('roles.index')">
        <icon
          name="office"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('roles') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('roles') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
          "
        >
          Roles
        </div>
      </Link>
    </div>
    <div
      class="mb-4"
      v-if="$page.props.auth.user.permissions.includes('upcomming_event_list')"
    >
      <Link class="group flex items-center py-3" :href="route('attendee.upcomingEvents')">
        <icon
          name="office"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('upcomingEvents')
              ? 'fill-white'
              : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('upcomingEvents')
              ? 'text-white'
              : 'text-indigo-300 group-hover:text-white'
          "
        >
          Upcoming Events
        </div>
      </Link>
    </div>

    <div class="mb-4" v-if="$page.props.auth.user.permissions.includes('my_events')">
      <Link class="group flex items-center py-3" :href="route('my-events')">
        <icon
          name="office"
          class="mr-2 w-4 h-4"
          :class="
            isUrl('my-events') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('my-events') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
          "
        >
          My Events
        </div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import Icon from "@/Shared/Icon.vue";

export default {
  components: {
    Icon,
    Link,
  },
  data() {
    return {
      showCreateEventSubMenu: false,
    };
  },
  methods: {
    isUrl(url) {
      return this.$page.url.includes(url);
    },
    toggleCreateEventSubMenu() {
      this.showCreateEventSubMenu = !this.showCreateEventSubMenu;
    },
  },
};
</script>
