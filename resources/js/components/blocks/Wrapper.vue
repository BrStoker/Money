<template lang="pug">
  DivElement(classCss="layout__middle")
    slot
</template>

<script>

  import DivElement from '@/js/components/elements/Div'

  export default {
    name: 'WrapperBlock',
    components: {
      DivElement
    }
  }
  
</script>

<style lang="scss"></style>