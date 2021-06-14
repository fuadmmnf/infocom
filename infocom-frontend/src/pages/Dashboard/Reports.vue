<template>
  <q-page class="q-pa-md">
    <div class="row ">
      <q-date class="col-3 q-ma-sm" v-model="departmentLogRange" range color="secondary"
              subtitle="Department Activity Log">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="departmentLogRange.department_id"
                      :options="$store.getters.getDepartments" option-label="name"
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


      <q-date class="col-3 q-ma-sm" v-model="departmentLogRange" range color="indigo-10"
              subtitle="Complain Status Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="departmentLogRange.department_id"
                      :options="$store.getters.getDepartments" option-label="name"
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


      <q-date class="col-3 q-ma-sm" v-model="departmentLogRange" range color="indigo" subtitle="POP/Topic Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="departmentLogRange.department_id"
                      :options="$store.getters.getDepartments" option-label="name"
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

      <q-date class="col-3 q-ma-sm" v-model="departmentLogRange" range color="blue-grey" subtitle="Service Time Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="departmentLogRange.department_id"
                      :options="$store.getters.getDepartments" option-label="name"
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

      <q-date class="col-3 q-ma-sm" v-model="departmentLogRange" range color="purple-9" subtitle="POP Report">
        <template>
          <div class="flex flex-center items-center ">
            <q-select class="col q-pr-xs " dense filled v-model.number="departmentLogRange.department_id"
                      :options="$store.getters.getDepartments" option-label="name"
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
  }
}
</script>
