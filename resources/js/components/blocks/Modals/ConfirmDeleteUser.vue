<template lang="pug">
  DivElement(classCss="modal__layout, modal__layout_tiny, modal__layout_confirm-delete-article" :class="classIsConfirmDeleteUserShown()")
    DivElement(classCss="modal__action, action" :onclick="closeForm")
      SvgElement(:image="close")
    DivElement(classCss="modal__main")
      DivElement(classCss="wysiwyg")
        h3 {{'Удалить статью?'}}
      DivElement(classCss="formular")
        DivElement(classCss="formular__main")
          Form(:onsubmit="deleteArticle")
            fieldset
              DivElement(classCss="form__group, group, form__group_second" v-for="(step, index) in steps" :key="index")
                DivElement(classCss="group__main")
                  RowElement
                    DivElement(classCss="col, col_6" v-for="(input, inputIndex) in step.inputs" :key="inputIndex")
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
  import MethodsConfirm from '@/js/methods/blocks/ConfirmDeleteArticle'
  import CustomCheckbox from '@/js/components/elements/Custom/Checkbox'
  import ComputedConfirmDeleteUser from '@/js/computed/blocks/ConfirmDeleteUser'

  export default{
    name: 'ConfirmDeleteUserModal',
    data(){
      return{

        schema: this.$store.state.schemas.confirmDelete,

        article: this.$store.state.data.app.articles,
        title: 'Пожаловаться на статью',
        close: '/image/svg/sprite.svg#close',
        notificationMessage: '',
        steps: [
          {
            inputs:[
              {
                name: '',
                inputType: 'submit',
                placeholder: 'Подтвердить',
                type: 'submit',
                parentClass: 'form-item',
                class: 'btn w-100',
                onclick: ()=>this.deleteUser(e)
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
    methods: {
      ...HttpClass,
      ...AppMethods,
      ...MethodsConfirm,
      ...ComputedConfirmDeleteUser,
      deleteUser(e){
        e.preventDefault()
        let sendData = new FormData()
        this.sendRequest({
          method: 'POST',
          url: '/user/delete',
          data: sendData,
          success: this.successDelete,
          error: this.errorDelete
        })

      },
      successDelete(result){
        if(result.data.code == 0){
          this.appendUserToStore(result.data, false)
          this.$store.commit('closeConfirmForm')
          console.log(this.$store.state.data.app.articles)
        }else{
          console.log(result)
        }
      },
      errorDelete(result){

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