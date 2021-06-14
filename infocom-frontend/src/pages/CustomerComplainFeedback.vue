<template>
  <q-page class="flex flex-center">

  </q-page>
</template>

<script>
export default {
  name: 'CustomerComplainFeedback',
  data() {
    return {
      feedbackForm: {
        complain_rating: '',
        complain_review: ''
      },
    }
  },
  mounted() {
    // this.fetchComplainByCode()
  },
  methods: {
    // fetchComplainByCode() {
    //   this.$axios.get(`complains/${this.$route.params.complain_code}`)
    //     .then((res) => {
    //       this.complain = res.data
    //     })
    // },
    storeFeedback() {
      this.$axios.put(`complains/${this.$route.params.complain_code}/feedback`, this.feedbackForm)
        .then((res) => {
          if (res.status === 204) {
            //thank you msg
            this.$router.replace({ name: 'home' })
          }
        })
    }
  }
}
</script>
