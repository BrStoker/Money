<template lang="pug">
.toster-wrapper(v-if="data != undefined && data.length" :class="className")
  TostElement(v-for="(tost, tostKey) in data" :key="tostKey" :data="tost" :error="error")
</template>

<script>

  import TostElement from '@/js/components/elements/Tost'

  export default {
    name: 'TosterBlock',
    props: ['error', 'data'],
    computed:{
      className()
      {
        return (this.error != undefined && parseInt(this.error) == 1 ? 'toster-wrapper__type_error': '')
      }
    },
    components: {
      TostElement
    }
  }
</script>

<style lang="scss">
</style>