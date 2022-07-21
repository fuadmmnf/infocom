<template>
  <q-page>
    <div class="row justify-start q-my-md">
      <q-card flat bordered class="col-4 q-pa-md q-ma-md" >
        <q-card-section>
          <div class="row">
            <q-input class="col-12 q-my-xs q-px-xs" filled :value="$store.getters.getUser.code" readonly label="Name" />
            <q-input class="col-12 q-my-xs q-px-xs" filled :value="$store.getters.getUser.name" readonly label="Name" />
            <q-input class="col-12 q-my-xs q-px-xs" filled :value="$store.getters.getUser.phone" readonly
                     label="Phone" />
            <q-input class="col-12 q-my-xs q-px-xs" filled :value="$store.getters.getUser.email" readonly
                     label="Email" />

          </div>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script>
export default {
  name: 'DashboardProfile',
  data() {
    return {
      showChangePasswordModal: false,
      changePasswordForm: {
        old_password: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  methods: {
    changePassword() {
      this.$axios.put('users/password', this.changePasswordForm)
        .then((res) => {
          if (res.status === 204) {
            this.showChangePasswordModal = false;
            this.changePasswordForm = {
              old_password: '',
              password: '',
              password_confirmation: ''
            }

            this.$q.notify({
              type: 'positive',
              message: `Password Changed Successfully`,
              position: 'top-right'
            })
          }
        }).catch((e) => {
        this.$q.notify({
          type: 'negative',
          message: `Password Change Failed`,
          position: 'top-right'
        })
      })
    }
  }
}
</script>
