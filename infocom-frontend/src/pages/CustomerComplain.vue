<template>
  <q-page>
    <div>
      <div class="row justify-center q-my-md">
        <h5 class="text-center text-h5 q-my-md">Customer Complain Form</h5>
      </div>
      <div class="row justify-center q-my-md">
        <q-card flat bordered class="q-pa-lg" style="min-width: 80%">
          <q-card-section>
            <complain-form :helptopics="$store.getters.getHelpTopics"/>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>

import ComplainForm from "components/Complain/ComplainForm";
export default {
  name: 'CusomerComplain',
  components: {ComplainForm},
  data() {
    return {
    }
  },
  mounted() {
    this.fetchHelpTopics()
    this.$root.$on('complain-created', (complain) => {
      this.$q.notify({
        type: 'positive',
        message: `Complain Submitted Successfully`,
        position: 'top-right'
      })
      this.$router.replace({name: 'home'})
    })
  },
  methods: {
    fetchHelpTopics() {
      this.$axios.get('helptopics')
        .then((res) => {
          this.$store.commit('setHelpTopics', res.data)
        }).catch(e => console.error(e))
    }
  }
}
</script>
