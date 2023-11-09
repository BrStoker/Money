<template lang="pug">
  DivElement(classCss="modal__layout, modal__layout_tiny, modal__layout_сomplaint-folder" :class="classIsComplainShown()")
    NotificationPopup(v-if="notificationMessage" :message="notificationMessage")
    DivElement(classCss="modal__action, action" :onclick="closeForm")
      SvgElement(:image="close")
    DivElement(classCss="modal__main")
      DivElement(classCss="wysiwyg")
        h3 {{title}}
      DivElement(classCss="formular")
        DivElement(classCss="formular__main")
          Form(:onsubmit="addComplain")
            fieldset
              DivElement(classCss="form__group, group, form__group_second" v-for="(step, index) in steps" :key="index")
                DivElement(classCss="group__header" v-if="step.title")
                  DivElement(classCss="wysiwyg")
                    h6 {{step.title}}
                DivElement(classCss="group__main")
                  RowElement
                    DivElement(classCss="col, col_12" v-for="(input, inputIndex) in step.inputs" :key="inputIndex")
                      InputElement(:data="input" :onclick="input.onclick")
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

  import AppMethods from '@/js/methods/App'
  import HttpClass from '@/js/classes/Http'
  import MethodsComplain from '@/js/methods/blocks/ComplainForm'
  import CustomCheckbox from '@/js/components/elements/Custom/Checkbox'
  import ComputedComplain from '@/js/computed/blocks/ComplainForm'

  export default{
    data(){
      return{

        schema: this.$store.state.schemas.complainForm,

        article: this.$store.state.data.app.articles,
        title: 'Пожаловаться на статью',
        close: '/image/svg/sprite.svg#close',
        notificationMessage: '',
        steps: [
          {
            title: 'Выберите причину жалобы',
            inputs: [
              {
                name: 'reason',
                inputType: 'option',
                class: 'col, col_12',
                options: [
                  {
                    value: '1',
                    label: 'Спам'
                  },
                  {
                    value: '2',
                    label: 'Угрозы'
                  },
                  {
                    value: '3',
                    label: 'Фейковый аккаунт'
                  },
                ],

              }
            ]
          },
          {
            inputs:[
              {
                name: '',
                inputType: 'submit',
                placeholder: 'Пожаловаться',
                type: 'submit',
                parentClass: 'form-item',
                class: 'btn w-100',
                onclick: ()=>this.addComplain(e)
              },
              {
                name: '',
                inputType: 'button',
                placeholder: 'Отменить',
                parentClass: 'form-item',
                type: 'button',
                class: 'btn btn_tertiary w-100',
                onclick: ()=> this.closeForm()
              },
            ]
          }
        ]


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
      NotificationPopup

    },
    methods:{
      ...HttpClass,
      ...AppMethods,
      ...MethodsComplain,
      ...ComputedComplain,
      addComplain(e){
        e.preventDefault()
        let sendData = new FormData(e.target)
        sendData.append('article_id', this.$store.state.data.app.articles[0].id)
        this.sendRequest({
          method: 'POST',
          url: '/complain-user',
          data: sendData,
          success: this.complainSuccess,
          error: this.complainError
        })
      },

      complainSuccess(result){
        if(result.data.code == 0){
          this.closeForm()
          this.notificationMessage = result.data.message
        }else{
          this.closeForm()
          this.notificationMessage = result.data.message
        }
      },
      complainError(result){
        this.closeForm()
        this.notificationMessage = result
      }
    },
    computed: {

    },
    mounted(){

    }

  }


</script>

<style lang="scss" scoped>
</style>