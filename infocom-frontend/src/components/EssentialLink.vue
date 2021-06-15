<template>
  <q-item
    v-if="permission === null || $store.getters[permission]"
    clickable
    :to="link"
  >
    <q-item-section
      v-if="icon"
      avatar
    >
      <q-icon :name="icon" />
    </q-item-section>

    <q-item-section>
      <q-item-label class="text-body1">{{ title }}</q-item-label>
      <q-item-label caption>
        {{ caption }}
      </q-item-label>
    </q-item-section>
  </q-item>
</template>

<script>
export default {
  name: 'EssentialLink',
  props: {
    title: {
      type: String,
      required: true
    },
    caption: {
      type: String,
      default: ''
    },
    link: {
      type: Object,
      required: true
    },
    icon: {
      type: String,
      default: ''
    },
    permission: {
      type: String,
      default: () => {return null}
    }
  }
}
</script>
