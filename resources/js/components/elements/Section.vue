<template lang="pug">
section(:class="className" :style="styleCss")
  slot
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'

  export default {
    name: 'SectionElement',
    props:[ 'classCss', 'styleCss'],
    computed:
    {
      ...ComputedElements
    },
    methods:{

    }
  }
</script>

<style lang="scss">

</style>