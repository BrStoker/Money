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
                  DivElement(classCss="col, col_12" v-for="(input, index) in schema.inputs" :key="index")
                    DivElement(:classCss="input.class")
                      DivElement(classCss="form-item__main")
                        DivElement(:classCss="input.classChild")
                          InputElement(classCss="form-item__input" :class="'error', errors" :type="input.typeInput" :input="input" :value="input.value" :name="input.name" :placeholder="input.placeholder" v-if="input.type == 'input'")
                          SpanElement(classCss="error" v-if="errors") {{errors}}
                          ButtonElement(classCss="btn, w-100" :type="input.typeInput" :disabled="disabled" v-if="input.type == 'button'")
                            SpanElement(classCss="btn__text") {{input.placeholder}}
</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import SvgElement from '@/js/components/elements/Svg'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import RowElement from '@/js/components/elements/Row'
  import Form from '@/js/components/elements/Form'
  import InputElement from '@/js/components/elements/Input'
  import ButtonElement from '@/js/components/elements/Button'

  import HttpClass from '@/js/classes/Http'

  export default{

    name: 'ConfirmPasswordForm',

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
      ...{
        GetCode(e){
          e.preventDefault()
          this.errors = ''
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
        },
        CodeSuccess(result)
        {
          if (result.data.code !== undefined && result.data.code == 0) {
              console.log(result.data.code)
              this.showConfirmForm()
            }
          else{
            if(result.data.desc != undefined && result.data.desc.length)
            {
               this.errors = result.data.desc
            }
          }
        },
        CodeError(result) {
          console.error(result)
        },
        closeForm(){
          this.$store.commit('showForgotForm')
        },
        showLoginForm(){
          this.$store.commit('closeForgotShowLogin')
        },
        showConfirmForm(){
          this.$store.commit('showConfirmFromForgot')
        }

      },
    },
    components:{
      DivElement,
      LinkElement,
      SpanElement,
      SvgElement,
      RowElement,
      Form,
      InputElement,
      ButtonElement
    },
  }


</script>

<style lang="scss" scoped>
</style>