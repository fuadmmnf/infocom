<template>
  <q-page class="flex flex-center">
    <div v-if="!feedbackStored" class="q-pa-md">
      <div class="q-gutter-y-md column">
        <q-form @submit="storeFeedback">
          <q-rating
            v-model.number="complain.customer_rating"
            size="3.5em"
            color="yellow"
            icon="star_border"
            icon-selected="star"
          />

          <q-input
            class="q-my-xs"
            v-model="complain.customer_review"
            filled
            type="textarea"
            label="Let us know your feedbacks"
          />

          <q-btn class="q-my-xs" label="Submit" type="submit" color="primary" />
        </q-form>

      </div>

    </div>
    <div v-else>
      <h3>Your feedback has been submitted successfully</h3>
    </div>

  </q-page>
</template>

<script>
export default {
  name: 'CustomerComplainFeedback',
  data() {
    return {
      feedbackStored: false,
      complain: {}
    }
  },
  mounted() {
  },
  methods: {
    storeFeedback() {
      this.$axios.put(`complains/${this.$route.params.complain_code}/feedback`, this.complain)
        .then((res) => {
          if (res.status === 204) {
            //thank you msg
            this.$q.notify({
              type: 'positive',
              message: `Thank you for your feedback`,
              position: 'top-right'
            })
            this.feedbackStored = true
          }
        })
    }
  }
}
</script>
