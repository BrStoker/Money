<template lang="pug">
DivElement(:classCss="'modal__layout, modal__layout_tiny, modal__layout_code' + (data.app.isConfirmPasswordShown?', modal__layout_active':'')")
  DivElement(classCss="modal__action, action" :onclick="closeForm")
    SvgElement(:image="images.close")
  DivElement(classCss="modal__return, return" :onclick="showForgotForm")
    DivElement(classCss="return__media")
      SvgElement(:image="images.arrowLeft")
    DivElement(classCss="return__title, title, modal-init")
      SpanElement(classCss="title__text") {{'Назад'}}
  DivElement(classCss="modal__main")
    DivElement(classCss="wysiwyg")
      h3 {{schema.text.title}}
      p {{schema.text.description}}
    DivElement(classCss="formular, mb-0")
      DivElement(classCss="formular__main")
        FormElement(:onsubmit="confirmReg" :method="schema.method")
          fieldset
            DivElement(classCss="form__group, group")
              DivElement(classCss="group__main")
                RowElement
                  DivElement(classCss="col, col_12" v-for="(step, index) in schema.steps" :key="index")
                    RowElement(v-if="step.inputs.length > 1")
                      DivElement(classCss="col, col_9")
                        RowElement
                          DivElement(v-for="(input, subIndex) in step.inputs" :key="subIndex" :classCss="input.classParent")
                            InputElement(:data="input" :onchange="handleClick")
                    InputElement(v-for="(input, subIndex) in step.inputs" :key="subIndex" :data="input" v-else)
    DivElement(classCss="wysiwyg, text_center")
      p
        LinkElement(v-if="countdown > 0") {{'Запросить новый код можно через '}} {{countdown}} {{' с'}}
        LinkElement(v-if="countdown == 0" href="#" :onclick="getNewCode") {{schema.text.textLink}}

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'
  import FormElement from '@/js/components/elements/Form'
  import RowElement from '@/js/components/elements/Row'
  import InputElement from '@/js/components/elements/Input'
  import Form from '@/js/components/elements/Form'
  import ButtonElement from '@/js/components/elements/Button'
  import NotificationPopup from '@/js/components/elements/Notification'
  import LinkElement from '@/js/components/elements/Link'

  import AppMethods from '@/js/methods/App'
  import HttpClass from '@/js/classes/Http'
  import MethodsConfirm from '@/js/methods/blocks/ConfirmDeleteArticle'
  import CustomCheckbox from '@/js/components/elements/Custom/Checkbox'
  import ComputedConfirm from '@/js/computed/blocks/ConfirmDeleteArticle'

  export default{
    name: 'ConfirmDeleteModal',

    data(){
      let data = this.$store.state.data
      return{
        countdown: 30,
        schema: this.$store.state.schemas.header.blocks.register.steps.StepTwo,
        data: data,
        images: {
          close: '/image/svg/sprite.svg#close',
          arrowLeft: '/image/svg/sprite.svg#arrowLeft',
        }

      }
    },
    components:{
      DivElement,
      SvgElement,
      SpanElement,
      FormElement,
      Form,
      RowElement,
      InputElement,
      ButtonElement,
      CustomCheckbox,
      NotificationPopup,
      LinkElement

    },
    methods: {
      ...HttpClass,
      ...AppMethods,
      ...MethodsConfirm,
      ...ComputedConfirm,
      startCountdown() {
        this.countdown = setInterval(() => {
          if (this.countdown > 0) {
            this.countdown--
          }
        }, 1000);
      },
      clearInterval(){
        this.countdown = 30;
      },
      confirmReg(e){
        e.preventDefault()
        let userCode = this.confirmCodeToInt(e.target)
        let sendData = new FormData()
        sendData.append('code', userCode)
        sendData.append('id', this.getUserId())
        this.sendRequest({
          method: 'POST',
          url: "/register/confirm",
          data: sendData,
          success: this.confirmSuccess,
          error: this.handelErrorResponse
        })
      },
      confirmSuccess(result){
        if(result.data.code === 0 && result.data.location){
          window.location.href = result.data.location
        }
        else{
          if(result.data.code){
            this.errors = 'Указанный код неверен'
          }
        }
      },
      handelErrorResponse(result) {
        console.error('RegisterError -> ', result)
      },
      getNewCode(event){
        event.preventDefault()
        let sendData = new FormData()
        this.sendRequest({
          method: 'GET',
          url: "/getcode",
          data: sendData,
          success: this.CodeSuccess,
          error: this.handelErrorResponse
        })
      },
      CodeSuccess(result){
        console.log(result)
        if(result.data.code == 0){
          this.errors = ''
          this.success = result.data.desc
          this.clearInterval()
          this.startCountdown()
        }
      },

      confirmCodeToInt(form){
        let code = ''
        if(form != null && form.tagName === 'FORM'){
          let inputsForm = form.querySelectorAll('input')
          inputsForm.forEach(input => {
            if(input.name != '_token'){
              code = code + input.value
            }
          })
        }
        return code

      },
      getUserId(){
        return this.$store.state.data.header.blocks.register.userID
      },

      handleClick(e){
        if(e.target.value.length > 1){
          e.target.value = e.target.value.substring(0, 1)
        }
        let input = ''
        if(e.target.name == 'input_1'){
          if (e.target.value){
            input = document.querySelector(`input[name="input_2"]`)
            input.focus()
          }
        }else if(e.target.name == 'input_2'){
          if(e.target.value){
            input = document.querySelector(`input[name="input_3"]`)
            input.focus()
          }
        }else if(e.target.name == 'input_3'){
          if(e.target.value) {
            input = document.querySelector(`input[name="input_4"]`)
            input.focus()
          }
        }

      },
      closeForm(){
        this.$store.commit('closeConfirmPassword')
      },
      showForgotForm(){
        this.$store.commit('closeConfirmPasswordFromForgot')
      }
    },
    computed: {

    },
    mounted(){
      this.startCountdown()
    }

  }


</script>

<style lang="scss" scoped>
</style>