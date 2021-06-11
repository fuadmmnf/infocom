<template>
  <q-page >
    <div>
      <div  class="row justify-center q-my-md">
        <h5 class="text-center text-h5 q-my-md">Customer Complain Form</h5>
      </div>
      <div  class="row justify-center q-my-md">
        <q-card flat bordered class="q-pa-lg" style="min-width: 80%">
          <q-card-section >
            <ComplainForm :helptopics="[]"/>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import ComplainForm from "components/ComplainForm";
export default {
  name: 'CusomerComplain',
  components: {ComplainForm},
  data() {
    return {
      email: '',
      password: ''
    }
  }
}
</script>
