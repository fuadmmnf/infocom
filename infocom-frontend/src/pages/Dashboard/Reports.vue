<template>
  <q-page class="q-pa-md">
    <div class="row ">
      <q-date class="col-3 q-ma-sm" v-model="departmentLogRange" range color="secondary"
              subtitle="Department Activity Log">
        <template>
          <div class="flex flex-center items-center ">
            <q-select
              v-if="!isSupportAgent"
              class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"

              :options="departments" option-label="name"
              option-value="id" emit-value
              map-options label="Department"

            />
            <download-excel class="col-2 q-mr-sm" type="csv"
                            :name="generateFileName('department_activity_log', departmentLogRange)"
                            :header="generateFileName('Department Activity Log', departmentLogRange).split('.')[0].replace('_', ' ')"
                            :fetch="fetchDataDepartmentLog"
            >
              <q-btn

                color="secondary"
                icon="text_snippet"
              />
            </download-excel>
          </div>
        </template>
      </q-date>


      <!--      <q-date class="col-3 q-ma-sm" v-model="complainStatusRange" range color="indigo-10"-->
      <!--              subtitle="Complain Status Report">-->
      <!--        <template>-->
      <!--          <div class="flex flex-center items-center ">-->
      <!--            <q-select class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"-->
      <!--                      :options="departments" option-label="name"-->
      <!--                      option-value="id" emit-value-->
      <!--                      map-options label="Department"-->
      <!--                      :readonly="isSupportAgent"-->
      <!--            />-->
      <!--            <download-excel class="col-2 q-mr-sm"-->
      <!--                            :name="generateFileName('complain_report', complainStatusRange)"-->
      <!--                            :header=" generateFileName('complain_report', complainStatusRange)"-->
      <!--                            :fetch="fetchComplainStatusLog"-->
      <!--            >-->
      <!--              <q-btn-->
      <!--                color="indigo-10"-->
      <!--                icon="text_snippet"-->
      <!--              />-->
      <!--            </download-excel>-->
      <!--          </div>-->
      <!--        </template>-->
      <!--      </q-date>-->


      <q-date class="col-3 q-ma-sm" v-model="topicStatusRange" range color="indigo" subtitle="Topic/POP Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select
              v-if="!isSupportAgent"
              class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
              :options="departments" option-label="name"
              option-value="id" emit-value
              map-options label="Department"

            />
            <download-excel class="col-2 q-mr-sm" type="csv"
                            :name="generateFileName('pop_topic_log', topicStatusRange)"
                            :header="generateFileName('Pop Topic Log', topicStatusRange).split('.')[0].replace('_', ' ')"
                            :fetch="fetchDataTopicLog"
            >
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
            <q-select
              v-if="!isSupportAgent"
              class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
              :options="departments" option-label="name"
              option-value="id" emit-value
              map-options label="Department"

            />
            <download-excel class="col-2 q-mr-sm" type="csv"
                            :name="generateFileName('service_time_log', serviceTimeRange)"
                            :header="generateFileName('Service Time Log', serviceTimeRange).split('.')[0].replace('_', ' ')"
                            :fetch="fetchDataServiceLog"
            >
              <q-btn
                color="blue-grey"
                icon="text_snippet"
              />
            </download-excel>
          </div>
        </template>
      </q-date>

      <q-date class="col-3 q-ma-sm" v-model="popStatusRange" range color="secondary" subtitle="POP Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select
              v-if="!isSupportAgent"
              class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
              :options="departments" option-label="name"
              option-value="id" emit-value
              map-options label="Department"
            />
            <download-excel class="col-2 q-mr-sm" type="csv"
                            :name="generateFileName('pop_log', popStatusRange)"
                            :header="generateFileName('Pop Log', popStatusRange).split('.')[0].replace('_', ' ')"
                            :fetch="fetchDataPopLog"
            >
              <q-btn
                color="purple-9"
                icon="text_snippet"
              />
            </download-excel>
          </div>
        </template>
      </q-date
      >

      <q-date class="col-3 q-ma-sm" v-model="servicepopStatusRange" range color="indigo" subtitle="Service/POP Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select
              v-if="!isSupportAgent"
              class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
              :options="departments" option-label="name"
              option-value="id" emit-value
              map-options label="Department"
            />
            <download-excel class="col-2 q-mr-sm" type="csv"
                            :name="generateFileName('servicewisepop_log', servicepopStatusRange)"
                            :header="generateFileName('Service/Pop Log', servicepopStatusRange).split('.')[0].replace('_', ' ')"
                            :fetch="fetchDataServiceWisePopLog"
            >
              <q-btn
                color="purple-9"
                icon="text_snippet"
              />
            </download-excel>
          </div>
        </template>
      </q-date>

      <q-date class="col-3 q-ma-sm" v-model="feedbackStatusRange" range color="blue-gray" subtitle="Feedback Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select
              v-if="!isSupportAgent"
              class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
              :options="departments" option-label="name"
              option-value="id" emit-value
              map-options label="Department"
            />
            <download-excel class="col-2 q-mr-sm" type="csv"
                            :name="generateFileName('feedback_log', feedbackStatusRange)"
                            :header="generateFileName('Feedback Log', feedbackStatusRange).split('.')[0].replace('_', ' ')"
                            :fetch="fetchDataFeedbackLog"
            >
              <q-btn
                color="purple-9"
                icon="text_snippet"
              />
            </download-excel>
          </div>
        </template>
      </q-date>


      <q-date class="col-3 q-ma-sm" v-model="complainStatRange" range color="blue-gray" subtitle="Complain Status Report">
        <template>
          <div class="flex flex-center items-center ">

            <download-excel class="col-2 q-mr-sm" type="csv"
                            :name="generateFileName('complain_stat', complainStatRange)"
                            :header="generateFileName('Complain Stat', complainStatRange).split('.')[0].replace('_', ' ')"
                            :fetch="fetchComplainStat"
            >
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
  components: {'downloadExcel': JsonExcel},
  data() {
    return {
      isSupportAgent: false,
      departments: [
        {id: '', name: 'all depts'},
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
      servicepopStatusRange: {
        to: '',
        from: ''
      },
      feedbackStatusRange: {
        to: '',
        from: ''
      },

      complainStatRange: {
        to: '',
        from: ''
      },
    }
  },
  mounted() {
    this.isSupportAgent = this.$store.getters.getUser.support_agent !== undefined
    this.selectedDepartmentId = this.isSupportAgent ? this.$store.getters.getUser.support_agent.department_id : ''
    // this.departmentLogRange.department_id = dept_id
    // this.complainStatusRange.department_id = dept_id
    // this.topicStatusRange.department_id = dept_id
    // this.serviceTimeRange.department_id = dept_id
    // this.popStatusRange.department_id = dept_id
  },
  methods: {
    generateFileName(title, range) {
      if (range === null) {
        return ''
      }
      return `${title}_${range.to.replaceAll('/', '-')}--${range.from.replaceAll('/', '-')}.csv`
    },
    async fetchDataDepartmentLog() {
      if (this.departmentLogRange !== null) {
        const response = await this.$axios.get(`reports/departmentlog?department_id=${this.selectedDepartmentId}&start=${this.departmentLogRange.from.replaceAll('/', '-')}&end=${this.departmentLogRange.to.replaceAll('/', '-')}`)
        return response.data.rows
      }

    },
    // async fetchComplainStatusLog() {
    //   const response = await this.$axios.get(`reports/complainstatus?department_id=${this.selectedDepartmentId}&start=${this.departmentLogRange.from.replaceAll('/', '-')}&end=${this.departmentLogRange.to.replaceAll('/', '-')}`)
    //   return response.data.rows
    // }

    async fetchDataTopicLog() {
      if (this.topicStatusRange !== null) {
        const response = await this.$axios.get(`reports/topicwisepop?department_id=${this.selectedDepartmentId}&start=${this.topicStatusRange.from.replaceAll('/', '-')}&end=${this.topicStatusRange.to.replaceAll('/', '-')}`)
        return response.data.rows
      }
    },

    async fetchDataServiceLog() {
      if (this.serviceTimeRange !== null) {
        const response = await this.$axios.get(`reports/servicetime?department_id=${this.selectedDepartmentId}&start=${this.serviceTimeRange.from.replaceAll('/', '-')}&end=${this.serviceTimeRange.to.replaceAll('/', '-')}`)
        return response.data.rows
      }

    },

    async fetchComplainStat() {
      if (this.complainStatRange !== null) {
        const response = await this.$axios.get(`reports/complainstat?start=${this.complainStatRange.from.replaceAll('/', '-')}&end=${this.complainStatRange.to.replaceAll('/', '-')}`)
        return response.data.rows
      }

    },

    async fetchDataPopLog() {
      if (this.popStatusRange !== null) {
        const response = await this.$axios.get(`reports/pop?department_id=${this.selectedDepartmentId}&start=${this.popStatusRange.from.replaceAll('/', '-')}&end=${this.popStatusRange.to.replaceAll('/', '-')}`)
        return response.data.rows
      }
    },

    async fetchDataServiceWisePopLog() {
      if (this.servicepopStatusRange !== null) {
        const response = await this.$axios.get(`reports/servicewisepop?department_id=${this.selectedDepartmentId}&start=${this.servicepopStatusRange.from.replaceAll('/', '-')}&end=${this.servicepopStatusRange.to.replaceAll('/', '-')}`)
        return response.data.rows
      }
    },

    async fetchDataFeedbackLog() {
      if (this.feedbackStatusRange !== null) {
        const response = await this.$axios.get(`reports/feedback?department_id=${this.selectedDepartmentId}&start=${this.feedbackStatusRange.from.replaceAll('/', '-')}&end=${this.feedbackStatusRange.to.replaceAll('/', '-')}`)
        return response.data.rows
      }
    },
  }
}
</script>
