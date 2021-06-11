<template>
  <q-form  @submit="complain.status === undefined? createComplain(): updateComplain()" class="q-gutter-md">
    <div class="row">
      <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled clearable v-model="complain.name" label="Full Name"/>
      <q-input class="col-md-6 col-xs-12  q-my-xs q-px-xs" filled clearable v-model="complain.phone" label="Phone"/>
    </div>

    <div class="row q-my-none">
      <q-input class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled clearable v-model="complain.email" type="email" label="Email"/>
      <q-select class="col-md-6 col-xs-12 q-my-xs q-px-xs" filled clearable v-model="complain.helptopic_id" :options="helptopics" option-label="name"
                option-value="id" label="Help Topic"/>

    </div>
    <q-input class=" q-my-xs q-px-xs" filled clearable v-model="complain.complain_text" type="textarea" autogrow label="Complain details"/>
    <div class="row q-px-xs " >
      <q-btn class="bg-purple text-white" label="Submit" type="submit"/>

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
    helptopics: {
      type: Array,
      required: true
    }
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

    },

    updateComplain() {

    }
  }
}
</script>

<style scoped>

</style>
