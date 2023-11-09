<template lang="pug">
DivElement(:classCss="'modal__layout, modal__layout_tiny, modal__layout_recovery' + (data.app.isForgotShown?', modal__layout_active':'')")
  DivElement(classCss="modal__action, action")
    SvgElement(:image="schema.images.close" :onclick="closeForm")
  DivElement(classCss="modal__return, return")
    DivElement(classCss="return__media")
      SvgElement(:image="schema.images.arrowLeft")
    DivElement(classCss="return__title, title, modal-init" :onclick="showLoginForm")
      SpanElement(classCss="title__text") {{schema.header.textBack}}
  DivElement(classCss="modal__main")
    DivElement(classCss="wysiwyg")
      h3 {{schema.header.title}}
      p {{schema.header.text}}
    DivElement(classCss="formular, mb-0")
      DivElement(classCss="formular__main")
        Form(:onsubmit="GetCode" :action="schema.action" :method="schema.method")
          fieldset
            DivElement(classCss="form__group, group")
              DivElement(classCss="group__main")
                RowElement
                  DivElement(v-for="(step, index) in schema.steps" :key="index" :classCss="step.class")
                    DivElement(:classCss="step.classChild")
                      FormInput(:data="step")
                  //DivElement(classCss="col, col_12" v-for="(input, index) in schema.inputs" :key="index")
                  //  DivElement(classCss="form-item__field")
                  //    InputElement(:data="input")
</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import SvgElement from '@/js/components/elements/Svg'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import RowElement from '@/js/components/elements/Row'
  import Form from '@/js/components/elements/Form'
  import FormInput from '@/js/components/elements/FormInput'
  import ButtonElement from '@/js/components/elements/Button'


  import HttpClass from '@/js/classes/Http'
  import MethodsForgot from '@/js/methods/blocks/Auth/Forgot'
  import Util from '@/js/classes/Util'

  export default{

    name: 'ForgotFormBlock',

    data() {

      let data = this.$store.state.data,
        schema = this.$store.state.schemas.forgot

      return {

        disabled: false,
        errors: '',
        data: data,
        schema: schema
      }

    },
    methods:{
      ...HttpClass,
      ...MethodsForgot,
      ...Util,
      ...{
        GetCode(e){
          e.preventDefault()
          this.errors = ''


          // let error = this.validateForm()

          // if(error == false){
            let formData = this.SchemasToFormData(this.schema)
            if (formData != null){
              this.sendRequest({
                method: this.schema.method,
                url: this.schema.action,
                data: formData,
                success: this.CodeSuccess,
                error: this.CodeError
              })
            }
          // }
        },
        CodeSuccess(result)
        {

          if (result.data.code !== undefined && result.data.code == 0) {
              this.showConfirmFormFromForgot()
            }
          else{
            if(result.data.desc !== undefined)
            {

              this.appendErrorsToSchema(this.schema, result.data.desc)
            }
          }
        },
        CodeError(result) {
          console.error(result)
        },
        // validateForm(){
        //   let error = false
        //   this.schema.inputs.forEach(input => {
        //     if(input.validate){
        //       if(input.error){
        //         input.error = ''
        //         error = false
        //       }
        //       if(input.value.length == 0){
        //         input.error = 'Обязательное поле'
        //         error = true
        //       }
        //     }
        //   })
        //
        //   return error
        //
        // },
        showConfirmFormFromForgot(){
          this.$store.commit('closeConfirmPasswordFromForgot')
        }

      },
    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement, RowElement, Form, FormInput, ButtonElement
    },
  }


</script>

<style lang="scss" scoped>
</style>