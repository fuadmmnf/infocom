<template>
  <q-page>
    <h5 class="q-ma-md">Customer Details</h5>
    <q-card flat bordered class=" q-pa-md q-ma-md">
      <q-card-section>
        <q-form @submit="updateCustomer()"
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
            <div  v-if="customerForm.additional_file !== null">
              <q-btn @click.stop="() => {downloadItem(customerForm.additional_file, 'Customer Additional File')}">Download Additional File</q-btn>
              <q-btn @click="()=>{customerForm.file = null; customerForm.additional_file = null}">Remove</q-btn>
            </div>
            <q-file v-else class="col-md-5 col-xs-12 q-my-xs q-px-xs" filled bottom-slots v-model="file"
                    label="Additional Customer File" counter max-files="1">
              <template v-slot:before>
                <q-icon name="attachment"/>
              </template>
              <template v-if="customerForm.file !== null" v-slot:append>
                <q-icon name="close" @click.stop=" () => {customerForm.file = null}" class="cursor-pointer"/>
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

      </q-card-section>
    </q-card>

  </q-page>
</template>

<script>

export default {
  name: "CustomerDetail",
  data() {
    return {
      customerForm: {
        file: null,
      },
      file: null,
    }

  },
  mounted() {
    this.fetchCustomer()
  },
  methods: {
    downloadItem(url, label) {
      const downloadRoot = process.env.PROD ? 'http://221.120.96.121/uploads/customers' : 'http://127.0.0.1:8000/uploads/customers'
      // const blob = new Blob([response.data])
      const link = document.createElement('a')
      link.href = downloadRoot + url
      link.download = label
      link.target = '_blank'
      link.click()
      // this.$axios.get(downloadRoot + url, { responseType: 'blob' })
      //   .then(response => {
      //
      //     URL.revokeObjectURL(link.href)
      //   }).catch(console.error)
    },
    fetchCustomer() {
      this.$axios.get(`customers/${this.$route.params.customer_id}`)
        .then((res) => {
          const serviceIds = this.$store.getters.getServices.map((s) => s.id)
          this.customerForm = {
            ...res.data,
            name: res.data.user.name,
            phone: res.data.user.phone,
            email: res.data.user.email,
            popaddress_id: res.data.popaddress === null ? '' : res.data.popaddress_id,
            services: res.data.services.filter((s) => {
              return serviceIds.includes(s)
            })
          }
        })
    },
    updateCustomer() {
      const prev = {...this.customerForm}
      if (this.customerForm.file === null) {
        this.customerForm = {...this.customerForm, additional_file: null}
      } else {
        delete this.customerForm['additional_file'];
      }
      this.$axios.put(`customers/${this.$route.params.customer_id}`, this.customerForm)
        .then(async (res) => {
          if (res.status === 204) {
            if (this.additional_file !== null) {
              let formData = new FormData();
              formData.append('additional_file', this.file);
              await this.$axios.post(`customers/${this.$route.params.customer_id}/file`, formData);
              this.fetchCustomer();
            }
            this.$q.notify({
              type: 'positive',
              message: `Customer Updated Successfully`,
              position: 'top-right'
            })
          }

        }).catch((e) => {
        this.customerForm = prev
        this.$q.notify({
          type: 'negative',
          message: `Customer Updated Failed`,
          position: 'top-right'
        })
      })
    }
  }
}
</script>

<style scoped>

</style>
