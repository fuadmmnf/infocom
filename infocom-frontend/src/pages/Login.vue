<template>
  <q-page>
    <div>
      <div class="row justify-center q-my-md">
        <h5 class="text-center text-h5 q-my-md">Infocom Login</h5>
      </div>
      <div class="row justify-center q-my-md">
        <q-card flat bordered class="q-pa-lg" style="min-width: 40%">
          <q-card-section>
            <q-form @submit="login" class="q-gutter-md">
              <div class="row">
                <q-input class="col-12 q-my-xs q-px-xs" filled  type="email" v-model="loginForm.email" label="Email"/>
                <q-input class="col-12  q-my-xs q-px-xs" filled  v-model="loginForm.password" type="password" label="Password"/>
              </div>
              <div class="row q-px-xs ">
                <q-btn class="bg-purple text-white" label="Submit" type="login"
                       :disable="this.$store.getters.getActionRunningState"/>

              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      loginForm: {
        email: '',
        password: ''
      }
    }
  },
  methods: {
    login(){
      this.$axios.post('login', this.loginForm)
      .then((res) => {
        if(res.status === 200){
          this.$store.commit('storeUser', res.data)
          this.$router.replace({name: 'dashboard-home'})
        }
      })
    }
  }
}
</script>
