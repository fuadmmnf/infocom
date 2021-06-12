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
    >
      <template v-slot:top-right>
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
                                  map-options label="Department"/>


                        <q-select class="q-mb-md" filled
                                  v-model.number="departmentLeaderForm.leader_id"
                                  :options="supportagents.filter((sa) => sa.department_id === departmentLeaderForm.department_id)"
                                  :option-label="(sa) =>  sa.user !== undefined? `${sa.user.name} (${sa.user.phone})`: ''"
                                  option-value="id" emit-value
                                  map-options label="Team Leader"/>


                      </q-item-section>
                    </q-item>
                  </q-list>

                </q-card-section>

                <q-card-actions align="right" class="text-primary">
                  <q-btn flat label="Close" v-close-popup/>
                  <q-btn flat
                         :disable="departmentLeaderForm.department_id === '' || departmentLeaderForm.leader_id ===''"
                         label="Confirm"
                         type="submit"/>
                </q-card-actions>
              </q-form>
            </q-card>
          </q-dialog>
        </q-btn>
      </template>

    </q-table>
  </div>
</template>

<script>
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
        {name: 'name', align: 'center', label: 'Name', field: 'name', sortable: true},
      ],
      supportagents: [],
      departmentLeaderForm: {
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
    }
    this.fetchResource()
    this.$root.$on('resource-created', this.fetchResource)
  },
  methods: {
    fetchResource() {
      this.$axios.get(this.resource_url)
        .then((res) => {
          this.data = res.data
        })
    },
    fetchSupportAgents() {
      this.$axios.get('supportagents')
        .then((res) => {
          this.supportagents = res.data
        })
    },
    createDepartmentLeader() {
      this.$axios.put(`departments/${this.departmentLeaderForm.department_id}`, this.departmentLeaderForm)
        .then((res) => {
          this.showLeaderAssignForm = false
          this.departmentLeaderForm = {
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
    }
  }
}
</script>

<style scoped>

</style>
