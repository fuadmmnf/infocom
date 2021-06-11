<template>
  <div>
    <q-table
      :title="title"
      :data="data"
      :columns="columns"
      row-key="id"
      :rows-per-page-options="[0]"
      :pagination.sync="pagination"
      hide-header
      hide-bottom
    />
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
      pagination: {
        page: 1,
        rowsPerPage: 0 // 0 means all rows
      },
      data: [],
      columns: [
        {name: 'name', align: 'center', label: 'Name', field: 'name', sortable: true},
      ]
    }
  },
  mounted() {
    this.fetchResource()
    this.$root.$on('resource-created', this.fetchResource)
  },
  methods: {
    fetchResource() {
      this.$axios.get(this.resource_url)
        .then((res) => {
          this.data = res.data
        })
    }
  }
}
</script>

<style scoped>

</style>
