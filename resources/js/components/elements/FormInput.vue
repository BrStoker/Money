<template lang="pug">
EmptyElement
  EmptyElement(v-for="(input, index) in data.inputs" :key="index")
    InputElement(:data="input" :step="data" :onclick="onclick" :onchange="onchange")

</template>

<script>

  import EmptyElement from '@/js/components/elements/Empty'
  import DivElement from '@/js/components/elements/Div'
  import InputElement from '@/js/components/elements/Input'
  import ComputedElements from '@/js/computed/elements/Index'
  import MethodsElements from '@/js/methods/elements/Input'
  import EventsInput from '@/js/events/elements/Input'



  export default {
    name: 'FormInputElement',
    props: ['data', 'onclick', 'onchange'],


	
    components:{
      EmptyElement,
      DivElement,
      InputElement
    },
    computed:
    {
      ...ComputedElements
    },
    methods: {
      ...MethodsElements,
      ...EventsInput,
      togglePasswordVisibility() {
        this.show = !this.show;
      },
      onInputChange(event) {
        if (this.element !== undefined) {
          if (typeof this.onchange === 'function') {
            this.onchange(event, this.element);
          }
        } else {
          if (typeof this.onchange === 'function') {
            this.onchange(event);
          }
        }
      }
    },
    created(){

    },
    mounted(){


    }
  }
</script>

<style lang="scss">
</style>