<script setup>
    import EventCreate from "@/Components/Events/EventCreate.vue"
</script>
<template>
        <event-create @event-created="eventCreated"/>
        <v-table>
            <thead>
                <tr>
                    <th class="text-left">
                        Title
                    </th>
                    <th class="text-left">
                        Start
                    </th>
                    <th class="text-left">
                        End
                    </th>
                    <th class="text-left">

                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in events" :key="item.title">
                    <td>{{ item.title }}</td>
                    <td>{{ item.start_time }}</td>
                    <td>{{ item.end_time }}</td>
                    <td>
                        <v-btn @click="deleteEvent(item.id)" icon="mdi-delete" size="x-small"></v-btn>
                    </td>
                </tr>
            </tbody>
        </v-table>
    </template>
<script>
import axios from 'axios';

export default {
    data: () => ({
        dialog: false,
        event: {
            title: "",
            start_time: "",
            end_time: ""
        },
        events: [],
    }),
    mounted() {
        this.getEvents()
    },
    methods: {
        getEvents() {
            axios
                .get("/api/events")
                .then(response => {
                    // console.log(response.data);
                    this.events = response.data.data
                    console.log(this.events)
                })
        },
        deleteEvent(event_id) {
            axios.delete("/api/events/" + event_id)
                .then(response => {
                    this.getEvents()
                })
        },
        eventCreated(event) {
            this.events.push(event)
        }
    }
}
</script>
