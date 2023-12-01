<template lang="pug">
  Section(classCss="layout__section, section")
    DivElement(classCss="section__main")
      DivElement(classCss="tabs")
        DivElement(classCss="tabs__header")
          DivElement(classCss="tabs__list")
            DivElement(v-for="(item, index) in tabs" classCss="tabs__item" :class="tabsItemClass(index)" :key="index" v-if="showTab(item)")
              DivElement(classCss="tabs__title, title" :onclick="()=> {handlerTab(index)}")
                SpanElement(classCss="title__text") {{item.name}}
        DivElement(classCss="tabs__body")
          DivElement(classCss="tabs__list")
            DivElement(:key="index" v-for="(item, index) in tabs" :classCss="tabsItemClass(index, [ 'tabs__item' ])" v-if="showTab(item)")
              template(v-if="index == 0")
                UserArticles
              template(v-if="index == 1")
                UserCourses


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
import UserCourses from '@/js/components/blocks/profile/user/UserCourses'

import UserSocialBlock from '@/js/components/blocks/profile/user/UserSocial'
import UserArticles from '@/js/components/blocks/profile/user/UserArticle'
import ComputedUserArticles from '@/js/computed/pages/User/UserArticles'


import MethodsUser from '@/js/methods/pages/User'

export default {
  name: 'UserCard',
  data()
  {
    return {

      tabs: this.$store.state.data.userTabs.tabs,
      userDetail: this.$store.state.data.app.user_detail,
      activeTab: 0

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
    UserArticles,
    UserCourses
  },
  methods: {
    ...MethodsUser,
    ...ComputedUserArticles,
    handlerTab(index) {
      this.activeTab = index
    },
    showTab(item) {
      if(item.countItem){
        return this.userDetail.courses.length > 0;
      }else{
        return true
      }
    }
  },
  computed:{

  },
  mounted()
  {

  },
}

</script>

<style lang="scss">
</style>