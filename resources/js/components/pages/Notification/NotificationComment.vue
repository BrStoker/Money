<template lang="pug">
EmptyElement
  DivElement(classCss="section__subsection, subsection")
    DivElement(classCss="subsection__header")
      DivElement(classCss="wysiwyg")
        h3 {{title}}
    DivElement(classCss="subsection__main")
      ArticleComments(v-for="(article, index) in comments" :key="index" :article="article")
</template>

<script>
import EmptyElement from '@/js/components/elements/Empty'
import DivElement from '@/js/components/elements/Div'
import MainElement from '@/js/components/elements/Main'
import SectionElement from '@/js/components/elements/Section'
import SpanElement from '@/js/components/elements/Span'

import ArticleComments from '@/js/components/pages/Notification/ArticleComments'

import AppMethods from '@/js/methods/App'


export default {
  name: 'NotificationCommentPage',


  data(){
    return{
      title: 'Комментарии',
      comments: this.$store.state.data.app.notifications.comment

    }
  },
  components:{
    EmptyElement,
    MainElement,
    SectionElement,
    SpanElement,
    DivElement,
    ArticleComments

  },
  methods:{
    ...AppMethods
  },
  mounted(){
  }
}


</script>

