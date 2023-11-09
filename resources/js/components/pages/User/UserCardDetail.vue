<template lang="pug">
MainElement(classCss="layout__main")
  Section(classCss="layout__section, section, section_animation")
    DivElement(classCss="section__main")
      RowElement
        DivElement(classCss="col, col_8, col_mob-12")
          DivElement(classCss="series")
            DivElement(classCss="series__group, series__group_main")
              DivElement(classCss="media")
                ImageElement(:src="getAvatar()")
            DivElement(classCss="series__group")
              DivElement(classCss="wysiwyg")
                h2 {{username}}
                p {{signature}}
              Tooltips(:user="User")
              DivElement(classCss="wysiwyg" )
                p(v-html="description")
              DivElement(classCss="buttons")
                DivElement(classCss="buttons__list")
                  DivElement(:classCss="button.class" v-for="(button, index) in buttons" :key="index")
                    LinkElement(:classCss="button.classLink" :onclick="button.onclick" v-if="index == 0")
                      SpanElement(classCss="btn__text") {{buttonText}}
                    LinkElement(:href="button.link" :classCss="button.classLink" v-else :onclick="button.onclick")
                      SpanElement(classCss="btn__text") {{button.name}}
        DivElement(classCss="col, col_4, col_mob-12")
          UserSocialBlock(:data="UserSocials")
  UserArticles
</template>

<script>

import MainElement from '@/js/components/elements/Main'
import DivElement from '@/js/components/elements/Div'
import Section from '@/js/components/elements/Section'
import RowElement from '@/js/components/elements/Row'
import ImageElement from '@/js/components/elements/Image'
import Tooltips from '@/js/components/blocks/Tooltips'
import LinkElement from '@/js/components/elements/Link'
import SpanElement from '@/js/components/elements/Span'

import UserSocialBlock from '@/js/components/blocks/profile/user/UserSocial'
import UserArticles from '@/js/components/blocks/profile/user/UserArticle'

import HttpClass from '@/js/classes/Http'
import AppMethods from '@/js/methods/App'
import MethodsUser from '@/js/methods/pages/User'

export default {
  name: 'UserCardDetail',
  props: ['data'],
  data()
  {
    return {

      User: this.$store.state.data.app.user_detail,
      UserSocials: this.getSocials(this.$store.state.data.app.user_detail),
      currentUser: this.$store.state.data.app.user,
      isSubscribe: false,
      username: this.Fio(this.$store.state.data.app.user_detail),
      signature: this.$store.state.data.app.user_detail.signature,
      description: this.$store.state.data.app.user_detail.description,
      avatar: '/image/avatar.png',
      linkMore: {
        link: '#',
        text: 'Ещё...'
      },
      buttons: [
        {
          name: '',
          class: 'buttons__item, buttons__item_second',
          classLink: 'btn',
          link: '#',
          onclick: this.$store.state.data.app.user_detail.id == this.$store.state.data.app.user.data.id ? ()=>this.redirectToAddArticle() : this.$store.state.data.app.user.auth ? this.isSubscribe ? ()=>this.unSubscribe() : ()=>this.subscribe() : ()=>this.showLoginForm()
        },
        {
          name: this.$store.state.data.app.user_detail.id == this.$store.state.data.app.user.data.id ? 'Редактировать профиль' : 'Написать сообщение',
          class: 'buttons__item',
          classLink: 'btn, btn_tertiary',
          link: '#',
          onclick: this.$store.state.data.app.user_detail.id == this.$store.state.data.app.user.data.id ? ()=>this.redirectToEdit() : this.$store.state.data.app.user.auth ? () => this.writeMessage() : () => this.showLoginForm()

        },
      ],

    }

  },
  components: {
    MainElement, DivElement, Section, RowElement, ImageElement, Tooltips, LinkElement, SpanElement,
    UserSocialBlock, UserArticles
  },
  methods: {
    ...MethodsUser,
    ...AppMethods,
    ...HttpClass,
    getAvatar(){
      return (this.User && this.User.image ? '/storage/' + this.User.image : this.avatar )
    },
    subscribe(){
      let fd = new FormData()
      fd.append('currentUser', this.$store.state.data.app.user.data.id)
      fd.append('userId', this.$store.state.data.app.user_detail.id)
      fd.append('subscribe', true)
      this.sendRequest({
        method: 'POST',
        url: "/subscribe",
        data: fd,
        success: this.confirmSuccess,
        error: this.handleErrorResponse
      })
    },
    confirmSuccess(result){
      if (result.data.code == 0){
        this.isSubscribe = result.data.subscribe
      }
    },
    handleErrorResponse(result){
      console.error(result)
    },
    unSubscribe(){
      let fd = new FormData()
      fd.append('currentUser', this.$store.state.data.app.user.data.id)
      fd.append('userId', this.$store.state.data.app.user_detail.id)
      fd.append('subbscribe', false)
      this.sendRequest({
        method: 'POST',
        url: "/subscribe",
        data: fd,
        success: this.confirmSuccess,
        error: this.handleErrorResponse
      })
    },
    writeMessage(){
      let fd = new FormData()
      fd.append('user_id', this.$store.state.data.app.user_detail.id)
      this.sendRequest({
        method: 'POST',
        url: '/write-message',
        data: fd,
        success: this.successWrite,
        error: this.handleErrorResponse
      })
    },
    successWrite(result){
      if(result.data.success){
        // console.log(result.data)
        window.location.href = result.data.redirectTo
      }
    },
    redirectToEdit() {
      window.location.href = '/user/edit'
    },
    redirectToAddArticle(){
      window.location.href = '/user/article'
    },
    showLoginForm(){
      this.$store.commit('closeModalLogin')
    }
  },
  computed:{
    buttonText() {
      return this.$store.state.data.app.user_detail.id == this.$store.state.data.app.user.data.id ? 'Добавить статью' :this.isSubscribe ? 'Отписаться' : 'Подписаться'
    }

  },
  mounted()
  {

   this.isSubscribe = this.$store.state.data.app.user_detail.isSubscribe

  },
  updated()
  {

  }
}

</script>

<style lang="scss">
</style>