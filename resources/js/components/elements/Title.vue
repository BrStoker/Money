<template lang="pug">
.title(:class="className" :style="styleCss")
  slot
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'

  export default {
    name: 'TitleElement',
    props:[ 'classCss', 'styleCss' ],
    computed:
    {
      ...ComputedElements
    }
  }
</script>

<style lang="scss">

</style>