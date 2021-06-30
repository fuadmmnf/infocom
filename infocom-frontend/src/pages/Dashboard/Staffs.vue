<template>
  <q-page>
    <q-table
      v-if="$store.getters.hasAdminAccess"
      :title="$route.params.type === 'supportagents'? 'Support Agents': 'Call Center Agents'"
      :data="staffs"
      :columns="columns"
      row-key="id"
      :rows-per-page-options="[0]"
      :pagination.sync="pagination"
      hide-bottom
      @row-click="(e, row, idx) => {openStaffModal(row)}"
    >
      <template v-slot:top-right>
        <q-btn label="Create" @click="openStaffModal(null)">
          <q-dialog v-model="showStaffForm" persistent>
            <q-card style="min-width: 350px">
              <q-form @submit.prevent.stop="staffForm.id === undefined? createStaff(): updateStaff()">

                <q-card-section class="q-pa-xs">
                  <q-list bordered padding>
                    <q-item-label header>Create New
                      {{ $route.params.type === 'supportagents' ? 'Support Agent' : 'Callcenter Agent' }}
                    </q-item-label>

                    <q-item>
                      <q-item-section>
                        <q-input
                          filled
                          v-model="staffForm.name"
                          label="Name"
                          :rules="[val => (!!val ) || 'Enter staff name']"
                        />

                        <q-input
                          filled
                          v-model="staffForm.phone"
                          label="Phone"
                          type="tel"
                          :rules="[val => (!!val && val.length === 11) || 'Enter 11 digit staff phone number']"
                        />

                        <q-input
                          filled
                          v-model="staffForm.email"
                          label="Email"
                          type="email"
                          :rules="[val => (!!val ) || 'Enter valid email']"
                        />

                        <q-select class="q-mb-md" v-if="$route.params.type === 'supportagents'" filled
                                  v-model.number="staffForm.department_id"
                                  :options="departments" option-label="name"
                                  option-value="id" emit-value
                                  map-options label="Department" />

                        <q-input
                          filled
                          v-if="staffForm.id === undefined"
                          v-model="staffForm.password"
                          label="Password"
                          type="password"
                          :rules="[val => (!!val ) || 'Enter staff password']"
                        />

                        <q-input
                          filled
                          v-if="staffForm.id === undefined"
                          v-model="staffForm.password_confirmation"
                          label="Password Confirmation"
                          type="password"
                          :rules="[
                            val => (!!val ) || 'Confirm staff password',
                            val => (val === staffForm.password) || 'Password not matching'
                          ]"
                        />
                      </q-item-section>
                    </q-item>
                  </q-list>

                </q-card-section>

                <q-card-actions align="right" class="text-primary">
                  <q-btn flat label="Close" v-close-popup />
                  <q-btn v-if="staffForm.id !== undefined" type="button" flat color="negative"
                         label="Delete" @click="deleteStaff"
                  />
                  <q-btn flat
                         :disable="(staffForm.id !== undefined || staffForm.password !== staffForm.password_confirmation) && ( staffForm.name ==='' || staffForm.phone === '' || staffForm.email === '')"
                         label="Confirm"
                         type="submit" />
                </q-card-actions>
              </q-form>
            </q-card>
          </q-dialog>
        </q-btn>
      </template>
    </q-table>
  </q-page>
</template>

<script>
const staffFormTemplate = () => {
  return {
    name: '',
    email: '',
    phone: '',
    department_id: '',
    password: '',
    password_confirmation: '',
  }
}
export default {
  name: 'DashboardStaffs',
  data() {
    return {
      showStaffForm: false,
      pagination: {
        page: 1,
        rowsPerPage: 0 // 0 means all rows
      },
      departments: [],
      staffs: [],
      staffForm: staffFormTemplate(),
      columns: [
        { name: 'name', align: 'center', label: 'Name', field: row => row.user.name, sortable: true },
        { name: 'phone', align: 'center', label: 'Phone', field: row => row.user.phone },
        { name: 'email', align: 'center', label: 'Email', field: row => row.user.email },
      ].concat(this.$route.params.type === 'supportagents' ?
        [{
          name: 'department',
          align: 'center',
          label: 'Department',
          field: row => row.department === null? '': row.department.name,
          sortable: true
        }] : []
      )
    }
  },
  mounted() {
    this.fetchDepartments()
    this.fetchStaffs()
  },
  methods: {
    openStaffModal(staff) {
      if (staff === null) {
        this.staffForm = staffFormTemplate()
      } else {
        this.staffForm = {
          ...staffFormTemplate(),
          ...staff,
          name: staff.user.name,
          phone: staff.user.phone,
          email: staff.user.email,
          department_id: staff.department === null || staff.department === undefined? '': staff.department_id
        }
      }
      this.showStaffForm = true
    },
    fetchDepartments() {
      this.$axios.get('departments')
        .then((res) => {
          this.departments = res.data
        })
    },
    fetchStaffs() {
      this.$axios.get(this.$route.params.type)
        .then((res) => {
          this.staffs = res.data
        })
    },
    createStaff() {
      this.$axios.post(this.$route.params.type, this.staffForm)
        .then((res) => {
          if (res.status === 201) {
            this.showStaffForm = false
            this.fetchStaffs()
            this.staffForm = staffFormTemplate()
            this.$q.notify({
              type: 'positive',
              message: `Staff Created Successfully`,
              position: 'top-right'
            })
          }
        })
    },

    updateStaff() {
      this.$axios.put(`${this.$route.params.type}/${this.staffForm.id}`, this.staffForm)
        .then((res) => {
          if (res.status === 204) {
            this.showStaffForm = false
            this.fetchStaffs()
            this.staffForm = staffFormTemplate()
            this.$q.notify({
              type: 'positive',
              message: `Staff Updated Successfully`,
              position: 'top-right'
            })
          }
        })
    },
    deleteStaff() {
      this.$axios.delete(`${this.$route.params.type}/${this.staffForm.id}`)
        .then((res) => {
          if (res.status === 204) {
            this.showStaffForm = false
            this.fetchStaffs()
            this.staffForm = staffFormTemplate()
            this.$q.notify({
              type: 'positive',
              message: `Staff Deleted Successfully`,
              position: 'top-right'
            })
          }
        })
    }
  }
}
</script>
