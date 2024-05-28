<template>
<div class="pa-4 text-center">
    <v-dialog v-model="dialog" max-width="600">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn class="text-none font-weight-regular" prepend-icon="mdi-calendar-plus" text="Create Event"
                variant="tonal" v-bind="activatorProps"></v-btn>
        </template>
        <form @submit="createEvent">
            <v-card prepend-icon="mdi-account" title="User Profile">
                <v-card-text>
                    <v-row dense>
                        <v-col cols="12" sm="12">
                            <v-text-field label="Title*" v-model="event.title" required></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field label="Start Date Time" v-model="event.start_time"
                                type="datetime-local" required></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field label="End Date Time" v-model="event.end_time"
                                type="datetime-local" required></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn text="Close" variant="plain" @click="dialog = false"></v-btn>

                    <v-btn color="primary" text="Save" type="submit" variant="tonal"></v-btn>
                </v-card-actions>
            </v-card>
        </form>
    </v-dialog>
</div>
</template>
<script>
import axios from 'axios';
import { toast } from 'vue3-toastify';

export default {
    data: () => ({
        dialog: false,
        event: {
            title: "",
            start_time: "",
            end_time: ""
        }
    }),
    methods: {
        createEvent(e) {
            e.preventDefault()
            console.log(this.event)
            axios
                .post("/api/events", this.event)
                .then(response => {
                    this.dialog = false
                    this.$emit('event-created', response.data)
                    toast.success("Event successfully created")
                })
                .catch(err => {
                    if (err.request.status == 422) {
                        console.log(err.response.data.errors)
                        for (let [index, error] of Object.entries(err.response.data.errors)) {
                            toast.error(error[0])
                        }
                        return
                    }
                    toast.error(err.response.data.message)
                })
        }
    }
}

</script>
