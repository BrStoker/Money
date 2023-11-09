<template lang="pug">
EmptyElement
  DivElement(classCss="modal__return, return")
    DivElement(classCss="return__media")
      SvgElement(:image="schema.images.arrowLeft")
    DivElement(classCss="return__title, title, modal-init" :onclick="changeStep")
      SpanElement(classCss="title__text") {{schema.text.back}}
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
                            DivElement(classCss="form-item")
                              InputElement(:data="input" :onchange="handleClick")
                    InputElement(v-for="(input, subIndex) in step.inputs" :key="subIndex" :data="input" v-else)
    DivElement(classCss="wysiwyg, text_center")
      p
        LinkElement(v-if="countdown > 0") {{'Запросить новый код можно через '}} {{countdown}} {{' с'}}
        LinkElement(v-if="countdown == 0" href="#" :onclick="getNewCode") {{schema.text.textLink}}

</template>

<script>

  import EmptyElement from "@/js/components/elements/Empty";
  import DivElement from '@/js/components/elements/Div'
  import RowElement from '@/js/components/elements/Row'
  import FormElement from '@/js/components/elements/Form'
  import FormInput from '@/js/components/elements/FormInput'
  import LabelElement from '@/js/components/elements/Label'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'
  import ButtonElement from '@/js/components/elements/Button'
  import LinkElement from '@/js/components/elements/Link'
  import InputElement from '@/js/components/elements/Input'

  import SupportBlock from '@/js/components/blocks/Support'

  import HttpClass from '@/js/classes/Http'
  import EventsLoginform from '@/js/events/blocks/LoginForm'


  export default{
    props: [ ],
    data() {
      return{
        countdown: 30,
        success: '',
        errors: '',
        validate: true,
        schema: this.$store.state.schemas.header.blocks.register.steps.StepTwo
      }
    },
    components:{
      InputElement,
      EmptyElement,
      DivElement, RowElement, FormElement, FormInput, LabelElement, SvgElement, SpanElement, ButtonElement, LinkElement,
      SupportBlock

    },
    methods:{
      ...HttpClass,
      ...EventsLoginform,
      ...{
        
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
        changeStep(){
          this.$store.commit('reloadStep')
        },
        handleClick(e){
          console.log(e)
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

        }

      }
    },
    computed:{

    },
    mounted(){
      this.startCountdown()

      this.$el.addEventListener('input', this.handleClick);

    },
    beforeDestroy() {
      // При уничтожении компонента удаляем прослушиватель события
      this.$el.removeEventListener('input', this.handleClick);
    }

  }


</script>

<style lang="scss" scoped>
</style>