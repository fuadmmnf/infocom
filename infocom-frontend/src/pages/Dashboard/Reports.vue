<template>
  <q-page class="q-pa-md">
    <div class="row ">
      <q-date class="col-3 q-ma-sm" v-model="departmentLogRange" range color="secondary"
              subtitle="Department Activity Log">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="departmentLogRange.department_id"
                      :options="departments" option-label="name"
                      option-value="id" emit-value
                      map-options label="Department"
            />
            <download-excel class="col-2 q-mr-sm">
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
            <q-select class="col q-pr-xs " dense filled v-model.number="complainStatusRange.department_id"
                      :options="departments" option-label="name"
                      option-value="id" emit-value
                      map-options label="Department"
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
            <q-select class="col q-pr-xs " dense filled v-model.number="topicStatusRange.department_id"
                      :options="departments" option-label="name"
                      option-value="id" emit-value
                      map-options label="Department"
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
            <q-select class="col q-pr-xs " dense filled v-model.number="serviceTimeRange.department_id"
                      :options="departments" option-label="name"
                      option-value="id" emit-value
                      map-options label="Department"
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
            <q-select class="col q-pr-xs " dense filled v-model.number="popStatusRange.department_id"
                      :options="departments" option-label="name"
                      option-value="id" emit-value
                      map-options label="Department"
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
      departments: [
        { id: '', name: 'all depts' },
        ...this.$store.getters.getDepartments
      ],
      departmentLogRange: {
        department_id: '',
        to: '',
        from: ''
      },
      complainStatusRange: {
        department_id: '',
        to: '',
        from: ''
      },
      topicStatusRange: {
        department_id: '',
        to: '',
        from: ''
      },
      serviceTimeRange: {
        department_id: '',
        to: '',
        from: ''
      },
      popStatusRange: {
        department_id: '',
        to: '',
        from: ''
      },
    }
  }
}
</script>
