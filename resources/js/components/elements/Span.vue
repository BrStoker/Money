<template lang="pug">
span(:class="className" :style="styleCss" @click="OnClick")
  slot
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'

  export default {
    name: 'SpanElement',
    props:[ 'classCss', 'styleCss', 'onclick'],
    computed:
    {
      ...ComputedElements
    },
    methods:{
      OnClick(e)
      {

        if(this.element != undefined)
        {

          if(typeof this.onclick == 'function')
          {
            this.onclick(e, this.element)
          }

        } else {

          if(typeof this.onclick == 'function')
          {
            this.onclick(e)
          }

        }

      }
    }
  }
</script>

<style lang="scss">

</style>