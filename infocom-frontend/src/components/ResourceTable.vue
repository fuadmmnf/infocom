<template>
  <div>
    <q-table
      :title="title"
      :data="data"
      :columns="columns"
      row-key="id"
      :rows-per-page-options="[0]"
      :pagination.sync="pagination"
      hide-bottom
      @row-click="(e, row, idx) => {openResourceModal(row)}"
    >
      <template v-slot:top-right>
        <div>
          <q-btn v-if="supportagents.length" no-caps label="Assign Leader" @click="showLeaderAssignForm = true">
            <q-dialog v-model="showLeaderAssignForm" persistent>
              <q-card style="min-width: 350px">
                <q-form @submit="createDepartmentLeader">

                  <q-card-section class="q-pa-xs">
                    <q-list bordered padding>
                      <q-item-label header>Assign Department Leader
                      </q-item-label>

                      <q-item>
                        <q-item-section>
                          <q-select class="q-mb-md" filled
                                    v-model.number="departmentLeaderForm.department_id"
                                    :options="data" option-label="name"
                                    option-value="id" emit-value
                                    map-options label="Department" />


                          <q-select class="q-mb-md" filled
                                    v-model.number="departmentLeaderForm.leader_id"
                                    :options="supportagents.filter((sa) => sa.department_id === departmentLeaderForm.department_id)"
                                    :option-label="(sa) =>  sa.user !== undefined? `${sa.user.name} (${sa.user.phone})`: ''"
                                    option-value="id" emit-value
                                    map-options label="Team Leader" />


                        </q-item-section>
                      </q-item>
                    </q-list>

                  </q-card-section>

                  <q-card-actions align="right" class="text-primary">
                    <q-btn flat label="Close" v-close-popup />
                    <q-btn flat
                           :disable="departmentLeaderForm.department_id === '' || departmentLeaderForm.leader_id ===''"
                           label="Confirm"
                           type="submit" />
                  </q-card-actions>
                </q-form>
              </q-card>
            </q-dialog>
          </q-btn>

          <q-btn label="Create" @click="() => {
          openResourceModal(null)
        }">


            <q-dialog v-model="showResourceForm" persistent>

              <q-card style="min-width: 350px">
                <q-form @submit="resourceForm.id === undefined? createResource(): updateResource()"
                        @reset="resourceForm = {}"
                        class="q-gutter-md">
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
                            readonly
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

                          <q-select class="q-mb-md" filled
                                    v-if="resourceForm.type === 'slaplans'"
                                    v-model.number="resourceForm.helptopic_id"
                                    :options="$store.getters.getHelpTopics"
                                    option-label="name"
                                    option-value="id" emit-value
                                    :rules="[val => (!!val ) || 'Enter topic']"
                                    map-options label="Help Topic" />

                        </q-item-section>
                      </q-item>
                    </q-list>
                  </q-card-section>

                  <q-card-actions align="right" class="text-primary">
                    <q-btn type="button" flat label="Close" v-close-popup />
                    <q-btn v-if="resourceForm.id !== undefined" type="button" flat color="negative"
                           label="Delete" @click="deleteResource"
                    />

                    <q-btn type="submit" flat :disable="resourceForm.type === '' || resourceForm.name ===''"
                           label="Confirm"
                    />
                  </q-card-actions>
                </q-form>

              </q-card>
            </q-dialog>
          </q-btn>

        </div>

      </template>

    </q-table>
  </div>
</template>

<script>
const resourceFormTemplate = () => {
  return {
    type: '',
    name: '',
    timelimit: '',
    helptopic_id: '',
  }
}
export default {
  name: "ResourceTable",
  props: {
    title: {
      type: String,
      required: true
    },
    resource_url: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      showLeaderAssignForm: false,
      pagination: {
        page: 1,
        rowsPerPage: 0 // 0 means all rows
      },
      data: [],
      columns: [
        { name: 'name', align: 'center', label: 'Name', field: 'name', sortable: true },
      ],
      showResourceForm: false,
      resourceOptions: [
        { label: 'Help Topic', value: 'helptopics' },
        { label: 'Pop Address', value: 'popaddresses' },
        { label: 'Department', value: 'departments' },
        { label: 'SLA Plan', value: 'slaplans' },
        { label: 'Services', value: 'services' },
      ],
      resourceForm: resourceFormTemplate(),
      supportagents: [],
      departmentLeaderForm: {
        name: '',
        department_id: '',
        leader_id: '',
      }
    }
  },
  mounted() {
    if (this.resource_url === 'departments') {
      this.fetchSupportAgents()
      this.columns.push({
        name: 'leader',
        align: 'center',
        label: 'Leader',
        field: row => row.leader !== null ? `${row.leader.user.name} (${row.leader.user.phone})` : ''
      })
    } else if (this.resource_url === 'slaplans') {
      this.columns.push({
        name: 'timelimits',
        align: 'center',
        label: 'Limit',
        field: row => row.timelimit !== null ? `${row.timelimit} hours` : ''
      })

      this.columns.push({
        name: 'Help Topic',
        align: 'center',
        label: 'Topic',
        field: row => row.helptopic !== null ? `${row.helptopic.name}` : ''
      })
    }
    this.fetchResource()
    this.$root.$on('resource-created', this.fetchResource)
  },


  methods: {
    openResourceModal(resource) {
      if (resource === null) {
        this.resourceForm = resourceFormTemplate()
      } else {
        this.resourceForm = {
          ...resourceFormTemplate(),
          ...resource,
        }
      }
      this.resourceForm.type = this.resource_url
      this.showResourceForm = true
    },
    fetchResource() {
      this.$axios.get(this.resource_url)
        .then((res) => {
          this.data = res.data
          this.$store.commit('set' + this.title.replace(' ', ''), this.data)
        })
    },
    fetchSupportAgents() {
      this.$axios.get('supportagents')
        .then((res) => {
          this.supportagents = res.data
        })
    },
    createDepartmentLeader() {
      this.departmentLeaderForm.name = this.$store.getters.getDepartments.find((d) => d.id === this.departmentLeaderForm.department_id).name
      this.$axios.put(`departments/${this.departmentLeaderForm.department_id}`, this.departmentLeaderForm)
        .then((res) => {
          this.showLeaderAssignForm = false
          this.departmentLeaderForm = {
            name: '',
            department_id: '',
            leader_id: ''
          }
          this.$q.notify({
            type: 'positive',
            message: `Department Leader Assigned`,
            position: 'top-right'
          })
          this.fetchResource()


        })
    },
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
    },
    updateResource() {
      this.$axios.put(`${this.resourceForm.type}/${this.resourceForm.id}`, this.resourceForm)
        .then((res) => {
          if (res.status === 204) {
            this.showResourceForm = false
            this.fetchResource()
            this.resourceForm = resourceFormTemplate()
            this.$q.notify({
              type: 'positive',
              message: `Resource Updated Successfully`,
              position: 'top-right'
            })

          }

        })
    },
    deleteResource() {
      this.$axios.delete(`${this.resourceForm.type}/${this.resourceForm.id}`)
        .then((res) => {
          if (res.status === 204) {
            this.showResourceForm = false
            this.fetchResource()
            this.resourceForm = resourceFormTemplate()
            this.$q.notify({
              type: 'positive',
              message: `Resource Deleted Successfully`,
              position: 'top-right'
            })

          }

        })
    }
  }
}
</script>

<style scoped>

</style>
