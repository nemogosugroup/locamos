<template>
  <i :class="['el-icon', icon]" />
  <span :v-slot="title">{{ title }}</span>
</template>
<script>
export default {
  name: 'MenuItem',
  functional: true,
  props: {
    icon: {
      type: String,
      default: ''
    },
    title: {
      type: String,
      default: ''
    }
  },
}
</script>

<style scoped>
</style>
