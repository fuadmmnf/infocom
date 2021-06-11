<template>
  <q-page>
    <q-table
      title="Customers"
      :data="customers"
      :columns="columns"
      row-key="id"
      :rows-per-page-options="[0]"
      :pagination.sync="pagination"
      @update:pagination="({page}) => {fetchCustomers(page)}"
    >
      <template v-slot:top-right>
        <q-btn label="Create" @click="showCustomerForm = true">
          <q-dialog v-model="showCustomerForm" persistent>
            <q-card style="min-width: 350px">
              <q-form @submit="createCustomer">

                <q-card-section class="q-pa-xs">
                  <q-list bordered padding>
                    <q-item-label header>Create New Customer
                    </q-item-label>

                    <q-item>
                      <q-item-section>
                        <q-input
                          filled
                          v-model="customerForm.name"
                          label="Name"
                          :rules="[val => (!!val ) || 'Enter customer name']"
                        />

                        <q-input
                          filled
                          v-model="customerForm.phone"
                          label="Phone"
                          type="tel"
                          :rules="[val => (!!val ) || 'Enter customer phone number']"
                        />

                        <q-input
                          filled
                          v-model="customerForm.email"
                          label="Email"
                          type="email"
                          :rules="[val => (!!val ) || 'Enter customer email']"
                        />

                        <q-input
                          class="q-mb-md"
                          filled
                          v-model="customerForm.code"
                          label="Code"
                        />

                        <q-input
                          filled
                          v-model="customerForm.password"
                          label="Password"
                          type="password"
                          :rules="[val => (!!val ) || 'Enter customer password']"
                        />

                        <q-input
                          filled
                          v-model="customerForm.password_confirmation"
                          label="Password Confirmation"
                          type="password"
                          :rules="[val => (!!val || val !== customerForm.password ) || 'Retype customer password']"
                        />
                      </q-item-section>
                    </q-item>
                  </q-list>

                </q-card-section>

                <q-card-actions align="right" class="text-primary">
                  <q-btn flat label="Close" v-close-popup/>
                  <q-btn flat
                         :disable="customerForm.password !== customerForm.password_confirmation || customerForm.name ==='' || customerForm.phone === '' || customerForm.email === ''"
                         label="Confirm"
                         type="submit"/>
                </q-card-actions>
              </q-form>
            </q-card>
          </q-dialog>
        </q-btn>
      </template>
    </q-table>
  </q-page>
</template>

<script>
export default {
  name: 'DashboardCustomers',
  data() {
    return {
      showCustomerForm: false,
      pagination: {
        page: 1,
        rowsPerPage: 20
      },
      departments: [],
      customers: [],
      customerForm: {
        name: '',
        email: '',
        phone: '',
        code: '',
        department_id: '',
        password: '',
        password_confirmation: '',
      },
      columns: [
        {name: 'name', align: 'center', label: 'Name', field: row => row.user.name, sortable: true},
        {name: 'phone', align: 'center', label: 'Phone', field: row => row.user.phone},
        {name: 'email', align: 'center', label: 'Email', field: row => row.user.email},
        {name: 'code', align: 'center', label: 'Code', field: row => row.code},
      ]

    }
  },
  mounted() {
    this.fetchCustomers()
  },
  methods: {
    fetchCustomers(page = 1) {
      this.$axios.get(`customers?page=${page}`)
        .then((res) => {
          this.customers = res.data.data

        })
    },
    createCustomer() {
      this.$axios.post('customers', this.customerForm)
        .then((res) => {
          if (res.status === 201) {
            this.showCustomerForm = false
            this.fetchCustomers()
            this.customerForm = {}
            this.$q.notify({
              type: 'positive',
              message: `Customer Created Successfully`,
              position: 'top-right'
            })
          }
        })
    }
  }
}
</script>
