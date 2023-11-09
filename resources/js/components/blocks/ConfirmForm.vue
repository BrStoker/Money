<template lang="pug">
DivElement(:classCss="'modal__layout, modal__layout_tiny, modal__layout_code' + (data.app.isConfirmShown?', modal__layout_active':'')")
  DivElement(classCss="modal__action, action")
    SvgElement(:image="schema.images.close" :onclick="closeForm")
  DivElement(classCss="modal__return, return")
    DivElement(classCss="return__media")
      SvgElement(:image="schema.images.arrowLeft")
    DivElement(classCss="return__title, title, modal-init" :onclick="showForgotForm")
      SpanElement(classCss="title__text") {{schema.header.textBack}}
  DivElement(classCss="modal__main")
    DivElement(classCss="wysiwyg")
      h3 {{schema.header.title}}
      p {{schema.header.text}}
    DivElement(classCss="formular, mb-0")
      DivElement(classCss="formular__main")
        Form(:onsubmit="ChangePassword" :action="schema.action" :method="schema.method")
          fieldset
            DivElement(classCss="form__group, group")
              DivElement(classCss="group__main")
                RowElement
                  DivElement(classCss="col, col_12")
                    RowElement
                      DivElement(classCss="col, col_9")
                        RowElement
                          DivElement(classCss="col, col_3" v-for="(input, index) in schema.inputs" :key="index")
                            DivElement(classCss="form-item")
                              DivElement(classCss="form-item__main")
                                DivElement(classCss="form-item__field")
                                  InputElement(classCss="form-item__input, form-item__input_second" :type="input.type" :data="input")
                  DivElement(classCss="col, col_12")
                    DivElement(classCss="form-item")
                      DivElement(classCss="form-item__main")
                        DivElement(classCss="form-item__field, modal-init")
                          ButtonElement(classCss="btn, w-100" :type="schema.button.type" :onclick="ChangePassword")
                            SpanElement(classCss="btn__text") {{schema.button.buttonText}}
      DivElement(classCss="wysiwyg, text_center")
        p
          LinkElement(v-if="countdown > 0") {{'Запросить новый код можно через '}} {{countdown}} {{' с'}}
          LinkElement(v-if="countdown == 0" :onclick="getCode") {{schema.header.textLink}}

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

    name: 'ConfirmFormBlock',

    data() {

      let data = this.$store.state.data,
        schema = this.$store.state.schemas.confirm

      return {
        countdown: 30,
        intervalId: null,
        LinkActive: false,
        disabled: false,
        errors: '',
        data: data,
        schema: schema
      }

    },
    methods:{
      ...HttpClass,
      ...{
        startCountdown() {
          this.intervalId = setInterval(() => {
            if (this.countdown > 0) {
              this.countdown--;
            } else {
              clearInterval(this.intervalId);
            }
          }, 1000);
        },
        ChangePassword(e){
          e.preventDefault()
          this.errors = ''
          let formData = this.SchemasToFormData(this.schema, e.target)
          for (const [key, value] of formData.entries()) {
            data[key] = value;
          }
        },
        CodeSuccess(result)
        {
          if (result.data.code != undefined && result.data.code == 0)
          {
            if (result.data.location)
            {
              location.href = result.data.location
            }
            else
            {
              if(result.data.desc != undefined && result.data.desc.length)
              {
                this.success = result.data.desc
              }
            }
          }
          else
          {
            if(result.data.desc != undefined && result.data.desc.length)
            {
              this.LoginErrors = result.data.desc
            }
          }
        },
        CodeError(result) {
          console.error(result)
        },
        getCode(){
          this.countdown = 30
          this.startCountdown()
        },
        closeForm(){
          this.$store.commit('closeConfirmForm')
        },
        showForgotForm(){
          this.$store.commit('closeConfirmShowForgot')
        },
      },
    },
    mounted(){
      this.startCountdown()
    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement, RowElement, Form, InputElement, ButtonElement
    },
  }


</script>

<style lang="scss" scoped>
</style>