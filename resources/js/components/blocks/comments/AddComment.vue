<template lang="pug">
DivElement(classCss="widget" v-if="!user.auth")
  RowElement(classCss="align_m-center")
    DivElement(classCss="col, col_6, col_mob-12")
      DivElement(classCss="wysiwyg, mb_m-0")
        p {{'Комментировать могут только зарегистрированные пользователи'}}
    DivElement(classCss="col, col_6, col_mob-12")
      DivElement(classCss="buttons, mb-0")
        DivElement(classCss="buttons__list, direction_m-column, align_m-end")
          DivElement(classCss="buttons__item")
            LinkElement(classCss="btn, btn_tiny" :onclick="showLoginForm")
              SpanElement(classCss="btn__text") {{'Зарегистрироваться'}}
DivElement(classCss="widget" v-else)
  DivElement(classCss="formular")
    DivElement(classCss="formular__main")
      NotificationPopup(v-if="notificationMessage" :message="notificationMessage")
      FormElement(:method="$store.state.schemas.addComment.method" :onsubmit="addComment")
        fieldset
          DivElement(classCss="section__subsection, subsection" v-for="(step, index) in $store.state.schemas.addComment.steps" :key="index")
            DivElement(classCss="subsection__main")
              RowElement
                DivElement(classCss="col, col_10, col_mob-12")
                  DivElement(classCss="form__group, group")
                    DivElement(classCss="group__main")
                      DivElement(classCss="form-item" v-for="(input, subIndex) in step.inputs" :key="subIndex")
                        DivElement(classCss="form-item__header" v-if="input.header")
                          DivElement(classCss="form-item__label")
                            DivElement(classCss="form-item__title, title")
                              SpanElement(classCss="title__text") {{input.header.title}}
                        DivElement(classCss="form-item__main")
                          InputElement(:data="input")


</template>

<script>

import DivElement from '@/js/components/elements/Div'
import RowElement from '@/js/components/elements/Row'
import LinkElement from '@/js/components/elements/Link'
import SvgElement from '@/js/components/elements/Svg'
import SpanElement from '@/js/components/elements/Span'
import FormElement from '@/js/components/elements/Form'
import InputElement from '@/js/components/elements/Input'
import NotificationPopup from '@/js/components/elements/Notification'

import HttpClass from '@/js/classes/Http'
import AppMethods from '@/js/methods/App'


export default {
  name: 'AddCommentBlock',
  props: ['article'],
  data()
  {
    return {
      user: this.$store.state.data.app.user,
      schema: this.$store.state.schemas.addComment,
      notificationMessage: '',
    }
  },
  computed:{

  },
  methods: {
    ...HttpClass,
    ...AppMethods,
    addComment(e){
      e.preventDefault()
      let sendData = this.SchemasToFormData(this.schema)
      sendData.append('article_id', this.article.id)
      this.sendRequest({
        method: this.schema.method,
        url: this.schema.action,
        data: sendData,
        success: this.AddSuccess,
        error: this.handlerErrorResponse
      })
    },
    AddSuccess(result){
      if(result.data.code == 0){
        this.appendUserToStore(result.data, false)
        this.schema.steps[0].inputs[0].value = ''
        if(this.notificationMessage.length > 0){
          this.notificationMessage = ''
        }
        this.notificationMessage = result.data.desc
      }
    },
    handlerErrorResponse(result){
      console.log(result)
    },
    showLoginForm(){
      this.$store.commit('closeModalLogin')
    },

  },
  components: {
    DivElement, SvgElement, SpanElement,
    RowElement, LinkElement, FormElement, InputElement,
    NotificationPopup
  },
  mounted() {
    // console.log('addComment->',this.schema.steps[0].inputs)
  }
}
</script>

<style lang="scss">
</style>