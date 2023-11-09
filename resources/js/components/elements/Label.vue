<template lang="pug">
label.label(:for="forHtml" :class="className" :style="styleCss")
    slot
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'
  
  export default {
    name: 'LabelElement',
    props:[ 'classCss', 'forHtml', 'styleCss' ],
    computed:
    {
      ...ComputedElements
    }
  }
</script>

<style lang="scss">
</style>