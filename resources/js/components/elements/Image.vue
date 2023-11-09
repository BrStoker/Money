<template lang="pug">
img.image(@click="onClick" :src="src" :alt="alt" :class="className" :style="styleCss")
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'

  export default {
    name: 'ImageElement',
    props:[ 'classCss', 'alt', 'src', 'styleCss', 'onclick' ],
    methods:
    {
      onClick(e)
      {
        if(typeof this.onclick == 'function' )
        {
          this.onclick(e)
        }
      }
    },
    computed:
    {
      ...ComputedElements
    }
  }
  
</script>

<style lang="scss">
</style>