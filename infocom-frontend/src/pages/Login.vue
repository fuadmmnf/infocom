<template>
  <q-page class="window-height window-width row justify-center items-center">
    <div class="column">
      <div class="row">
        <h5 class="text-h5  q-my-md">Infocom Login</h5>
      </div>
      <div class="row">
        <q-card class="shadow-1">
          <q-card-section>
            <q-form class="q-gutter-md">
              <q-input filled clearable v-model="email" type="email" label="email"/>
              <q-input filled clearable v-model="password" type="password" label="password"/>
            </q-form>
          </q-card-section>
          <q-card-actions class="q-px-md">
            <q-btn unelevated color="light-green-7" size="lg" class="full-width" label="Login"/>
          </q-card-actions>
          <q-card-section class="text-center q-pa-none">
<!--            <p class="text-grey-6">Not reigistered? Created an Account</p>-->
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
      email: '',
      password: ''
    }
  }
}
</script>
