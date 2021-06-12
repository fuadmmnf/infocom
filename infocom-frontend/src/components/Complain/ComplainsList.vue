<template>
  <div>
    <q-table
      title=""
      flat
      :data="complains"
      :columns="columns"
      row-key="id"
      :rows-per-page-options="[0]"
      :pagination.sync="pagination"
      @update:pagination="({page}) => {fetchComplainsList(page)}"
      @row-click="(e, row, idx) => {
        selectedComplain = row
        showComplainDetailModal = true
      }"
    />

    <q-dialog v-model="showComplainDetailModal" persistent @hide="(e) => {selectedComplain = null}">
      <q-card style="min-width: 70%">
        <q-bar>
          <div>Complain Details</div>

          <q-space/>

          <q-btn dense flat icon="close" v-close-popup>
            <q-tooltip>Close</q-tooltip>
          </q-btn>
        </q-bar>
        <q-card-section class="q-pa-xs">
          <complain-form :existing-complain="selectedComplain"/>
        </q-card-section>

        <q-card-actions align="right" class="text-primary">
          <!--          <q-btn flat label="Close" v-close-popup/>-->
          <!--          <q-btn flat/>-->
        </q-card-actions>
      </q-card>
    </q-dialog>

  </div>
</template>

<script>
import {date} from 'quasar'
import ComplainForm from "components/Complain/ComplainForm";

export default {
  name: "ComplainsList",
  components: {ComplainForm},
  props: {
    status: {
      type: String,
      required: true
    },
  },
  data() {
    return {
      showComplainDetailModal: false,
      selectedComplain: null,
      pagination: {
        page: 1,
        rowsPerPage: 20
      },
      complains: [],
      columns: [
        {name: 'name', align: 'center', label: 'Name', field: row => row.customer.user.name},
        {name: 'phone', align: 'center', label: 'Phone', field: row => row.customer.user.phone},
        {name: 'email', align: 'center', label: 'Email', field: row => row.customer.user.email},
        {name: 'priority', align: 'center', label: 'Priority', field: row => row.priority},
        {
          name: 'complain_time',
          align: 'center',
          label: 'Time',
          field: row => date.formatDate(row.complain_time, 'D MMM, YYYY hh:mm A')
        },
      ],
    }
  },
  mounted() {
    // this.fetchComplainsList()
    this.$root.$on('complain-updated', (data) => {
      this.showComplainDetailModal = false
      this.selectedComplain = null;
      this.fetchComplainsList(this.pagination.page)
      this.$q.notify({
        type: 'positive',
        message: `Complain Submitted Successfully`,
        position: 'top-right'
      })
    })
  },
  methods: {
    fetchComplainsList(page = 1) {
      const dept_id = this.$store.getters.getUser.department_id === undefined ? '' : this.$store.getters.getUser.department_id
      this.$axios.get(`complains?status=${this.status}${dept_id === '' ? '' : ('&department=' + dept_id)}&page=${page}`)
        .then((res) => {
          this.complains = res.data.data
        })
    }
  }
}
</script>

<style scoped>

</style>
