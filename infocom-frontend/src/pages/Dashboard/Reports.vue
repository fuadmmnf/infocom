<template>
  <q-page class="q-pa-md">
    <div class="row ">
      <q-date class="col-3 q-ma-sm" v-model="departmentLogRange" range color="secondary"
              subtitle="Department Activity Log">
        <template>
          <div>
            <div class="flex flex-center items-center ">
              <q-select
                v-if="!isSupportAgent"
                class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"

                :options="departments" option-label="name"
                option-value="id" emit-value
                map-options label="Department"

              />
            </div>

            <div class="q-mt-sm flex flex-center items-center ">
              <download-excel class="q-mr-sm" type="csv"
                              :name="generateFileName('department_activity_log', departmentLogRange)"
                              :header="generateFileName('Department Activity Log', departmentLogRange).split('.')[0].replace('_', ' ')"
                              :fetch="fetchDataDepartmentLog"
              >
                <q-btn

                  color="secondary"
                  icon="text_snippet"
                >excel
                </q-btn>
              </download-excel>

              <q-btn
                @click="()=>{fetchDataDepartmentLog(true)}"
                color="red-12"
                icon="picture_as_pdf"
              >pdf
              </q-btn>
            </div>

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


      <q-date class="col-3 q-ma-sm" v-model="popWiseTopicStatusRange" range color="indigo" subtitle="POP/Topic Report">
        <template>
          <div>
            <div class="flex flex-center items-center ">
              <q-select
                v-if="!isSupportAgent"
                class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
                :options="departments" option-label="name"
                option-value="id" emit-value
                map-options label="Department"

              />
            </div>
            <div class="flex flex-center items-center q-mt-sm">

              <download-excel class="col-2 q-mr-sm" type="csv"
                              :name="generateFileName('pop_topic_log', popWiseTopicStatusRange)"
                              :header="generateFileName('Pop Topic Log', popWiseTopicStatusRange).split('.')[0].replace('_', ' ')"
                              :fetch="fetchDataTopicLog"
              >
                <q-btn
                  color="secondary"
                  icon="text_snippet"
                >excel
                </q-btn>
              </download-excel>

              <q-btn
                @click="()=>{fetchDataTopicLog(true)}"
                color="red-12"
                icon="picture_as_pdf"
              >pdf
              </q-btn>
            </div>
          </div>
        </template>
      </q-date>

      <q-date class="col-3 q-ma-sm" v-model="serviceTimeRange" range color="blue-grey" subtitle="Service Time Report">
        <template>
          <div>
            <div class="flex flex-center items-center ">
              <q-select
                v-if="!isSupportAgent"
                class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
                :options="departments" option-label="name"
                option-value="id" emit-value
                map-options label="Department"

              />
            </div>

            <div class="flex flex-center items-center q-mt-sm">

              <download-excel class="col-2 q-mr-sm" type="csv"
                              :name="generateFileName('service_time_log', serviceTimeRange)"
                              :header="generateFileName('Service Time Log', serviceTimeRange).split('.')[0].replace('_', ' ')"
                              :fetch="fetchDataServiceLog"
              >
                <q-btn
                  color="secondary"
                  icon="text_snippet"
                >excel
                </q-btn>
              </download-excel>

              <q-btn
                @click="()=>{fetchDataServiceLog(true)}"
                color="red-12"
                icon="picture_as_pdf"
              >pdf
              </q-btn>
            </div>
          </div>
        </template>
      </q-date>

      <q-date class="col-3 q-ma-sm" v-model="popStatusRange" range color="secondary" subtitle="POP Report">
        <template>
          <div>
            <div class="flex flex-center items-center ">
              <q-select
                v-if="!isSupportAgent"
                class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
                :options="departments" option-label="name"
                option-value="id" emit-value
                map-options label="Department"
              />

            </div>
            <div class="flex flex-center items-center q-mt-sm">
              <download-excel class="col-2 q-mr-sm" type="csv"
                              :name="generateFileName('pop_log', popStatusRange)"
                              :header="generateFileName('Pop Log', popStatusRange).split('.')[0].replace('_', ' ')"
                              :fetch="fetchDataPopLog"
              >
                <q-btn

                  color="secondary"
                  icon="text_snippet"
                >excel
                </q-btn>
              </download-excel>

              <q-btn
                @click="()=> {fetchDataPopLog(true)}"
                color="red-12"
                icon="picture_as_pdf"
              >pdf
              </q-btn>
            </div>
          </div>

        </template>
      </q-date>

      <q-date class="col-3 q-ma-sm" v-model="helpTopicRange" range color="indigo" subtitle="Help Topic Report">
        <template>
          <div>
            <div class="flex flex-center items-center ">
              <q-select
                v-if="!isSupportAgent"
                class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
                :options="departments" option-label="name"
                option-value="id" emit-value
                map-options label="Department"
              />

            </div>
            <div class="flex flex-center items-center q-mt-sm">
              <download-excel class="col-2 q-mr-sm" type="csv"
                              :name="generateFileName('topic_log', helpTopicRange)"
                              :header="generateFileName('Topic Log', helpTopicRange).split('.')[0].replace('_', ' ')"
                              :fetch="fetchHelpTopicLog"
              >
                <q-btn
                  color="secondary"
                  icon="text_snippet"
                >excel
                </q-btn>
              </download-excel>

              <q-btn
                @click="()=> {fetchHelpTopicLog(true)}"
                color="red-12"
                icon="picture_as_pdf"
              >pdf
              </q-btn>
            </div>
          </div>
        </template>
      </q-date>


      <q-date class="col-3 q-ma-sm" v-model="statusRange" range color="blue-grey" subtitle="Status Report">
        <template>
          <div>
            <div class="flex flex-center items-center ">
              <q-select
                v-if="!isSupportAgent"
                class="col q-pr-xs " dense filled v-model.number="selectedDepartmentId"
                :options="departments" option-label="name"
                option-value="id" emit-value
                map-options label="Department"
              />

            </div>
            <div class="flex flex-center items-center q-mt-sm">
              <download-excel class="col-2 q-mr-sm" type="csv"
                              :name="generateFileName('topic_log', statusRange)"
                              :header="generateFileName('Topic Log', statusRange).split('.')[0].replace('_', ' ')"
                              :fetch="fetchStatusLog"
              >
                <q-btn

                  color="secondary"
                  icon="text_snippet"
                >excel
                </q-btn>
              </download-excel>

              <q-btn
                @click="()=> {fetchStatusLog(true)}"

                color="red-12"
                icon="picture_as_pdf"
              >pdf
              </q-btn>
            </div>
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
      popWiseTopicStatusRange: {
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
      helpTopicRange: {
        to: '',
        from: ''
      },
      statusRange: {
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

    showToPDF(base64, title) {
      const blob = this.base64ToBlob(base64, 'application/pdf');
      const url = URL.createObjectURL(blob);
      const pdfWindow = window.open("");
      pdfWindow.document.title = title;
      pdfWindow.document.write("<iframe width='100%' height='100%' style='position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;' src='" + url + "'></iframe>");
    },

    base64ToBlob(base64, type = "application/octet-stream") {
      const binStr = atob(base64);
      const len = binStr.length;
      const arr = new Uint8Array(len);
      for (let i = 0; i < len; i++) {
        arr[i] = binStr.charCodeAt(i);
      }
      return new Blob([arr], {type: type});
    },


    async fetchDataDepartmentLog(isPDF = false) {
      if (this.departmentLogRange !== null) {
        const response = await this.$axios.get(`reports/departmentlog?department_id=${this.selectedDepartmentId}&start=${this.departmentLogRange.from.replaceAll('/', '-')}&end=${this.departmentLogRange.to.replaceAll('/', '-')}&pdf=${isPDF}`)
        if (!isPDF) {
          return response.data.rows
        }
        this.showToPDF(response.data, "Department Wise Report")
      }

    },
    // async fetchComplainStatusLog() {
    //   const response = await this.$axios.get(`reports/complainstatus?department_id=${this.selectedDepartmentId}&start=${this.departmentLogRange.from.replaceAll('/', '-')}&end=${this.departmentLogRange.to.replaceAll('/', '-')}`)
    //   return response.data.rows
    // }

    async fetchDataTopicLog(isPDF = false) {
      if (this.popWiseTopicStatusRange !== null) {
        const response = await this.$axios.get(`reports/topicwisepop?department_id=${this.selectedDepartmentId}&start=${this.popWiseTopicStatusRange.from.replaceAll('/', '-')}&end=${this.popWiseTopicStatusRange.to.replaceAll('/', '-')}&pdf=${isPDF}`)
        if (!isPDF) {
          return response.data.rows
        }
        this.showToPDF(response.data, "Topic Wise Pop Report")
      }
    },

    async fetchDataServiceLog(isPDF = false) {
      if (this.serviceTimeRange !== null) {
        const response = await this.$axios.get(`reports/servicetime?department_id=${this.selectedDepartmentId}&start=${this.serviceTimeRange.from.replaceAll('/', '-')}&end=${this.serviceTimeRange.to.replaceAll('/', '-')}&pdf=${isPDF}`)
        if (!isPDF) {
          return response.data.rows
        }
        this.showToPDF(response.data, "Service Time Log Report")
      }

    },

    async fetchDataPopLog(isPDF = false) {
      if (this.popStatusRange !== null) {
        const response = await this.$axios.get(`reports/pop?department_id=${this.selectedDepartmentId}&start=${this.popStatusRange.from.replaceAll('/', '-')}&end=${this.popStatusRange.to.replaceAll('/', '-')}&pdf=${isPDF}`)
        if (!isPDF) {
          return response.data.rows
        }
        this.showToPDF(response.data, "Pop Log Report")
      }
    },

    async fetchHelpTopicLog(isPDF = false) {
      if (this.helpTopicRange !== null) {
        const response = await this.$axios.get(`reports/helptopic?department_id=${this.selectedDepartmentId}&start=${this.helpTopicRange.from.replaceAll('/', '-')}&end=${this.helpTopicRange.to.replaceAll('/', '-')}&pdf=${isPDF}`)
        if (!isPDF) {
          return response.data.rows
        }
        this.showToPDF(response.data, "Help Topic Report")
      }
    },

    async fetchStatusLog(isPDF = false) {
      if (this.statusRange !== null) {
        const response = await this.$axios.get(`reports/status?department_id=${this.selectedDepartmentId}&start=${this.statusRange.from.replaceAll('/', '-')}&end=${this.statusRange.to.replaceAll('/', '-')}&pdf=${isPDF}`)
        if (!isPDF) {
          return response.data.rows
        }
        this.showToPDF(response.data, "Status Report")
      }
    }
  }
}
</script>
