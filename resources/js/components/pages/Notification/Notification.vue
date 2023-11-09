<template lang="pug">
  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Уведомления" :link="link")
        MainElement(classCss="layout__main")
          SectionElement(classCss="layout__section, section, section_animation")
            DivElement(classCss="section__main")
              DivElement(classCss="tabs")
                DivElement(classCss="tabs__header")
                  DivElement(classCss="tabs__list")
                    DivElement(classCss="tabs__item" v-for="(item, index) in tabs" :class="tabsItemClass(index)" :key="index")
                      DivElement(classCss="tabs__title, title" :onclick="() => { item.click(index) }")
                        SpanElement(classCss="title__text") {{item.text}}
                      DivElement(classCss="tabs__count, count")
                        SpanElement(classCss="count__text") {{item.countNotif}}
                DivElement(classCss="tabs__body")
                  DivElement(classCss="tabs__list")
                    DivElement(:key="index" v-for="(item, index) in tabs" :classCss="tabsItemClass(index, [ 'tabs__item' ])")
                      template(v-if="index == 0")
                        NotificationAll
                      template(v-if="index == 1")
                        NotificationComment
                      template(v-if="index == 2")
                        NotificationSubscribers
                      template(v-if="index == 3")
                        NotificationLikes
                      template(v-if="index == 4")

        FooterBlock
    ModalLayout

</template>

<script>

import DivElement from '@/js/components/elements/Div'
import MainElement from '@/js/components/elements/Main'
import SectionElement from '@/js/components/elements/Section'
import SpanElement from '@/js/components/elements/Span'

import HeaderBlock from '@/js/components/blocks/Header'
import WrapperBlock from '@/js/components/blocks/Wrapper'
import AsideBlock from '@/js/components/blocks/Aside'
import FooterBlock from '@/js/components/blocks/Footer'
import ContentBlock from '@/js/components/blocks/Content'
import BreadcrumbsBlock from '@/js/components/blocks/Breadcrumbs'
import ComputedIndex from '@/js/computed/pages/Index'
import ModalLayout from '@/js/components/blocks/ModalLayout'

import NotificationAll from '@/js/components/pages/Notification/NotificationAll'
import NotificationComment from '@/js/components/pages/Notification/NotificationComment'
import NotificationLikes from '@/js/components/pages/Notification/NotificationLikes'
import NotificationSubscribers from '@/js/components/pages/Notification/NotificationSubscribers'



import AppMethods from '@/js/methods/App'
import NotificationEvents from '@/js/events/pages/Notification'
import ComputedNotifications from '@/js/computed/pages/Notification'

export default {
  name: 'NotificationPage',
  props: ['data'],

  data(){
    this.appendUserToStore(this.data)
    let notifications = this.$store.state.data.app.notifications
    return{
      activeTab: 0,
      notifications: notifications,
      link: '/user/notification',
      tabs:[
        {
          text: 'Все уведомления',
          click: this.handlerTab,
          countNotif: notifications.counts.all
        },
        {
          text: 'Комментарии',
          click: this.handlerTab,
          countNotif: notifications.counts.comment
        },
        {
          text: 'Подписки',
          click: this.handlerTab,
          countNotif: notifications.counts.subscribe
        },
        {
          text: 'Лайки',
          click: this.handlerTab,
          countNotif: notifications.counts.score
        },
      ]
    }
  },
  components:{
    MainElement,
    SectionElement,
    SpanElement,
    DivElement,
    HeaderBlock,
    WrapperBlock,
    AsideBlock,
    FooterBlock,
    ContentBlock,
    BreadcrumbsBlock,
    ModalLayout,
    NotificationAll,
    NotificationComment,
    NotificationLikes,
    NotificationSubscribers
  },
  methods:{
    ...AppMethods,
    ...ComputedIndex,
    ...ComputedNotifications,
    ...NotificationEvents
  },
  created(){
    // console.log(JSON.parse(this.data))
    // this.appendUserToStore(this.data)
    // this.getCount()
  },
  mounted(){

  }
}


</script>

