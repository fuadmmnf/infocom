<template>
  <q-page>
    <div class="row justify-start q-my-md">
      <q-card flat bordered class="col-6 q-ma-md">
        <q-card-section>
          <div>
            <q-form @submit="sendCustomNotice">
              <q-select class="q-my-xs" filled v-model="noticeForm.type"
                        :options="types" label="Audience Type"
                        @input="() => {
                          noticeForm.type_id = ''
                        }"
                        :rules="[val => !!val || 'Field is required']"
              />

              <q-select v-if="noticeForm.type !== 'all' || noticeForm.type === ''" class="q-my-xs" filled
                        v-model.number="noticeForm.type_id"
                        :options="getSelectionOptions" option-label="name"
                        option-value="id" emit-value
                        map-options label="Select Audience"
                        :rules="[val => !!val || 'Field is required']"
              />

              <q-input class="q-my-xs" type="textarea" autogrow filled v-model="noticeForm.message" label="Message"
                       :rules="[val => !!val || 'Field is required']"
              />

              <q-btn class="q-my-xs" type="submit" color="primary" label="Confirm"/>
            </q-form>
          </div>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script>
export default {
  name: "Messages",
  data() {
    return {
      types: ['all', 'service', 'popaddress', 'individual'],
      notices: [],
      customerCodes: [],
      noticeForm: {
        type: '',
        type_id: '',
        message: ''
      }
    }
  },
  computed: {
    getSelectionOptions() {
      let options = []
      if (this.noticeForm.type === 'service') {
        options = this.$store.getters.getServices
      } else if (this.noticeForm.type === 'popaddress') {
        options = this.$store.getters.getPopAddresses
      } else if (this.noticeForm.type === 'individual') {
        options = this.customerCodes
      }
      return options
    }
  },
  mounted() {
    this.fetchCustomerCodes()
  },
  methods: {
    fetchCustomerCodes() {
      this.$axios.get('customers/customer_id/all')
        .then((res) => {
          this.customerCodes = res.data.map((c) => {
            return {
              id: c.id,
              name: c.customer_id
            }
          })
        })
    },
    sendCustomNotice() {
      this.noticeForm.type_id = (this.noticeForm.type === 'all'? 0: this.noticeForm.type_id)
      this.$axios.post('customers/notice', this.noticeForm)
        .then((res) => {
          if (res.status === 204) {
            this.noticeForm = {
              type: '',
              type_id: '',
              message: ''
            }
            this.$q.notify({
              type: 'positive',
              message: `Notice sent to customers`,
              position: 'top-right'
            })
          }
        })
    }
  }
}
</script>

<style scoped>

</style>
