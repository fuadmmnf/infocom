<template>
  <q-page class=" q-ma-md">

      <div class="q-gutter-y-md q-pa-sm">
          <q-tabs
            v-model="tab"
            dense
            class="text-grey"
            active-color="primary"
            indicator-color="primary"
            narrow-indicator
          >
            <q-tab name="helptopics" label="Help Topics" />
            <q-tab name="slaplans" label="SLA Plans" />
            <q-tab name="popaddresses" label="Pop Addresses" />
            <q-tab name="departments" label="Departments" />
            <q-tab name="services" label="Services" />
          </q-tabs>

          <q-separator />

          <q-tab-panels v-model="tab" animated >
            <q-tab-panel name="helptopics">
              <resource-table class="" title="Help Topics" resource_url="helptopics"/>
            </q-tab-panel>

            <q-tab-panel name="slaplans">
              <resource-table class="q-px-md" title="SLA Plans" resource_url="slaplans"/>
            </q-tab-panel>

            <q-tab-panel name="popaddresses">
              <resource-table class="q-px-md" title="Pop Addresses" resource_url="popaddresses"/>
            </q-tab-panel>

            <q-tab-panel name="departments">
              <resource-table class="q-px-md" title="Departments" resource_url="departments"/>
            </q-tab-panel>

            <q-tab-panel name="services">
              <resource-table class="q-px-md" title="Services" resource_url="services"/>
            </q-tab-panel>

          </q-tab-panels>
      </div>
  </q-page>
</template>

<script>
import ResourceTable from "components/ResourceTable";

export default {
  name: 'DashboardResources',
  components: {ResourceTable},
  data() {
    return {
      tab: 'helptopics',

    }
  },
  methods: {

  }
}
</script>
