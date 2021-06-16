<template>
  <q-page class="q-pa-md">
    <div class="row ">
      <q-date class="col-3 q-ma-sm" v-model="departmentLogRange" range color="secondary"
              subtitle="Department Activity Log">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
                      :options="departments" option-label="name"
                      option-value="id" emit-value
                      map-options label="Department"
                      :readonly="isSupportAgent"
            />
            <download-excel class="col-2 q-mr-sm"
                            :name="generateFileName('department_activity_log', departmentLogRange)"
                            :header=" generateFileName('department_activity_log', departmentLogRange)"
                            :fetch="fetchDataDepartmentLog">
              <q-btn

                color="secondary"
                icon="text_snippet"
              />
            </download-excel>
          </div>
        </template>
      </q-date>


      <q-date class="col-3 q-ma-sm" v-model="complainStatusRange" range color="indigo-10"
              subtitle="Complain Status Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
                      :options="departments" option-label="name"
                      option-value="id" emit-value
                      map-options label="Department"
                      :readonly="isSupportAgent"
            />
            <download-excel class="col-2 q-mr-sm">
              <q-btn
                color="indigo-10"
                icon="text_snippet"
              />
            </download-excel>
          </div>
        </template>
      </q-date>


      <q-date class="col-3 q-ma-sm" v-model="topicStatusRange" range color="indigo" subtitle="POP/Topic Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
                      :options="departments" option-label="name"
                      option-value="id" emit-value
                      map-options label="Department"
                      :readonly="isSupportAgent"
            />
            <download-excel class="col-2 q-mr-sm">
              <q-btn
                color="indigo"
                icon="text_snippet"
              />
            </download-excel>
          </div>
        </template>
      </q-date>

      <q-date class="col-3 q-ma-sm" v-model="serviceTimeRange" range color="blue-grey" subtitle="Service Time Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
                      :options="departments" option-label="name"
                      option-value="id" emit-value
                      map-options label="Department"
                      :readonly="isSupportAgent"
            />
            <download-excel class="col-2 q-mr-sm">
              <q-btn
                color="blue-grey"
                icon="text_snippet"
              />
            </download-excel>
          </div>
        </template>
      </q-date>

      <q-date class="col-3 q-ma-sm" v-model="popStatusRange" range color="purple-9" subtitle="POP Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
                      :options="departments" option-label="name"
                      option-value="id" emit-value
                      map-options label="Department"
                      :readonly="isSupportAgent"
            />
            <download-excel class="col-2 q-mr-sm">
              <q-btn
                color="purple-9"
                icon="text_snippet"
              />
            </download-excel>
          </div>
        </template>
      </q-date>

    </div>

  </q-page>
</template>

<script>
import JsonExcel from "vue-json-excel";

export default {
  name: 'DashboardReports',
  components: { 'downloadExcel': JsonExcel },
  data() {
    return {
      isSupportAgent: false,
      departments: [
        { id: '', name: 'all depts' },
        ...this.$store.getters.getDepartments
      ],
      selectedDepartmentId: '',
      departmentLogRange: {
        to: '',
        from: ''
      },
      complainStatusRange: {
        to: '',
        from: ''
      },
      topicStatusRange: {
        to: '',
        from: ''
      },
      serviceTimeRange: {
        to: '',
        from: ''
      },
      popStatusRange: {
        to: '',
        from: ''
      },
    }
  },
  mounted() {
    this.isSupportAgent = this.$store.getters.getUser.support_agent !== undefined
    this.selectedDepartmentId = this.isSupportAgent ? this.$store.getters.getUser.support_agent.id : ''
    // this.departmentLogRange.department_id = dept_id
    // this.complainStatusRange.department_id = dept_id
    // this.topicStatusRange.department_id = dept_id
    // this.serviceTimeRange.department_id = dept_id
    // this.popStatusRange.department_id = dept_id
  },
  methods: {
    generateFileName(title, range) {
      return `${title}_${range.to.replaceAll('/', '-')}_${range.from.replaceAll('/', '-')}`
    },
    async fetchDataDepartmentLog() {
      const response = await this.$axios.get(`reports/departmentlog?department_id=${this.selectedDepartmentId}&start=${this.departmentLogRange.from.replaceAll('/', '-')}&end=${this.departmentLogRange.to.replaceAll('/', '-')}`)
      return response.data
    }
  }
}
</script>
