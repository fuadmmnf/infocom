<template>
  <q-page>
    <h5 class="q-ma-md">Customer Details</h5>
    <q-card flat bordered class=" q-pa-md q-ma-md">
      <q-card-section>
        <q-form @submit="updateCustomer()"
                @reset="customerForm = {}"
                class="q-gutter-md">
          <div class="row">
            <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled v-model="customerForm.name"
                     label="Full Name" />
            <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled v-model="customerForm.phone"
                     label="Phone"
                     :rules="[ val => !!val && val.length === 11 || 'Please enter 11 digit phone number']"
            />
          </div>

          <div class="row q-my-none">
            <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.email" type="email"
                     label="Email" />
            <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.code"
                     label="Code" />


          </div>

          <div class="row q-my-none">
            <q-select class="col-md-4 col-xs-12 q-my-xs q-px-xs" filled
                      v-model="customerForm.type"
                      :options="['VIP', 'corporate', 'home']"
                      label="type"

            />
            <q-select class="col-md-8 col-xs-12 q-my-xs q-px-xs" filled
                      v-model="customerForm.services" multiple
                      :options="$store.getters.getServices" option-label="name"
                      option-value="id" emit-value
                      map-options label="Services"

            />
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
                        <q-btn v-close-popup label="Close" color="primary" flat />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
          </div>

          <div class="row q-my-none">
            <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.address"
                     label="Address" />
          </div>
          <div class="row q-my-none">
            <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.technical_contact"
                     label="Technical Contact" />
          </div>

          <div class="row q-my-none">
            <q-input class="col-md-12 col-xs-12 q-my-xs q-px-xs" filled
                     v-model="customerForm.management_contact"
                     label="Management Contact" />
          </div>

          <div class="row q-my-none">
            <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.connection_package"
                     label="Connection Package" />
            <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.connection_details"
                     label="Connection Details" />
          </div>

          <div class="row q-my-none">
            <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.other_services"
                     label="Other Services" />
            <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                     v-model="customerForm.additional_technical_box"
                     label="Additional Technical Box" />
          </div>

          <div class="row q-my-none">
            <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled
                     v-model="customerForm.billing_information"
                     label="Billing Information" />
            <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="customerForm.kam_name"
                     label="KAM Name" />
          </div>


          <div class="q-pa-sm">
            <!--      <q-btn class="bg-info text-white q-mr-sm" label="Resubmit" type="button"-->
            <!--             @click="updateComplain(false)"-->
            <!--             :disable="this.$store.getters.getActionRunningState"/>-->

            <!--                  <q-btn class=" q-mr-sm" label="Reset" type="reset"/>-->

            <q-btn class="bg-purple text-white" label="Update" type="submit"
                   :disable="$store.getters.getActionRunningState" />
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
      customerForm: {},
    }

  },
  mounted() {
    this.fetchCustomer()
  },
  methods: {
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
            services: res.data.services.filter((s) => { return serviceIds.includes(s)})
          }
        })
    },
    updateCustomer() {
      const prev = { ...this.customerForm }
      this.$axios.put(`customers/${this.$route.params.customer_id}`, this.customerForm)
        .then((res) => {
          if (res.status === 204) {
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
