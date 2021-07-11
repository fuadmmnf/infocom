<template>
  <div>
    <q-table
      title=""
      flat
      :data="complains"
      :columns="columns"
      row-key="id"
      :rows-per-page-options="[20]"
      :pagination.sync="pagination"
      @update:pagination="({page}) => {fetchComplainsList(page)}"
      @row-click="(e, row, idx) => {
        selectedComplain = row
        showComplainDetailModal = true
      }"
    >
      <q-dialog v-model="showComplainDetailModal" persistent @hide="(e) => {selectedComplain = null}">
        <q-card style="min-width: 70%">
          <q-bar>
            <div>Complain Details</div>

            <q-space />

            <q-btn dense flat icon="close" v-close-popup>
              <q-tooltip>Close</q-tooltip>
            </q-btn>
          </q-bar>
          <q-card-section class="q-pa-xs">
            <complain-form :existing-complain="selectedComplain" :supportagents="supportagents" />
          </q-card-section>

          <q-card-actions align="right" class="text-primary">
            <!--          <q-btn flat label="Close" v-close-popup/>-->
            <!--          <q-btn flat/>-->
          </q-card-actions>
        </q-card>
      </q-dialog>

      <template v-slot:top-right>
        <q-input borderless dense v-model="query" placeholder="Search">
          <template v-slot:append>
            <q-icon name="search" @click="fetchComplainsList" />
          </template>
        </q-input>
      </template>
    </q-table>

  </div>
</template>

<script>
import { date } from 'quasar'
import ComplainForm from "components/Complain/ComplainForm";

export default {
  name: "ComplainsList",
  components: { ComplainForm },
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
      selectedDepartmentId: this.$store.getters.getUser.support_agent ===
                            undefined ? '' : this.$store.getters.getUser.support_agent.department_id,
      supportagents: [],
      pagination: {
        page: 1,
        rowsPerPage: 20
      },
      query: '',
      dateRangeQuery: {
        to: null,
        from: null
      },
      complains: [],
      columns: [
        { name: 'id', align: 'center', label: 'TT #', field: row => row.id },
        { name: 'name', align: 'center', label: 'Name', field: row => row.customer.user.name },
        { name: 'phone', align: 'center', label: 'Phone', field: row => row.customer.user.phone },
        { name: 'email', align: 'center', label: 'Email', field: row => row.customer.user.email },
        { name: 'priority', align: 'center', label: 'Priority', field: row => row.priority },
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
    if (this.status === 'assigned') {
      this.fetchSupportAgent()
    }

    if (this.status === 'approved') {
      this.columns.push({
        name: 'rating',
        align: 'center',
        label: 'Rating',
        field: row => (row === null ? '' : row.customer_rating)
      })
    }

    this.fetchSupportAgent()
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
      const deptQuery = this.selectedDepartmentId === '' ? '' : ('&department_id=' + this.selectedDepartmentId)
      const customerQuery = this.query === '' ? '' : ('&customer_code=' + this.query)
      const daterangeQuery = (this.dateRangeQuery.from !== null && this.dateRangeQuery.to !== null) ? '' :
        ('&start_date=' + this.dateRangeQuery.from.replaceAll('/', '-') + '&end_date=' +
         ths.dateRangeQuery.to.replaceAll('/', '-'))

      this.$axios.get(`complains?status=${this.status}${deptQuery}${customerQuery}${daterangeQuery}&page=${page}`)
        .then((res) => {
          this.complains = res.data.data
        })
    },

    fetchSupportAgent() {
      this.$axios.get('supportagents')
        .then((res) => {
          this.supportagents = res.data
        })
    }
  }
}
</script>

<style scoped>

</style>
