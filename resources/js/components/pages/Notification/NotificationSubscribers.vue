<template lang="pug">
EmptyElement
  DivElement(classCss="section__subsection, subsection")
    DivElement(classCss="subsection__header")
      DivElement(classCss="wysiwyg")
        h3 {{title}}
    DivElement(classCss="subsection__main")
      RowElement
        DivElement(classCss="col, col_4, col_mob-12")
          SubscribersList(v-for="(user, index) in subscribers" :key="index" :user="user")
</template>

<script>
import EmptyElement from '@/js/components/elements/Empty'
import DivElement from '@/js/components/elements/Div'
import MainElement from '@/js/components/elements/Main'
import SectionElement from '@/js/components/elements/Section'
import SpanElement from '@/js/components/elements/Span'
import RowElement from '@/js/components/elements/Row'
import ImageElement from '@/js/components/elements/Image'
import SvgElement from '@/js/components/elements/Svg'
import LinkElement from '@/js/components/elements/Link'

import SubscribersList from '@/js/components/pages/Notification/SubscribersList'


import AppMethods from '@/js/methods/App'


export default {
  name: 'NotificationLikesPage',

  data(){
    return{
      title: 'Подписки',
      subscribers: this.$store.state.data.app.notifications.subscribe,
      avatar: '/image/avatar.png',
      statusIco: '/image/svg/sprite.svg#status',


    }
  },
  components:{
    EmptyElement,
    MainElement,
    SectionElement,
    SpanElement,
    DivElement,
    RowElement,
    ImageElement,
    SvgElement,
    LinkElement,
    SubscribersList

  },
  methods:{
    ...AppMethods,
    getImage(userData){
      return userData.image ? '/storage/' + userData.image : this.avatar
    },
    getFio(userData){
      console.log('getFio',userData)
      return [userData.first_name, userData.last_name].join(' ')
    },
    getLink(userData){
      return userData.id ? '/user/detail/' + userData.id : '#'
    }
  },
  mounted(){
    // console.log(this.subscribers)
  }
}


</script>

