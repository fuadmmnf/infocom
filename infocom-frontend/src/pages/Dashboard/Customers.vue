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
      @row-click="(e, row, idx) => {$router.push({name: 'dashboard-customer-detail', params: {customer_id: row.id}})}"
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

                <q-form @submit="createCustomer()"
                        @reset="customerForm = {}"
                        class="q-gutter-md">
                  <div class="row q-mb-none">
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
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.customer_id"
                             label="Customer ID"/>


                  </div>

                  <div class="row q-my-none">
                    <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                              v-model="customerForm.services" multiple
                              :options="$store.getters.getServices" option-label="name"
                              option-value="id" emit-value
                              map-options label="Services"

                    />

                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.client_type"
                             label="Client Type"/>
                  </div>

                  <div class="row q-my-none">
                    <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                              v-model.number="customerForm.popaddress_id"
                              :options="$store.getters.getPopAddresses" option-label="name"
                              option-value="id" emit-value
                              map-options label="Pop Address"

                    />
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.kam_name"
                             label="Kam Name"/>
                  </div>

                  <div class="row q-my-none">
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.connectivity_type"
                             label="Connectivity Type"/>
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.allocated_ip"
                             label="Allocated IP"/>
                  </div>

                  <div class="row q-my-none">

                    <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.technical_contact"
                             label="Technical Contact"/>

                  </div>

                  <div class="row q-my-none">

                    <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.management_contact"
                             label="Management Contact"/>

                  </div>


                  <div class="row q-my-none">
                    <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.billing_contact_person"
                             label="Billing Contact Person"/>
                  </div>

                  <div class="row q-my-none">
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.selling_price"
                             label="Selling Price"/>
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.price_details"
                             label="Price Details"/>
                  </div>

                  <div class="row q-my-none">
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                             v-model="customerForm.nttn"
                             label="NTTN"/>
                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.bw_allocation"
                             label="BW Allocation"/>
                  </div>
                  <div class="row q-my-none">
                    <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.mrtg_details"
                             label="MRTG Details"/>
                  </div>

                  <div class="row q-my-none">
                    <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.router_details"
                             label="Router Details"/>
                  </div>


                  <div class="row q-my-none">
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

                    <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.first_billing_date"
                             mask="date"
                             label="First Billing Date">
                      <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                          <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                            <q-date v-model="customerForm.first_billing_date">
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

                    <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.address"
                             label="Customer Address"/>

                  </div>
                  <div class="row q-my-none">
                    <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.comment_box"
                             label="Comment Box"/>
                  </div>
                  <div class="row q-my-none">
                    <q-file class="col-md-5 col-xs-12 q-my-xs q-px-xs" filled bottom-slots v-model="additional_file"
                            label="Additional Customer File" counter max-files="1">
                      <template v-slot:before>
                        <q-icon name="attachment"/>
                      </template>
                      <template v-if="additional_file !== null" v-slot:append>
                        <q-icon name="close" @click.stop="additional_file = null" class="cursor-pointer"/>
                      </template>

                      <template v-slot:hint>
                        Additional File
                      </template>
                    </q-file>

                    <!--            <q-btn class="col-md-3 col-xs-12 q-my-xs q-px-xs" filled-->
                    <!--                   label="Download"  @click="downloadItem(customerForm.additional_file, 'Additional File')"/>-->
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
    popaddress_id: '',
    name: '',
    phone: '',
    email: '',
    services: [],
    client_type: '',
    connectivity_type: '',
    customer_id: '',
    address: '',
    technical_contact: '',
    management_contact: '',
    billing_contact_person: '',
    kam_name: '',
    selling_price: '',
    price_details: '',
//           'assword'  'required|confirmed',
    nttn: '',
    bw_allocation: '',
    mrtg_details: '',
    allocated_ip: '',
    comment_box: '',
    router_details: '',
    installation_date: '',
    first_billing_date: '',
    additional_file: '',
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
      additional_file: null,
      customerForm: customerFormTemplate(),
      columns: [
        {name: 'name', align: 'center', label: 'Name', field: row => row.user.name},
        {name: 'phone', align: 'center', label: 'Phone', field: row => row.user.phone},
        {name: 'email', align: 'center', label: 'Email', field: row => row.user.email},
        {name: 'customer_id', align: 'center', label: 'Customer ID', field: row => row.customer_id},
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
    onRequest(props) {
      const {page, rowsPerPage, sortBy, descending} = props.pagination
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
        .then(async (res) => {
          if (res.status === 201) {
            const c = res.data
            if (this.additional_file !== null) {
              let formData = new FormData();
              formData.append('additional_file', this.additional_file);
              await this.$axios.post(`customers/${c.id}/file`, formData);
            }
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
