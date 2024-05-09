<template>
  <FullCalendar
    :plugins="calendarPlugins"
    :initialView="calendarView"
    :events="calendarEvents"
  />
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";

export default {
  components: {
    FullCalendar,
  },
  data() {
    return {
      calendarPlugins: [dayGridPlugin],
      calendarView: "dayGridMonth",
      calendarEvents: [
        { title: "Event 1", date: "2024-05-01" },
        { title: "Event 2", date: "2024-05-05" },
      ],
    };
  },
};
</script>
