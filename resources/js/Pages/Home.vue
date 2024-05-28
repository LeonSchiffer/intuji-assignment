<script setup>
import EventCreate from "@/Components/Events/EventCreate.vue"
import EventList from "@/Components/Events/EventList.vue"
import axios from "axios";
import { toast } from "vue3-toastify";
</script>
<template>
    <div class="p-10">
        <event-create @event-created="eventCreated" />
        <event-list :events="events" @event-deleted="eventDeleted" />
    </div>
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
                .catch(err => {
                    toast.error(err.response.data.message)
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
