<template>
  <div>
    <q-table
      title=""
      :data="complains"
      :columns="columns"
      row-key="id"
      :rows-per-page-options="[0]"
      :pagination.sync="pagination"
      @update:pagination="({page}) => {fetchComplainsList(page)}"
    />

  </div>
</template>

<script>
import {date} from 'quasar'

export default {
  name: "ComplainsList",
  props: {
    status: {
      type: String,
      required: true
    },
  },
  data() {
    return {
      pagination: {
        page: 1,
        rowsPerPage: 20
      },
      complains: [],
      columns: [
        {name: 'name', align: 'center', label: 'Name', field: row => row.customer.name},
        {name: 'phone', align: 'center', label: 'Phone', field: row => row.customer.phone},
        {name: 'email', align: 'center', label: 'Email', field: row => row.customer.email},
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
    this.fetchComplainsList()
  },
  methods: {
    fetchComplainsList(page = 1) {
      const dept_id = this.$store.getters.getUser.department_id === undefined ? '' : this.$store.getters.getUser.department_id
      this.$axios.get(`complains?status=${status}${dept_id === '' ? '' : ('&department=' + dept_id)}&page=${page}`)
        .then((res) => {
          this.complains = res.data.data
        })
    }
  }
}
</script>

<style scoped>

</style>
