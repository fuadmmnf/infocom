<template>
  <q-layout view="hHh lpr fFf">
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title>
          Infocom CMS
        </q-toolbar-title>

        <div>
          <q-btn :to="{name: 'customer-complain'}" flat label="create complain"/>
          <q-btn :to="{name: 'login'}" v-if="$store.getters.getUser === null" flat label="login"/>
          <q-btn :to="{name: 'dashboard-home'}" v-else flat label="dashboard"/>
        </div>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view :key="$route.fullPath"/>
    </q-page-container>
  </q-layout>
</template>

<script>


export default {
  name: 'MainLayout',
  data() {
    return {}
  },
  mounted() {
    this.fetchHelpTopics()
  },
  methods: {
    fetchHelpTopics() {
      this.$axios.get('helptopics')
        .then((res) => {
          this.$store.commit('setHelpTopics', res.data)
        })
    },
  }
}
</script>
