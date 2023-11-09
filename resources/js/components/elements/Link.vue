<template lang="pug">
a.link(@click="OnClick" :href="href" :title="title" :class="className" :target="target" :style="styleCss")
  slot
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'

  export default {
    name: 'LinkElement',
    props:[ 'href', 'title', 'classCss', 'target', 'styleCss', 'onclick', 'element' ],
    computed:
    {
      ...ComputedElements,
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