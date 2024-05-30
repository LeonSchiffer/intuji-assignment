<script setup>
import EventCreate from "@/Components/Events/EventCreate.vue"
import EventList from "@/Components/Events/EventList.vue"
import axios from "axios";
import { toast } from "vue3-toastify";
</script>
<template>
    <div class="p-10">
        <event-create @event-created="eventCreated" />
        <event-list :events="events" @event-deleted="eventDeleted" @refresh-event-list="getEvents(true)" :eventListLoading="eventListLoading" />
    </div>
</template>
<script>
export default {
    data: () => ({
        events: [],
        eventListLoading: false
    }),
    methods: {
        getEvents(without_cache = false) {
            this.eventListLoading = true;
            axios
                .get("/api/events?without_cache=" + without_cache)
                .then(response => {
                    this.events = response.data.data
                })
                .catch(err => {
                    toast.error(err.response.data.message)
                })
                .finally(() => {
                    this.eventListLoading = false
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
