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
        </q-toolbar-title>

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
                <img alt="logo" src="~assets/logo.png" />
              </q-avatar>
            </q-item-section>
            <q-item-section>
              <q-item-label  class="text-subtitle2">Infocom CMS</q-item-label>
            </q-item-section>
          </q-item>
        </q-item-label>
        <EssentialLink
          v-for="(link, idx) in essentialLinks"
          :key="idx"
          :title="link.title"
          :link="link.route"
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
  components: { EssentialLink },
  data () {
    return {
      leftDrawerOpen: false,
      essentialLinks: sidenavdata(),
    }
  }
}
</script>
