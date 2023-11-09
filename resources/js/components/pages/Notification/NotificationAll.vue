<template lang="pug">
EmptyElement
  DivElement(classCss="section__subsection, subsection" v-for="(item, index) in schema.steps" :key="index")
    DivElement(classCss="subsection__header")
      DivElement(classCss="wysiwyg")
        h3 {{item.title}}
    DivElement(classCss="subsection__main")
      SubscribersList(v-for="(user, subIndex) in notification.subscribe" :key="subIndex" :user="user")
      ArticleLikes(v-for="(article, articleIndex) in notification.score" :key="articleIndex" :article="article" v-if="item.name == 'score'")
      ArticleComments(v-for="(article, commentIndex) in notification.comment" :key="commentIndex" :article="article" v-if="item.name == 'comment'")
</template>

<script>
import EmptyElement from '@/js/components/elements/Empty'
import DivElement from '@/js/components/elements/Div'
import MainElement from '@/js/components/elements/Main'
import SectionElement from '@/js/components/elements/Section'
import SpanElement from '@/js/components/elements/Span'

import SubscribersList from '@/js/components/pages/Notification/SubscribersList'
import ArticleLikes from '@/js/components/pages/Notification/NotificationArticleLikes'
import ArticleComments from '@/js/components/pages/Notification/ArticleComments'

import AppMethods from '@/js/methods/App'


export default {
  name: 'NotificationAllPage',


  data(){
    return{

      schema: this.$store.state.schemas.notificationAll,
      notification: this.$store.state.data.app.notifications


    }
  },
  components:{
    EmptyElement,
    MainElement,
    SectionElement,
    SpanElement,
    DivElement,
    SubscribersList,
    ArticleLikes,
    ArticleComments

  },
  methods:{
    ...AppMethods,
    // showBlock(){
    //   let steps = this.schema.steps
    //   let counts = this.$store.state.data.app.notifications.counts
    //   Object.keys(counts).forEach(function(key) {
    //     Object.keys(steps).forEach(function(item){
    //       if(counts[key] > 0 && steps[item]['name'] == key){
    //         steps[item]['show'] = true
    //       }
    //     })
    //   })
    // }
  },
  created(){
    // this.showBlock()
  },
  mounted(){

  }
}


</script>

