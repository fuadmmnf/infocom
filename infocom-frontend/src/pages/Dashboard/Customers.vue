<template>
  <q-page>
    <q-table
      v-if="$store.getters.hasCallcenterAccess"
      title="Customers"
      :data="customers"
      :columns="columns"
      row-key="id"
      :rows-per-page-options="[20]"
      :pagination.sync="pagination"
      @row-click="(e, row, idx) => {openCustomerModal(row)}"
      @update:pagination="({page}) => {fetchCustomers(page)}"
    >
      <template v-slot:top-right>
        <q-btn label="Create" @click="() => {
          openCustomerModal(null)
        }">


          <q-dialog v-model="showCustomerForm" persistent>
            <q-card style="min-width: 70%">
              <q-bar>
                <div>Create Customer</div>

                <q-space/>

                <q-btn dense flat icon="close" v-close-popup>
                  <q-tooltip>Close</q-tooltip>
                </q-btn>
              </q-bar>

              <q-form @submit="customerForm.id === undefined? createCustomer(): updateCustomer()"
                      @reset="customerForm = {}"
                      class="q-gutter-md">
                <div class="row">
                  <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled v-model="customerForm.name"
                           label="Full Name"/>
                  <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled v-model="customerForm.phone" label="Phone"
                  />
                </div>

                <div class="row q-my-none">
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.email" type="email"
                           label="Email"/>
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.code" label="Code"/>


                </div>

                <div class="row q-my-none">
                  <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                            v-model.number="customerForm.popaddress_id"
                            :options="$store.getters.getPopAddresses" option-label="name"
                            option-value="id" emit-value
                            map-options label="Pop Address"

                  />
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.installation_date"
                           mask="date"
                           label="Installation Date">
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                          <q-date v-model="customerForm.installation_date">
                            <div class="row items-center justify-end">
                              <q-btn v-close-popup label="Close" color="primary" flat/>
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="row q-my-none">
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.technical_contact"
                           label="Technical Contact"/>
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.management_contact"
                           label="Management Contact"/>
                </div>

                <div class="row q-my-none">
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.connection_package"
                           label="Connection Package"/>
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.connection_details"
                           label="Connection Details"/>
                </div>

                <div class="row q-my-none">
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.other_services"
                           label="Other Services"/>
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                           v-model="customerForm.additional_technical_box"
                           label="Additional Technical Box"/>
                </div>

                <div class="row q-my-none">
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.billing_information"
                           label="Billing Information"/>
                  <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.kam_name"
                           label="KAM Name"/>
                </div>


                <div class="q-pa-sm">
                  <!--      <q-btn class="bg-info text-white q-mr-sm" label="Resubmit" type="button"-->
                  <!--             @click="updateComplain(false)"-->
                  <!--             :disable="this.$store.getters.getActionRunningState"/>-->

                  <q-btn class=" q-mr-sm" label="Reset" type="reset"/>

                  <q-btn class="bg-purple text-white" label="Submit" type="submit"
                         :disable="$store.getters.getActionRunningState"/>
                </div>

              </q-form>

            </q-card>
          </q-dialog>
        </q-btn>
      </template>
    </q-table>
  </q-page>
</template>

<script>
const customerFormTemplate = () => {
  return {
    name: '',
    email: '',
    phone: '',
    code: '',
    popaddress_id: '',
    technical_contact: '',
    management_contact: '',
    connection_package: '',
    other_services: '',
    connection_details: '',
    additional_technical_box: '',
    billing_information: '',
    kam_name: '',
    installation_date: '',
    password: '',
    password_confirmation: '',
  }
}
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
      customerForm: customerFormTemplate(),
      columns: [
        {name: 'name', align: 'center', label: 'Name', field: row => row.user.name, sortable: true},
        {name: 'phone', align: 'center', label: 'Phone', field: row => row.user.phone},
        {name: 'email', align: 'center', label: 'Email', field: row => row.user.email},
        {name: 'code', align: 'center', label: 'Code', field: row => row.code},
        {
          name: 'popaddress',
          align: 'center',
          label: 'POP',
          field: row => row.popaddress === null ? '' : row.popaddress.name
        },
      ]

    }
  },
  mounted() {
  },
  methods: {
    openCustomerModal(customer) {
      if (customer === null) {
        this.customerForm = customerFormTemplate()
      } else {
        this.customerForm = {
          ...customerFormTemplate(),
          ...customer,
          name: customer.user.name,
          phone: customer.user.phone,
          email: customer.user.email
        }
      }

      this.showCustomerForm = true
    },
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
            this.customerForm = customerFormTemplate()
            this.$q.notify({
              type: 'positive',
              message: `Customer Created Successfully`,
              position: 'top-right'
            })
          }
        })
    },
    updateCustomer() {
      this.$axios.put(`customers/${this.customerForm.id}`, this.customerForm)
        .then((res) => {
          if (res.status === 204) {
            this.showCustomerForm = false
            this.fetchCustomers()
            this.customerForm = customerFormTemplate()
            this.$q.notify({
              type: 'positive',
              message: `Customer Updated Successfully`,
              position: 'top-right'
            })

          }

        })
    }
  }
}
</script>
