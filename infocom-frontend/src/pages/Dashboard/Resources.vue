<template>
  <q-page class=" q-ma-md">
    <div v-if="$store.getters.hasAdminAccess" class="row justify-end">
      <q-btn label="Create" @click="showResourceForm = true">
        <q-dialog v-model="showResourceForm" persistent>
          <q-card style="min-width: 350px">

            <q-card-section class="q-pa-xs">

              <q-list bordered padding>
                <q-item-label header>Create New Resource</q-item-label>

                <q-item>
                  <q-item-section>
                    <q-select
                      filled
                      :options="resourceOptions"
                      v-model="resourceForm.type"
                      label="Type"
                      emit-value
                      :rules="[val => (!!val ) || 'Select resource type']"
                    />

                    <q-input
                      filled
                      v-model="resourceForm.name"
                      label="Name"
                      :rules="[val => (!!val ) || 'Enter resource name']"
                    />

                    <q-input
                      v-if="resourceForm.type === 'slaplans'"
                      filled
                      v-model.number="resourceForm.timelimit"
                      label="Time"
                      :rules="[val => (!!val ) || 'Enter time limit']"
                    />
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card-section>

            <q-card-actions align="right" class="text-primary">
              <q-btn flat label="Close" v-close-popup />
              <q-btn flat :disable="resourceForm.type === '' || resourceForm.name ===''" label="Confirm"
                     @click="createResource" />
            </q-card-actions>
          </q-card>
        </q-dialog>
      </q-btn>
    </div>
    <br />
    <div class="row">
      <resource-table class="q-px-md" title="Help Topics" resource_url="helptopics" />
      <resource-table class="q-px-md" title="SLA Plans" resource_url="slaplans" />
      <resource-table class="q-px-md" title="Pop Addresses" resource_url="popaddresses" />
      <resource-table class="q-px-md" title="Departments" resource_url="departments" />
    </div>
  </q-page>
</template>

<script>
import ResourceTable from "components/ResourceTable";

export default {
  name: 'DashboardResources',
  components: { ResourceTable },
  data() {
    return {
      showResourceForm: false,
      resourceOptions: [
        { label: 'Help Topic', value: 'helptopics' },
        { label: 'Pop Address', value: 'popaddresses' },
        { label: 'Department', value: 'departments' },
        { label: 'SLA Plan', value: 'slaplans' },
      ],
      resourceForm: {
        type: '',
        name: '',
        timelimit: '',
      }
    }
  },
  methods: {
    createResource() {
      this.$axios.post(this.resourceForm.type, this.resourceForm)
        .then((res) => {
          if (res.status === 201) {
            this.showResourceForm = false
            this.resourceForm = {}
            this.$root.$emit('resource-created')
            this.$q.notify({
              type: 'positive',
              message: `Resource Created Successfully`,
              position: 'top-right'
            })
          }
        })
    }
  }
}
</script>
