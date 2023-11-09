<template lang="pug">
DivElement(classCss="form-item__main")
  DivElement(classCss="form-item__field")
    button(@click="Onclick" type="button" class="error btn btn_tertiary")
      SpanElement(classCss="btn__text, error") {{'Удалить страницу'}}

</template>

<script>
  
  import ComputedElements from '@/js/computed/elements/Index'
  import DivElement from '@/js/components/elements/Div'
  import SpanElement from '@/js/components/elements/Span'
  import HttpClass from '@/js/classes/Http'

  export default {
    name: 'ButtonElement',
    props:[ 'data','classCss', 'type', 'name', 'value', 'placeholder', 'styleCss', 'onclick' ],
    computed:
    {
      ...ComputedElements
    },
    components:{
      DivElement, SpanElement
    },
    methods:{
      ...HttpClass,
      Onclick(e){
        let sendData = new FormData()
        this.sendRequest({
          method: 'POST',
          url: '/user/delete',
          data: sendData,
          success: this.deleteSuccess,
          error: this.deleteError
        })

      },
      deleteSuccess(result){
        if(result.data.code == 0){
          if(result.data.redirect){
            window.location = result.data.redirect
          }
        }
      },
      deleteError(result){
        console.error(result)
      }
    }
  }

</script>

<style lang="scss">

</style>