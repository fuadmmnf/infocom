<template>
  <div>
    <div class="row items-center">
      <v-select class="col-md-3 col-xs-5  q-my-xs q-px-xs" v-model="search" :options="customerCodes" label="code"  :reduce="c => c.id" placeholder="Search by Customer ID"></v-select>
      <!--      <q-input class="col-md-3 col-xs-5  q-my-xs q-px-xs" filled v-model="search" label="Search Customer"></q-input>-->
      <q-btn flat label="search" type="button" @click="() => {fetchComplainsList()}"/>
    </div>
    <q-table
      title=""
      flat
      :data="complains"
      :columns="columns"
      row-key="id"
      :rows-per-page-options="[20]"
      :pagination.sync="pagination"
      @update:pagination="({page}) => {fetchComplainsList(page)}"
      @request="onRequest"

      @row-click="(e, row, idx) => {
        selectedComplain = row
        showComplainDetailModal = true
      }"
    >
      <template v-slot:pagination="scope">
        <q-btn
          v-if="scope.pagesNumber > 2"
          icon="first_page"
          color="grey-8"
          round
          dense
          flat
          :disable="scope.isFirstPage"
          @click="scope.firstPage"
        />

        <q-btn
          icon="chevron_left"
          color="grey-8"
          round
          dense
          flat
          :disable="scope.isFirstPage"
          @click="scope.prevPage"
        />

        <q-btn
          icon="chevron_right"
          color="grey-8"
          round
          dense
          flat
          :disable="scope.isLastPage"
          @click="scope.nextPage"
        />

        <q-btn
          v-if="scope.pagesNumber > 2"
          icon="last_page"
          color="grey-8"
          round
          dense
          flat
          :disable="scope.isLastPage"
          @click="scope.lastPage"
        />
      </template>
    </q-table>

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
          <complain-form :existing-complain="selectedComplain" :supportagents="supportagents"/>
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
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import {date} from 'quasar'
import ComplainForm from "components/Complain/ComplainForm";

export default {
  name: "ComplainsList",
  components: {ComplainForm, vSelect},
  props: {
    status: {
      type: String,
      required: true
    },
  },
  data() {
    return {
      loading: false,
      search: '',
      customerCodes: [],
      filter: '',
      showComplainDetailModal: false,
      selectedComplain: null,
      selectedDepartmentId: this.$store.getters.getUser.support_agent === undefined ? '' : this.$store.getters.getUser.support_agent.department_id,
      supportagents: [],
      pagination: {
        sortBy: 'desc',
        descending: false,
        page: 1,
        rowsPerPage: 3,
        rowsNumber: 10
      },
      complains: [],
      columns: [
        {name: 'id', align: 'center', label: 'TT #', field: row => row.id},
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
    if (this.status === 'assigned') {
      this.fetchSupportAgent()
    }
    this.fetchCustomerCodes()
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
    this.onRequest({
      pagination: this.pagination,
      filter: undefined
    })
  },
  methods: {
    onRequest (props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination
      const filter = props.filter

      this.loading = true
      this.fetchComplainsList(page)
      this.pagination.page = page
      this.pagination.sortBy = sortBy
      this.pagination.descending = descending

      // this.pagination.rowsPerPage = rowsPerPage


      // ...and turn of loading indicator
      this.loading = false
    },

    fetchCustomerCodes() {
      this.$axios.get('customers/code')
        .then((res) => {
          this.customerCodes = res.data
        })
    },
    fetchComplainsList(page = 1) {
      console.log(this.search)
      this.$axios.get(`complains?status=${this.status}${this.selectedDepartmentId === '' ? '' : ('&department_id=' + this.selectedDepartmentId)}${(''+this.search).length > 0? "&customer_id=" + this.search: ""}&page=${page}`)
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
