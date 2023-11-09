<template lang="pug">
textarea.textarea(:class="this.className" :name="this.name" :style="styleCss" :required="required" v-model="input.value") {{text}}
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'
  
  export default {
    name: 'TextareaElement',
    props:[ 'classCss', 'name', 'text', 'styleCss', 'required', 'input' ],
    computed:
      {
        ...ComputedElements
      },
    mounted(){

    }

  }
</script>

<style lang="scss">

</style>