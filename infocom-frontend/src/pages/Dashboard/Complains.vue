<template>
  <q-page>
    <div class="q-gutter-y-md q-pa-sm">
      <q-card>
        <q-tabs
          v-model="tab"
          dense
          class="text-grey"
          active-color="primary"
          indicator-color="primary"
          align="justify"
          narrow-indicator
        >
          <q-tab v-if="hasCallcenterAgentAccess" name="agent-complain" label="New Complain"/>

          <q-tab v-if="hasCallcenterAgentAccess" name="pending" label="Pending Complains"/>

          <q-tab v-if="hasSupportAgentAccess" name="assigned" label="Assigned Complains"/>

          <q-tab v-if="hasSupportAgentAccess" name="running" label="Running Complains"/>
          <q-tab v-if="hasCallcenterAgentAccess" name="feedback" label="Feedback Complains"/>
          <q-tab name="history" label="Complain History"/>
        </q-tabs>

        <q-separator/>

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel v-if="hasCallcenterAgentAccess" name="agent-complain">
            <div class="text-h6">New Customer Complain</div>
            <complain-form :helptopics="helptopics" :departments="departments" :slaplans="slaplans"/>
          </q-tab-panel>

          <q-tab-panel v-if="hasCallcenterAgentAccess" name="pending">
            <div class="text-h6">Pending Complains</div>
            <complains-list status="pending"/>
          </q-tab-panel>

          <q-tab-panel v-if="hasSupportAgentAccess" name="assigned">
            <div class="text-h6">Assigned Complains</div>
            <complains-list status="assigned"/>
          </q-tab-panel>

          <q-tab-panel v-if="hasSupportAgentAccess" name="running">
            <div class="text-h6">Running Complains</div>
          </q-tab-panel>


          <q-tab-panel v-if="hasCallcenterAgentAccess" name="feedback">
            <div class="text-h6">Feedback Complains</div>
          </q-tab-panel>


          <q-tab-panel name="history">
            <div class="text-h6">Complain History</div>
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import ComplainForm from "components/Complain/ComplainForm";
import ComplainsList from "components/Complain/ComplainsList";

export default {
  name: 'DashboardComplains',
  components: {ComplainsList, ComplainForm},
  data() {
    return {
      tab: 'agent-complain',
      departments: [],
      slaplans: [],
      helptopics: [],
    }
  },
  mounted() {
    this.fetchDepartments()
    this.fetchHelpTopics()
    this.fetchSlaPlans()
  },
  computed: {
    hasSupportAgentAccess: function (){
      return this.$store.getters.getUser.support_agent !== undefined ||
        this.$store.getters.getUser.super_admin !== undefined
    },

    hasCallcenterAgentAccess: function () {
      return this.$store.getters.getUser.callcenter_agent !== undefined ||
        this.$store.getters.getUser.super_admin !== undefined
    },

  },
  methods: {
    fetchDepartments() {
      this.$axios.get('departments')
        .then((res) => {
          this.departments = res.data
        })
    },
    fetchSlaPlans() {
      this.$axios.get('slaplans')
        .then((res) => {
          this.slaplans = res.data
        })
    },
    fetchHelpTopics() {
      this.$axios.get('helptopics')
        .then((res) => {
          this.helptopics = res.data
        })
    }
  }
}
</script>
