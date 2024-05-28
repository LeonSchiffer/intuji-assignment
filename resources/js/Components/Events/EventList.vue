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
export default {
    props: ['events'],
    data: () => ({
        dialog: false,
    }),
    methods: {
        deleteEvent(event_id) {
            axios.delete("/api/events/" + event_id)
                .then(response => {
                    this.$emit("event-deleted", event_id)
                })
        }
    }
}
</script>
