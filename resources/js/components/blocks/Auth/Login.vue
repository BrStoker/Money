<template lang="pug">
    DivElement(classCss="formular, mb-0")
      DivElement(classCss="formular__main")
        Form(:onsubmit="Login" :action="schema.url" :method="schema.method")
          fieldset
            DivElement(classCss="form__group, group")
              DivElement(classCss="group__main")
                RowElement
                  DivElement(v-for="(step, index) in schema.steps" :key="index" :classCss="step.class")
                    DivElement(:classCss="step.classChild")
                      FormInput(:data="step" :onclick="showForgotForm")
</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import Form from '@/js/components/elements/Form'
  import RowElement from '@/js/components/elements/Row'
  import InputElement from '@/js/components/elements/Input'
  import LabelElement from '@/js/components/elements/Label'
  import SvgElement from '@/js/components/elements/Svg'
  import ButtonElement from '@/js/components/elements/Button'
  import SupportBlock from '@/js/components/blocks/Support'
  import EmptyElement from '@/js/components/elements/Empty'
  import FormInput from '@/js/components/elements/FormInput'

  import Utils from '@/js/classes/Util'
  import HttpClass from '@/js/classes/Http'
  import AppMethods from '@/js/methods/App'

  export default{
    data() {
      return{
        success: [ ],
        errors: [],
        data: this.$store.state.data,
        schema: this.$store.state.schemas.header.blocks.login
      }
    },
    components:{
      DivElement,
      LinkElement,
      SpanElement,
      Form,
      RowElement,
      InputElement,
      LabelElement,
      SvgElement,
      ButtonElement,
      SupportBlock,
      EmptyElement,
      FormInput

    },
    methods:{
      ...AppMethods,
      ...HttpClass,
      ...Utils,
      ...{
        Login(e){

          e.preventDefault()
          this.errors = ''
          this.success = ''
          this.removeErrors()
          this.sendRequest({
            method: this.schema.method,
            url: this.schema.action,
            data: this.SchemasToFormData(this.schema),
            success: this.LoginSuccess,
            error: this.LoginError
          })
        },
        LoginSuccess(result)
        {
          console.log(result)
          if (result.data.code !== undefined && result.data.code == 0)
          {
            if (result.data.location)
            {
              location.href = result.data.location
            }
            else
            {
              if(result.data.desc !== undefined && result.data.desc.length)
              {
                this.success = result.data.desc
              }
            }
          }
          else
          {
              this.appendErrorsToSchema(this.schema, result.data.desc)
          }
        },
        LoginError(result) {
          console.error(result)
        },
        showLoginForm(){
          this.$store.commit('closeModalLogin')
        },
        showForgotForm(){
          this.$store.commit('closeLoginShowForgot')
        },
        removeErrors(){
          this.schema.steps.forEach(step =>{
            if(step.inputs){
              step.inputs.forEach(input=>{

                if(input.error){

                  input.error = ''

                }


              })

            }

          })

        }
      }

    },


  }


</script>

<style lang="scss">
</style>