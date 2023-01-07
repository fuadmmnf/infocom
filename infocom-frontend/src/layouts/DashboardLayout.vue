<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
        />

        <q-toolbar-title>
          Infocom CMS Dashboard

          <q-badge transparent align="middle" color="teal">
            {{$store.getters.getUser.roles[0].name.replace('_', ' ')}}
          </q-badge>
        </q-toolbar-title>
        <div>
          <q-btn :to="{name: 'dashboard-profile'}" flat label="profile"/>
          <q-btn @click="logout" flat label="logout"/>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      content-class="bg-grey-1"
    >
      <q-list>
        <q-item-label
          header
          class="text-grey-8"
        >
          <q-item>
            <q-item-section side>
              <q-avatar rounded size="70px">
                <img alt="logo" src="~assets/logo.png"/>
              </q-avatar>
            </q-item-section>
            <q-item-section>
              <q-item-label class="text-subtitle2">Infocom CMS</q-item-label>
            </q-item-section>
          </q-item>
        </q-item-label>
        <EssentialLink
          v-for="(link, idx) in essentialLinks"
          :key="idx"
          :title="link.title"
          :link="link.route"
          :permission="link.permission"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view :key="$route.fullPath"/>
    </q-page-container>
  </q-layout>
</template>

<script>
import EssentialLink from 'components/EssentialLink.vue'
import sidenavdata from "assets/data/sidenav_items";

export default {
  name: 'DashboardLayout',
  components: {EssentialLink},
  data() {
    return {
      leftDrawerOpen: false,
      essentialLinks: sidenavdata(),
    }
  },
  mounted() {
    this.fetchPopAddresses()
    this.fetchDepartments()
    this.fetchHelpTopics()
    this.fetchSlaPlans()
    this.fetchServices()
  },
  methods: {
    fetchPopAddresses(){
      this.$axios.get('popaddresses')
        .then((res) => {
          this.$store.commit('setPopAddresses', res.data)
        })
    },
    fetchDepartments() {
      this.$axios.get('departments')
        .then((res) => {
          this.$store.commit('setDepartments', res.data)
        })
    },
    fetchSlaPlans() {
      this.$axios.get('slaplans')
        .then((res) => {
          this.$store.commit('setSLAPlans', res.data)
        })
    },
    fetchHelpTopics() {
      this.$axios.get('helptopics')
        .then((res) => {
          this.$store.commit('setHelpTopics', res.data)
        })
    },

    fetchServices(){
      this.$axios.get('services')
      .then((res) => {
        this.$store.commit('setServices', res.data)
      })
    },

    logout(){
      this.$store.commit('storeUser', null)
      this.$router.replace({name: 'home'})
    }
  }
}
</script>
