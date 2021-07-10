<template>
  <q-page class="flex flex-center">
    <div class="row justify-center q-my-md">
      <q-card flat bordered class="q-pa-lg" style="min-width: 40%">
        <q-card-section>
          <div class="row">
            <q-input class="col-12 q-my-xs q-px-xs" filled :value="$store.getters.getUser.name" readonly label="Name" />
            <q-input class="col-12 q-my-xs q-px-xs" filled :value="$store.getters.getUser.phone" readonly
                     label="Phone" />
            <q-input class="col-12 q-my-xs q-px-xs" filled :value="$store.getters.getUser.email" readonly
                     label="Email" />

          </div>
          <br />
          <div class="row q-px-xs ">
            <q-btn class="" label="Change Password" type="button"
                   :disable="this.$store.getters.getActionRunningState" @click="showChangePasswordModal = true" />

            <q-dialog v-model="showChangePasswordModal" persistent>

              <q-card style="min-width: 350px">
                <q-form @submit="changePassword"
                        class="q-gutter-md">
                  <q-card-section class="q-pa-xs">

                    <q-list bordered padding>
                      <q-item-label header>Change Your Password</q-item-label>

                      <q-item>
                        <q-item-section>

                          <q-input
                            filled
                            v-model="changePasswordForm.old_password"
                            type="password"
                            label="Old Password"
                            :rules="[val => (!!val ) || 'Enter Old Password']"
                          />


                          <q-input
                            filled
                            v-model="changePasswordForm.password"
                            label="Password"
                            type="password"
                            :rules="[val => (!!val ) || 'Enter staff password']"
                          />

                          <q-input
                            filled
                            v-model="changePasswordForm.password_confirmation"
                            label="Password Confirmation"
                            type="password"
                            :rules="[
                            val => (!!val ) || 'Confirm staff password',
                            val => (val === changePasswordForm.password) || 'Password not matching'
                          ]"
                          />
                        </q-item-section>
                      </q-item>
                    </q-list>
                  </q-card-section>

                  <q-card-actions align="right" class="text-primary">
                    <q-btn type="button" flat label="Close" v-close-popup />

                    <q-btn type="submit" flat label="Confirm"
                    />
                  </q-card-actions>
                </q-form>

              </q-card>
            </q-dialog>

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
