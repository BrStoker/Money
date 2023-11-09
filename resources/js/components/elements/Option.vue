<template lang="pug">
option(:value="value" :class="className" :selected="selected" :disabled="disabled" :placeholder="placeholder" :style="styleCss" :data-custom-properties="getDataAttrs")
  slot
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'
  
  export default {
    name: 'OptionElement',
    props:[ 'classCss', 'value', 'selected', 'disabled', 'styleCss', 'placeholder', 'dataAttrs' ],
    computed:
    {
      ...ComputedElements,
      ...{
        getDataAttrs() {

          if(this.dataAttrs != undefined && Object.keys(this.dataAttrs).length > 0) {

            return JSON.stringify(this.dataAttrs)

          }
        },
      }
    }
  }
  
</script>

<style lang="scss">

</style>