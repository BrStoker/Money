<template lang="pug">
RowElement
  DivElement(classCss="col, col_12")
    DivElement(classCss="box")
      DivElement(classCss="box__list, box__list_second")
        DivElement(classCss="box__item")
          DivElement(classCss="box__media")
            LinkElement(:href="getArticleLink(article)" classCss="box__link")
              ImageElement(:src="getArticleImage(article)" classCss="media__img")
          DivElement(classCss="box__layout")
            DivElement(classCss="box__group")
              DivElement(classCss="tooltips, tooltips_tertiary")
                DivElement(classCss="tooltips__list")
                  DivElement(classCss="tooltips__item")
                    DivElement(classCss="tooltips__title, title")
                      SpanElement(classCss="title__text") {{article.created_at}}
                  DivElement(classCss="tooltips__item")
                    DivElement(classCss="tooltips__tag, tag")
                      SpanElement(classCss="tag__text")
              DivElement(classCss="wysiwyg")
                h4
                  LinkElement(:href="getArticleLink(article)") {{article.title}}
              DivElement(classCss="tooltips, mb-0")
                DivElement(classCss="tooltips__list")
                  DivElement(classCss="tooltips__item" v-for="(tooltip, index) in tooltips" :key="index")
                    DivElement(classCss="tooltips__media")
                      SvgElement(:image="tooltip.image")
                    DivElement(classCss="tooltips__title, title")
                      SpanElement(classCss="title__text") {{tooltip.text}}
  DivElement(classCss="col, col_12")
    DivElement(classCss="series, mb-0, no-wrap" v-for="(user, index) in article.users" :key="index")
      DivElement(classCss="series__group, series__group_tertiary")
        DivElement(classCss="media, media_tertiary, mb_m-0")
          ImageElement(:src="getUserImage(user)")
      DivElement(classCss="series__group")
        DivElement(classCss="wysiwyg, mb_m-0")
          LinkElement(:href="getUserLink(user)")
            h6 {{getFio(user)}}
          p {{user.signature}}
        DivElement(classCss="tooltips")
          DivElement(classCss="tooltips__list")
            DivElement(classCss="tooltips__item")
              LinkElement(classCss="title__text" href="#" :onclick="()=>showAnswerField(user)") {{'Ответить'}}
            DivElement(classCss="tooltips__item")
              DivElement(classCss="tooltips__media")
                SvgElement(:image="images.like")
              DivElement(classCss="tooltips__title, title")
                SpanElement(classCss="title__text") {{'0'}}
              DivElement(classCss="tooltips__media")
                SvgElement(:image="images.dislike")
      DivElement(classCss="series__group")
        DivElement(classCss="wysiwyg, mb_m-0")
          p(v-html="user.text")
    AnswerField(v-if="showField" :data="commentData" :article="article")

</template>

<script>
import EmptyElement from '@/js/components/elements/Empty'
import DivElement from '@/js/components/elements/Div'
import MainElement from '@/js/components/elements/Main'
import SectionElement from '@/js/components/elements/Section'
import SpanElement from '@/js/components/elements/Span'
import RowElement from '@/js/components/elements/Row'
import LinkElement from '@/js/components/elements/Link'
import ImageElement from '@/js/components/elements/Image'
import SvgElement from '@/js/components/elements/Svg'
import AppMethods from '@/js/methods/App'

import AnswerField from '@/js/components/pages/Notification/AnswerField'


export default {
  name: 'NotificationArticleCommentPage',
  props: ['article'],

  data(){
    return{
      title: 'Комментарии',
      userImage: '/image/avatar.png',
      articleImage: '/image/no-image.png',
      showField: false,
      schema: this.$store.state.schemas.answerField,
      commentData:{
        text: '',
        comment_id: '',
      },
      images:{
        like: '/image/svg/sprite.svg#like-up',
        dislike: '/image/svg/sprite.svg#like-bottom'
      },
      tooltips: [
        {
          image: '/image/svg/sprite.svg#tooltips_01',
          text: this.article.view
        },
        {
          image: '/image/svg/sprite.svg#tooltips_02',
          text: this.article.score
        },
        {
          image: '/image/svg/sprite.svg#tooltips_03',
          text: this.article.countComm
        },
        {
          image: '/image/svg/sprite.svg#tooltips_04',
          //TODO нужна формула расчета времени на прочтение статьи
          text: 3
        },

      ]

    }
  },
  components:{
    EmptyElement,
    MainElement,
    SectionElement,
    SpanElement,
    DivElement,
    RowElement,
    LinkElement,
    ImageElement,
    SvgElement,
    AnswerField

  },
  methods:{
    ...AppMethods,
    getArticleLink(article){
      return '/' + article.slug
    },
    getArticleImage(article){
      return article.image ? '/storage/' + article.image : this.articleImage
    },
    getUserImage(user){
      return user.image ? '/storage/' + user.image : this.userImage
    },
    getFio(user){
      return [user.first_name, user.last_name].join(' ')
    },
    getUserLink(user){
      return '/id' + user.id
    },
    showAnswerField(user){
      Object.keys(user).forEach(item=>{
        if(item == 'text'){
          user[item] = '<quote>' + user[item] + '</quote>'
        }
      })
      this.commentData.comment_id = user.comment_id
      this.commentData.text = user.text
      this.showField = true
    }
  },
  updated(){
    console.log('commentUpdated')
  }
}


</script>

