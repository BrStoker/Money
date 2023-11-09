<template lang="pug">
MainElement(classCss="layout__main")
  Section(classCss="layout__section, section, section_animation")
    DivElement(classCss="section__main")
      RowElement
        DivElement(classCss="col, col_8, col_mob-12")
          DivElement(classCss="series")
            DivElement(classCss="series__group, series__group_main")
              DivElement(classCss="media")
                ImageElement(:src="getAvatar")
            DivElement(classCss="series__group")
              DivElement(classCss="wysiwyg")
                h2 {{username}}
                p {{signature}}
              Tooltips(:user="User")
              DivElement(classCss="wysiwyg")
                p(v-html="User.description")
              DivElement(classCss="buttons")
                DivElement(classCss="buttons__list")
                  DivElement(:classCss="button.class" v-for="(button, index) in buttons" :key="index")
                    LinkElement(:href="button.link" :classCss="button.classLink")
                      SpanElement(classCss="btn__text") {{button.name}}
        DivElement(classCss="col, col_4, col_mob-12")
          UserSocialBlock
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

import MethodsUser from '@/js/methods/pages/User'

export default {
  name: 'UserCard',
  data()
  {
    return {

      data: this.$store.state.data,
      User: this.$store.state.data.app.user.data,
      UserSocials: this.getSocials(this.$store.state.data.app.user.data),
      avatar: '/image/avatar.png',
      signature: this.$store.state.data.app.user.data.signature,
      username: this.Fio(this.$store.state.data.app.user.data),
      newUser: this.$store.state.data.app.user.data.new,

      buttons: [
        {
          name: 'Добавить статью',
          class: 'buttons__item, buttons__item_second',
          classLink: 'btn',
          link: '/user/article'
        },
        {
          name: 'Редактировать профиль',
          class: 'buttons__item',
          classLink: 'btn, btn_tertiary',
          link: '/user/edit'
        },
      ],

    }

  },
  components: {
    MainElement,
    DivElement,
    Section,
    RowElement,
    ImageElement,
    Tooltips,
    LinkElement,
    SpanElement,
    UserSocialBlock,
    UserArticles
  },
  methods: {
    ...MethodsUser,
    showInterests(){
      if(this.newUser){
        this.$store.commit('closeIntereForm')
      }
    }
  },
  computed:{

    getAvatar(){
      return (this.User && this.User.image ? '/storage/' + this.User.image : this.avatar )
    },


  },
  mounted()
  {
    this.showInterests()
  },
}

</script>

<style lang="scss">
</style>