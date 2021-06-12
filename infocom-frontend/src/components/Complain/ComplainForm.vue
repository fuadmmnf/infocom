<template>
  <q-form @submit="complain.status === undefined? createComplain(): updateComplain()" @reset="complain = {}"
          class="q-gutter-md">
    <div class="row">
      <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled clearable v-model="complain.name" label="Full Name"/>
      <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled clearable v-model="complain.phone" label="Phone"/>
    </div>

    <div class="row q-my-none">
      <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled clearable v-model="complain.email" type="email"
               label="Email"/>
      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled clearable v-model.number="complain.helptopic_id"
                :options="$store.getters.getHelpTopics" option-label="name"
                option-value="id" emit-value
                map-options label="Help Topic"/>

    </div>

    <div v-if="$store.getters.getDepartments.length" class="row q-my-none">

      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled clearable v-model="complain.ticket_source"
                :options="[
                  {label: 'Agent Create', value: 'agent'},
                  {label: 'Customer Email', value: 'email'},
                ]"
                emit-value
                label="Ticket Source"/>

      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled clearable v-model.number="complain.department_id"
                :options="$store.getters.getDepartments" option-label="name"
                option-value="id" emit-value
                map-options label="Department"/>

    </div>


    <div v-if="slaplans.length" class="row q-my-none">

      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled clearable v-model.number="complain.slaplan_id"
                :options="$store.getters.getSlaPlans"
                option-label="name"
                option-value="id" emit-value
                map-options
                label="SLA Plan"/>

      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled clearable v-model="complain.priority"
                :options="[
                  {label: 'Low', value: 'low'},
                  {label: 'Medium', value: 'medium'},
                  {label: 'High', value: 'high'},
                  {label: 'Urgent', value: 'urgent'},
                ]"
                emit-value
                label="Priority"/>


    </div>


    <q-input v-if="$store.getters.getDepartments.length" class=" q-my-xs q-px-xs" filled clearable
             v-model="complain.complain_summary"
             type="textarea" autogrow
             label="Complain Summary"/>

    <q-input class=" q-my-xs q-px-xs" filled clearable v-model="complain.complain_text" type="textarea" autogrow
             label="Complain details"/>

    <div class="row q-px-xs ">
      <q-btn v-if="$store.getters.getDepartments.length" class="bg-red text-white q-mr-sm" label="Reset" type="reset"/>
      <q-btn class="bg-purple text-white" label="Submit" type="submit"
             :disable="this.$store.getters.getActionRunningState"/>

    </div>
  </q-form>
</template>

<script>
export default {
  name: "ComplainForm",
  props: {
    existingComplain: {
      type: Object,
      default: () => {
        return {}
      }
    },
  },
  data() {
    return {
      complain: {
        name: '',
        phone: '',
        email: '',
        helptopic_id: '',
        complain_text: '',
      }
    }
  },
  computed: {},
  mounted() {
    if (this.existingComplain.status !== undefined) {
      this.complain = {...this.existingComplain}
    }
  },
  methods: {
    createComplain() {
      this.$axios.post('complains', this.complain)
        .then((res) => {
          if (res.status === 201) {
            this.$root.$emit('complain-created', res.data)
          }
        })

    },

    updateComplain() {
      this.$axios.put('complains', this.complain)
        .then((res) => {
          if (res.status === 201) {
            this.$root.$emit('complain-created', res.data)
          }
        })

    }
  }
}
</script>

<style scoped>

</style>
