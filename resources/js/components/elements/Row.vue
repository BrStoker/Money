<template lang="pug">
.row(:class="className" :style="styleCss")
  slot
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'

  export default {
    name: 'RowElement',
    props:[ 'classCss', 'styleCss' ],
    computed:
    {
      ...ComputedElements
    }
  }
</script>

<style lang="scss">

</style>