<template lang="pug">
    DivElement(classCss="form-item__main")
      DivElement(classCss="form-item__field")
        DivElement(classCss="form-item__support, support, w-100")
          DivElement(classCss="support__preview")
            SpanElement(classCss="btn, btn_secondary, w-100")
              SpanElement(classCss="btn__text") {{schemas.title}}
          DivElement(classCss="support__dropdown, dropdown")
            DivElement(classCss="support__list")
              DivElement(classCss="support__item" v-for="(input, index) in schemas.buttons" :key="index")
                LinkElement(classCss="support__link" :href="input.link")
                  DivElement(classCss="support__media")
                    SvgElement(:image="input.image")
                  DivElement(classCss="support__title, title")
                    SpanElement(classCss="title__text") {{input.name}}
</template>
<script>

import DivElement from '@/js/components/elements/Div'
import SpanElement from '@/js/components/elements/Span'
import LinkElement from '@/js/components/elements/Link'
import SvgElement from '@/js/components/elements/Svg'
import HttpClass from '@/js/classes/Http'


    export default {
        name: 'SupportBlock',
        data() {

            return {
                schemas: this.$store.state.schemas.support
            }

        },
        methods:{
          ...HttpClass,

          getSupportLink(){

            this.schemas.buttons.forEach(button => {
                let sendData = new FormData()
                sendData.append('name', button.name)
                this.sendRequest({
                  method: 'POST',
                  url: '/support-link',
                  data: sendData,
                  success: this.successResponse,
                  error: this.errorResponse
                })
              })
          },
          successResponse(result){
            if(result.data.status == true){
              this.schemas.buttons.forEach(button => {
                if(button.name == result.data.name){
                  button.link = result.data.link
                }
              })
            }
          },
          errorResponse(result){
            console.error(result)
          }
        },
        mounted(){
          this.getSupportLink()
        },
        components: {
          DivElement, SpanElement, LinkElement, SvgElement
        }


    }
</script>

<style lang="scss">
</style>