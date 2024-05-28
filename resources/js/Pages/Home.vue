<script setup>
import EventCreate from "@/Components/Events/EventCreate.vue"
import EventList from "@/Components/Events/EventList.vue"
import axios from "axios";
</script>
<template>
    <event-create @event-created="eventCreated" />
    <event-list :events="events" @event-deleted="eventDeleted" />
</template>
<script>
export default {
    data: () => ({
        events: []
    }),
    methods: {
        getEvents() {
            axios
                .get("/api/events")
                .then(response => {
                    this.events = response.data.data
                })
        },
        eventCreated(event) {
            this.events.push(event)
        },
        eventDeleted(event_id) {
            this.events = this.events.filter((item) => item.id != event_id)
        }
    },
    mounted() {
        this.getEvents()
    }
}
</script>
