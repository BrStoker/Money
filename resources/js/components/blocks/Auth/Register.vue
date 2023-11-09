<template lang="pug">
DivElement(classCss="formular, mb-0")
  DivElement(classCss="formular__main" v-if="step == 1")
    StepOne
  DivElement(classCss="formular__main" v-if="step == 2")
    StepTwo
</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import StepOne from '@/js/components/blocks/Auth/RegisterStepOne'
  import StepTwo from '@/js/components/blocks/Auth/RegisterStepTwo'

  import HttpClass from '@/js/classes/Http'
  import EventsLoginform from '@/js/events/blocks/LoginForm'



  export default{
    props: [ ],
    components:{
      DivElement,
      StepOne,
      StepTwo
    },
    computed: {
      step(){
        return this.$store.state.data.header.blocks.register.step
      }
    },
    methods:{
      ...HttpClass,
      ...EventsLoginform,

    },
    mounted(){
    }

  }


</script>

<style lang="scss" scoped>
</style>