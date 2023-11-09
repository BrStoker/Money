<template lang="pug">
  DivElement(classCss="layout__overflow")
    slot
</template>

<script>

  import DivElement from '@/js/components/elements/Div'

  export default {

    name: 'ContentBlock',
    components: {
      DivElement
    },
    data()
    {
      return{

      }
    },
  }

</script>

<style lang="scss">
</style>