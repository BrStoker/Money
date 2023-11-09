<template lang="pug">
EmptyElement
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
  RowElement
    DivElement(classCss="col, col_4, col_mob-12" v-for="(user, subIndex) in article.users" :key="subIndex")
      DivElement(classCss="series, align_center, no-wrap")
        DivElement(classCss="series__group, series__group_second")
          DivElement(classCss="media, media_tertiary, mb_m-0")
            ImageElement(:src="getUserImage(user)")
            DivElement(classCss="media__ico")
              SvgElement(:image="statusIco")
        DivElement(classCss="series__group")
          DivElement(classCss="tooltips, mb-0")
            DivElement(classCss="tooltips__list")
              DivElement(classCss="tooltips__item")
                DivElement(classCss="tooltips__title, title")
                  SpanElement(classCss="title__text") {{user.score_date}}
          DivElement(classCss="wysiwyg, mb_m-0")
            h6
              LinkElement(:href="getLink(user)") {{getFio(user)}}
            p {{user.signature}}
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


export default {
  name: 'NotificationLikesPage',
  props: ['article'],


  data(){
    return{
      title: 'Лайки',
      articleImage: '/image/no-image.png',
      statusIco: '/image/svg/sprite.svg#status',
      userImage: '/image/avatar.png',
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
    SvgElement

  },
  methods:{
    ...AppMethods,
    getArticleLink(article){
        return '/' + article.slug
    },
    getArticleImage(article){
      if(article.image){
        return '/storage/' + article.image
      }else{
        return this.articleImage
      }
    },
    getUserImage(user){
      if(user.image){
        return '/storage/' + user.image
      }else{
        return this.userImage
      }
    },
    getFio(user){
      return [user.first_name, user.last_name].join(' ')
    },
    getLink(user){
      return user.id ? '/id' + user.id : '#'
    }
  },
  mounted(){
  }
}


</script>

