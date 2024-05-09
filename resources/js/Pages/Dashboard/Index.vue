<template>
  <div>
    <Head title="Dashboard" />
    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>
    <div>
      <FullCalendar class="demo-app-calendar" :options="calendarOptions">
        <template v-slot:eventContent="arg">
          <div class="event-content">
            <div class="event-time">{{ arg.timeText }}</div>
            <div class="event-title">{{ arg.event.title }}</div>
          </div>
        </template>
      </FullCalendar>
    </div>
  </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import { router } from "@inertiajs/vue3";
export default {
  components: {
    FullCalendar,
    Head,
  },
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    permissions() {
      return this.$page.props.auth.user.permissions;
    },
  },
  layout: Layout,
  props: {
    events: Array,
  },
  data() {
    return {
      calendarOptions: {
        plugins: [
          dayGridPlugin,
          timeGridPlugin,
          interactionPlugin, // needed for dateClick
        ],
        headerToolbar: {
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay",
        },
        initialView: "dayGridMonth",
        events: this.events, // alternatively, use the `events` setting to fetch from a feed
        editable: false,
        selectable: true,
        selectMirror: false,
        dayMaxEvents: true,
        weekends: true,
        select: this.handleDateSelect,
        eventClick: this.handleEventClick,
        // eventsSet: this.handleEvents,
      },
      currentEvents: [],
    };
  },
  methods: {
    handleDateSelect(selectInfo) {
      // Check if the user has permission to create events
      if (this.permissions.includes("create_event")) {
        const startDate = selectInfo.startStr;
        router.visit(route("events.create", { event_date: startDate }));
      } else {
        // Handle unauthorized access
        console.log("You do not have permission to create events.");
      }
    },
    handleEventClick(selectInfo) {
      // Check if the user has permission to edit events
      const event = selectInfo.event.id;
      if (this.permissions.includes("update_event")) {
        router.visit(route("events.edit", event));
      } else if (this.permissions.includes("upcomming_event_list")) {
        router.visit(route("attendee.event.show", event));
      } else {
        console.log("You do not have permission to edit events.");
      }
    },
  },
};
</script>

<style scoped>
.event-content {
  display: flex;
  flex-direction: column;
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #f8f8f8;
  color: black;
  margin-bottom: 5px;
  cursor: pointer;
}

.event-time {
  font-weight: bold;
}

.event-title {
  font-style: italic;
}
</style>
