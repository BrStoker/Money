<template lang="pug">
button(@click="Onclick" :type="type" :name="name" :value="value" :placeholder="placeholder" :class="className" :style="styleCss")
  slot
</template>

<script>
  
  import ComputedElements from '@/js/computed/elements/Index'

  export default {
    name: 'ButtonElement',
    props:[ 'classCss', 'type', 'name', 'value', 'placeholder', 'styleCss', 'onclick' ],
    computed:
    {
      ...ComputedElements
    },
    methods:{
      Onclick(e){

        if(typeof(this.onclick) == 'function') {

          this.onclick(e)
          
        }

      }
    }
  }

</script>

<style lang="scss">

</style>