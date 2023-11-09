<template lang="pug">
form.form(@submit="OnSubmit" :class="className" :action="action" :method="method" :enctype="enctype" :style="styleCss")
  slot
</template>

<script>
  
  import InputElement from '@/js/components/elements/Input'
  import ComputedElements from '@/js/computed/elements/Index'

  export default {
    name: 'FormElement',
    props:[ 'onsubmit', 'onreset', 'classCss', 'action', 'method', 'enctype', 'styleCss' ],
    methods:{
      OnSubmit(e)
      {

        if(typeof this.onsubmit == 'function')
        {
          let form = e.target

          if(form.tagName == 'FORM')
          {
            this.onsubmit(e)
          }

        }

      }
    },
    computed:
    {
        ...ComputedElements
    },
    components:{
      InputElement
    }
  }
  
</script>

<style lang="scss">

</style>