    <template>
        <div class="pa-4 text-center">
            <v-dialog v-model="dialog" max-width="600">
                <template v-slot:activator="{ props: activatorProps }">
                    <v-btn class="text-none font-weight-regular" prepend-icon="mdi-account" text="Edit Profile"
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
                                        type="datetime-local"></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6">
                                    <v-text-field label="End Date Time" v-model="event.end_time"
                                        type="datetime-local"></v-text-field>
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
        createEvent(e) {
            e.preventDefault()
            console.log(this.event)
            axios
                .post("/api/events", this.event)
                .then(response => {
                    this.dialog = false
                    this.events.push(response.data)
                })
        },
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
        }
    }
}
</script>
