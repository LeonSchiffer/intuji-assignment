<template>
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
        <tbody v-if="!eventListLoading">
            <tr v-for="(item, index) in events" :key="item.title">
                <td>{{ item.title }}</td>
                <td>{{ item.start_time }}</td>
                <td>{{ item.end_time }}</td>
                <td>
                    <v-btn :loading="(deleteLoading && (index == deleteIndexClicked))"
                        @click="deleteEvent(item.id, index)" color="primary" icon="mdi-delete" size="x-small"></v-btn>
                </td>
            </tr>
        </tbody>
        <tbody v-else>
            <tr>
                <td colspan="4" style="text-align: center;"><v-progress-circular color="primary" indeterminate></v-progress-circular></td>
            </tr>
        </tbody>
    </v-table>
</template>
<script>
import { toast } from 'vue3-toastify';

export default {
    props: ['events', 'eventListLoading'],
    data: () => ({
        dialog: false,
        deleteLoading: false,
        deleteIndexClicked: null
    }),
    methods: {
        deleteEvent(event_id, index) {
            this.deleteIndexClicked = index
            this.deleteLoading = true
            axios.delete("/api/events/" + event_id)
                .then(response => {
                    this.$emit("event-deleted", event_id)
                    toast.success("Event has been deleted")
                })
                .catch(err => {
                    toast.error(err.response.data.message)
                })
                .finally(() => {
                    this.deleteIndexClicked = null
                    this.deleteLoading = false
                })
        }
    }
}
</script>
