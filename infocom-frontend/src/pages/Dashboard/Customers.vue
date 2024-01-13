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
      @request="onRequest"
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

      <template v-slot:top-right>
        <div class="flex flex-center items-center">
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
                    <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled v-model="customerForm.phone"
                             label="Phone"
                             :rules="[ val => !!val && val.length === 11 || 'Please enter 11 digit phone number']"
                    />
                  </div>

                  <div class="row q-my-none">
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.email" type="email"
                             label="Email"/>
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.code"
                             label="Code"/>


                  </div>

                  <div class="row q-my-none">
                    <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                              v-model="customerForm.services" multiple
                              :options="$store.getters.getServices" option-label="name"
                              option-value="id" emit-value
                              map-options label="Services"

                    />

                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.selling_price_bdt_excluding_vat"
                             label="Selling price bdt excluding vat"/>
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
                             :rules="[ val => (val===undefined || val === '' || val.replace(/\s/g,'').split(',').filter((v) => v.length !== 11).length === 0) || 'Please enter comma separated 11 digit phone numbers']"
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
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.billing_information"
                             label="Billing Information"/>
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.kam_name"
                             label="KAM Name"/>
                  </div>

                  <div class="row q-my-none">
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.client_type"
                             label="Client type"/>
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.connection_type"
                             label="Connection Type"/>
                  </div>

                  <div class="row q-my-none">
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.bandwidth_distribution_point"
                             label="Bandwidth distribution point"/>
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.connectivity_type"
                             label="Connectivity type"/>
                  </div>


                  <div class="row q-my-none">
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.bandwidth_allocation"
                             label="Bandwidth allocation"/>
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.allocated_ip"
                             label="Allocated IP"/>

                  </div>

                  <div class="flex flex-center items-center q-mt-none">
                    <span class="text-subtitle1">Customer Address</span>
                  </div>
                  <div class="row q-my-none">
                    <q-input class="col-md-4 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.division"
                             label="Division"/>
                    <q-input class="col-md-4 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.district"
                             label="District"/>
                    <q-input class="col-md-4 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.thana"
                             label="Thana"/>

                  </div>
                  <div class="row q-my-none">

                    <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.address"
                             label="Address"/>

                  </div>

                  <div class="q-pa-sm">
                    <!--      <q-btn class="bg-info text-white q-mr-sm" label="Resubmit" type="button"-->
                    <!--             @click="updateComplain(false)"-->
                    <!--             :disable="this.$store.getters.getActionRunningState"/>-->

                    <!--                  <q-btn class=" q-mr-sm" label="Reset" type="reset"/>-->

                    <q-btn class="bg-purple text-white" label="Submit" type="submit"
                           :disable="$store.getters.getActionRunningState"/>
                  </div>

                </q-form>

              </q-card>
            </q-dialog>
          </q-btn>

          <download-excel class="q-ml-md" type="csv"
                          :name="`Customers_${(new Date()).toLocaleDateString('en-US').replaceAll('/', '_')}.csv`"
                          :header="`Customers_${(new Date()).toLocaleDateString('en-US').replaceAll('/', '_')}.csv`"
                          :fetch="fetchCustomerData"
          >
            <q-btn
              color="green-10"
              icon="text_snippet"
            >Export
            </q-btn>
          </download-excel>
        </div>

      </template>
    </q-table>
  </q-page>
</template>

<script>
import {date} from "quasar";
import JsonExcel from "vue-json-excel";

const customerFormTemplate = () => {
  return {
    name: '',
    email: '',
    phone: '',
    code: '',
    division: '',
    district: '',
    thana: '',
    address: '',
    services: [],
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
    client_type: '',
    connection_type: '',
    bandwidth_distribution_point: '',
    connectivity_type: '',
    bandwidth_allocation: '',
    allocated_ip: '',
    selling_price_bdt_excluding_vat: ''
  }
}
export default {
  name: 'DashboardCustomers',
  components: {'downloadExcel': JsonExcel},
  computed: {
    date() {
      return date
    }
  },
  data() {
    return {
      loading: false,
      showCustomerForm: false,
      pagination: {
        page: 1,
        rowsPerPage: 3,
        rowsNumber: 10
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
      this.fetchCustomers(page)
      this.pagination.page = page
      this.pagination.sortBy = sortBy
      this.pagination.descending = descending

      // this.pagination.rowsPerPage = rowsPerPage


      // ...and turn of loading indicator
      this.loading = false
    },
    openCustomerModal(customer) {
      if (customer === null) {
        this.customerForm = customerFormTemplate()
      } else {
        const serviceIds = this.$store.getters.getServices.map((s) => s.id)
        this.customerForm = {
          ...customerFormTemplate(),
          ...customer,
          name: customer.user.name,
          phone: customer.user.phone,
          email: customer.user.email,
          popaddress_id: customer.popaddress === null ? '' : customer.popaddress_id,
          services: customer.services.filter((s) => {
            return serviceIds.includes(s)
          })
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
    async fetchCustomerData() {
      const response = await this.$axios.get(`reports/customers`)
      return response.data.rows
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
