<template lang="pug">
EmptyElement
  DivElement(classCss="section__subsection, subsection")
    DivElement(classCss="subsection__header")
      DivElement(classCss="wysiwyg")
        h3 {{title}}
    DivElement(classCss="subsection__main")
      ArticleLikes(v-for="(article, index) in likes" :key="index" :article="article")


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

import ArticleLikes from '@/js/components/pages/Notification/NotificationArticleLikes'

import AppMethods from '@/js/methods/App'


export default {
  name: 'NotificationLikesPage',


  data(){
    return{
      title: 'Лайки',
      likes: this.$store.state.data.app.notifications.score

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
    ArticleLikes

  },
  methods:{
    ...AppMethods
  },
  mounted(){
  }
}


</script>

