<template>
  <q-form @submit="complain.status === undefined? createComplain(): updateComplain(true)" @reset="complain = {}"
          class="q-gutter-md">
    <div class="row items-center" v-if="isAuthenticated && complain.status === undefined">
      <v-select class="col-md-3 col-xs-5  q-my-xs q-px-xs" v-model="search" :options="customerIds"></v-select>
      <!--      <q-input class="col-md-3 col-xs-5  q-my-xs q-px-xs" filled v-model="search" label="Search Customer"></q-input>-->
      <q-btn flat label="search" type="button" @click="searchCustomer"/>
    </div>

    <div class="row">
      <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled v-model="complain.name" label="Full Name"
               :disable="statusIndex > 0" readonly/>
      <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled v-model="complain.phone" label="Phone"
               :disable="statusIndex > 0" readonly/>
    </div>

    <div class="row q-my-none">
      <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="complain.email" type="email"
               label="Email" :disable="statusIndex > 0" readonly/>

      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model.number="complain.helptopic_id"
                :options="$store.getters.getHelpTopics" option-label="name"
                option-value="id" emit-value
                map-options label="Help Topic"
                :disable="statusIndex > 0"
                @input="() => {setSlaTopic()}"
      />

    </div>

    <div v-if="$store.getters.hasCallcenterAccess" class="row q-my-none">

      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="complain.ticket_source"
                :options="[
                  {label: 'Agent Create', value: 'agent'},
                  {label: 'Customer Email', value: 'email'},
                ]"
                emit-value
                label="Ticket Source"
                :disable="statusIndex > 0"
      />

      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model.number="complain.department_id"
                :options="$store.getters.getDepartments" option-label="name"
                option-value="id" emit-value
                map-options label="Department"
                :disable="statusIndex > 0"
      />

    </div>


    <div v-if="$store.getters.hasCallcenterAccess" class="row q-my-none">

      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled :value="selectSlaPlan"
                label="SLA Plan"
                readonly
      />

      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled v-model="complain.priority"
                :options="[
                  {label: 'Low', value: 'low'},
                  {label: 'Medium', value: 'medium'},
                  {label: 'High', value: 'high'},
                  {label: 'Urgent', value: 'urgent'},
                ]"
                emit-value
                label="Priority"
                :disable="statusIndex > 0"
      />


    </div>


    <q-input v-if="$store.getters.hasCallcenterAccess" class=" q-my-xs q-px-xs" filled
             v-model="complain.complain_summary"
             type="textarea" autogrow
             label="Complain Summary"
             :disable="statusIndex > 0"
    />

    <q-input class=" q-my-xs q-px-xs" filled v-model="complain.complain_text" type="textarea" autogrow
             label="Complain details"
             :disable="statusIndex > 0"
    />


    <q-input v-if="$store.getters.hasAuthorityAccess && statusIndex > 1" class=" q-my-xs q-px-xs" filled
             v-model="complain.complain_feedback"
             type="textarea" autogrow
             label="Complain Feedback"
             :disable="statusIndex<2 || !(isComplainEditor || $store.getters.hasCallcenterAccess)"
    />


    <div v-if="statusIndex === 4" class="row q-my-none">
      <q-input class="col-2 q-my-xs q-px-xs" filled
               :value="complain.customer_rating"
               label="Customer Rating"
               readonly
      />

      <q-input class="col-8 q-my-xs q-px-xs" filled
               :value="complain.customer_review"
               type="textarea" autogrow
               label="Customer Feedback"
               readonly
      />
    </div>


    <!--    Action bar for complain according to status-->
    <div v-if="complain.status === undefined">
      <q-btn class=" q-mr-sm" label="Reset" type="reset"/>
      <q-btn class="bg-purple text-white" label="Submit" type="submit"
             :disable="$store.getters.getActionRunningState"/>
    </div>

    <div v-else-if="statusIndex === 0">
      <q-btn class="bg-info text-white q-mr-sm" label="Save" type="button"
             @click="updateComplain(false)"
             :disable="$store.getters.getActionRunningState"/>

      <q-btn class="bg-purple text-white" label="Submit" type="submit"
             :disable="$store.getters.getActionRunningState"/>
    </div>


    <div v-else-if="statusIndex === 1 && $store.getters.getUser.support_agent !== undefined">
      <q-select class="col-md-6 col-xs-8 q-my-xs q-px-xs" filled v-model.number="complain.editor_id"
                :options="supportagents.filter((sa) => sa.department_id === complain.department_id)"
                :option-label="(sa) => sa.user===undefined? '': `${sa.user.name} (${sa.user.phone})`"
                option-value="id" emit-value
                map-options
                label="Editor"
                :disable="statusIndex > 1 || !hasTeamLeaderPermission"
      />

      <q-btn class="bg-purple text-white" no-caps label="Confirm Ticket" type="submit"
             :disable="$store.getters.getActionRunningState"/>
    </div>

    <div v-else-if="statusIndex === 2">

      <q-select class="col-md-6 col-xs-8 q-my-xs q-px-xs" filled v-model.number="complain.editor_id"
                :options="supportagents.filter((sa) => sa.department_id === complain.department_id)"
                :option-label="(sa) => sa.user===undefined? '': `${sa.user.name} (${sa.user.phone})`"
                option-value="id" emit-value
                map-options
                label="Editor"
                :disable="statusIndex > 1 || !hasTeamLeaderPermission"
      />


      <q-btn class="bg-info text-white q-mr-sm" label="Save" type="button"
             @click="updateComplain(false)"
             :disable="$store.getters.getActionRunningState"/>

      <q-btn class="bg-purple text-white" label="Submit" type="submit"
             :disable="$store.getters.getActionRunningState"/>
    </div>


    <div v-else-if="statusIndex === 3">
      <!--      <q-btn class="bg-info text-white q-mr-sm" label="Resubmit" type="button"-->
      <!--             @click="updateComplain(false)"-->
      <!--             :disable="this.$store.getters.getActionRunningState"/>-->
      <q-btn class="bg-info text-white q-mr-sm" label="Save" type="button"
             @click="updateComplain(false)"
             :disable="$store.getters.getActionRunningState"/>

      <q-btn class="bg-purple text-white" label="Approve" type="submit"
             :disable="$store.getters.getActionRunningState"/>
    </div>

    <div v-if="complain.status === 'overdue'">
      <q-btn class="bg-purple text-white q-mx-md" label="Approve" type="submit"
             :disable="$store.getters.getActionRunningState"/>

      <q-btn class="bg-purple text-white" label="Reopen" type="submit"
             :disable="$store.getters.getActionRunningState"/>
    </div>

    <!--    Action bar for complain according to status-->

  </q-form>
</template>

<script>
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

export default {
  name: "ComplainForm",
  components: {
    vSelect
  },
  props: {
    existingComplain: {
      type: Object,
      default: () => {
        return {}
      }
    },
    supportagents: {
      type: Array,
      default: () => {
        return []
      }
    }
  },
  data() {
    return {
      statusList: ['pending', 'assigned', 'working', 'finished', 'approved'],
      customerIds: [],
      search: '',
      complain: {
        customer_id: '',
        name: '',
        phone: '',
        email: '',
        helptopic_id: '',
        complain_text: '',
        customer_file: null
      }
    }
  },
  computed: {
    statusIndex: function () {
      console.log(this.statusList.indexOf(this.complain.status));
      return this.statusList.indexOf(this.complain.status)
    },
    isAuthenticated: function () {
      return this.$store.getters.getUser !== null && this.$store.getters.getUser.phone !== undefined
    },
    isComplainEditor: function () {
      return (this.$store.getters.getUser !== null && this.$store.getters.getUser.support_agent ===
        undefined) ? false : this.complain.editor_id ===
        this.$store.getters.getUser.support_agent.id
    },
    hasTeamLeaderPermission: function () {
      return (this.$store.getters.getUser !== null && this.$store.getters.getUser.support_agent ===
        undefined) ? false : this.$store.getters.getDepartments.find((d) => (d.leader_id ===
        this.$store.getters.getUser.support_agent.id &&
        d.id ===
        this.complain.department_id))
    },
    selectSlaPlan() {
      return (this.complain.helptopic_id === '' ? '' : this.$store.getters.getSLAPlans.find((s) => s.id ===
        this.complain.slaplan_id).name)
    }
  },
  mounted() {
    if (this.existingComplain.status !== undefined) {
      this.complain = {
        ...this.existingComplain,
        name: this.existingComplain.customer.user.name,
        phone: this.existingComplain.customer.user.phone,
        email: this.existingComplain.customer.user.email,
      }
      this.setSlaTopic()
    }

    if (this.complain.editor_id === null && this.$store.getters.getUser !== null && this.$store.getters.getUser.support_agent !== undefined) {
      this.complain.editor_id = this.$store.getters.getUser.support_agent.id
    } else if (this.$store.getters.getUser !== null && this.$store.getters.getUser.customer !== undefined) {
      this.complain.customer_id = this.$store.getters.getUser.customer.id
      this.complain.name = this.$store.getters.getUser.name
      this.complain.phone = this.$store.getters.getUser.phone
      this.complain.email = this.$store.getters.getUser.email
    }

    if (this.$store.getters.hasAuthorityAccess) {
      this.fetchCustomerIds()
    }
  },
  methods: {
    setSlaTopic() {
      let s = this.$store.getters.getSLAPlans.find((sla) => sla.helptopic_id === this.complain.helptopic_id)
      this.complain.slaplan_id = (s !== undefined ? s.id : '')
    },
    fetchCustomerIds() {
      this.$axios.get('customers/customer_id/all')
        .then((res) => {
          this.customerIds = res.data.map((c) => c.customer_id)
        })
    },

    searchCustomer() {
      this.$axios.get(`customers/${this.search}/customer_id`)
        .then((res) => {
          this.complain.customer_id = res.data.id
          this.complain.name = res.data.user.name
          this.complain.email = res.data.user.email
          this.complain.phone = res.data.user.phone
        })
    },

    createComplain() {
      if (this.isAuthenticated && this.$store.getters.getUser.callcenter_agent !== undefined) {
        this.complain.agent_id = this.$store.getters.getUser.callcenter_agent.id
      }
      this.$axios.post('complains', this.complain)
        .then((res) => {
          if (res.status === 201) {
            this.complain = {}
            this.$root.$emit('complain-created', res.data)
          }
        })

    },

    updateComplain(isStatusChanged) {
      const prevStatus = this.complain.status
      if (isStatusChanged) {
        if (prevStatus === 'overdue') {
          this.complain.status = 'pending'
        } else {
          this.complain.status = this.statusList[this.statusList.indexOf(this.complain.status) + 1]

          if (this.complain.status === 'assigned' && this.complain.agent_id === null) {
            this.complain.agent_id = (this.isAuthenticated && this.$store.getters.getUser.callcenter_agent !==
              undefined) ? this.$store.getters.getUser.callcenter_agent.id : null
          }
        }
      }


      this.$axios.put(`complains/${this.complain.id}`, this.complain)
        .then((res) => {
          this.$root.$emit('complain-updated', res.data)
        }).catch((e) => {
        if (isStatusChanged) {
          this.complain.status = prevStatus
        }
      })

    }
  }
}
</script>

<style scoped>

</style>
