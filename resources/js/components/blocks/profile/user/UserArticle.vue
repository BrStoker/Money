<template lang="pug">

  SectionElement(classCss="layout__section, section")
    DivElement(classCss="section__main")
      DivElement(classCss="tabs")
        DivElement(classCss="tabs__header")
          DivElement(classCss="tabs__list")
            DivElement(v-for="(item, index) in tabs" classCss="tabs__item" :class="tabsItemClass(index)" :key="index")
              DivElement(classCss="tabs__title, title" :onclick="() => { item.click(index) }")
                SpanElement(classCss="title__text") {{item.text}}
        DivElement(classCss="tabs__body")
          DivElement(classCss="tabs__list")
            DivElement(:key="index" v-for="(item, index) in tabs" :classCss="tabsItemClass(index, [ 'tabs__item' ])")
              template(v-if="index == 0")
                UserArticleNew
              template(v-if="index == 1")

              template(v-if="index == 2")

              template(v-if="index == 3")
                FavoriteBlock(:data="1")
</template>

<script>

  import MainElement from '@/js/components/elements/Main'
  import SectionElement from '@/js/components/elements/Section'
  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'
  import AppMethods from '@/js/methods/App'
  import EventsUserArticle from '@/js/events/pages/User/UserArticles'
  import ComputedUserArticles from '@/js/computed/pages/User/UserArticles'

  import UserArticleNew from '@/js/components/blocks/profile/user/UserArticleNew'
  import FavoriteBlock from '@/js/components/blocks/profile/user/Favorite'

  export default {
    name: 'UserArticleBlock',

    data() {

      return {
        activeTab: 0,
        User: this.$store.state.data.app.user.data,
        articles: this.$store.state.data.app.article,
        store: this.$store.state.data.usersocial,
        tabs: [
          {
            text: 'Новые',
            click: this.handlerTab
          },
          {
            text: 'Популярные',
            click: this.handlerTab
          },
          {
            text: 'Категории',
            click: this.handlerTab
          },
          {
            text: 'Избранное',
            click: this.handlerTab
          },
        ]


      }
    },
    components:{
      MainElement, SectionElement, DivElement, LinkElement, SvgElement, SpanElement,
      UserArticleNew, FavoriteBlock
    },
    computed: {

    },
    methods: {
      ...AppMethods,
      ...EventsUserArticle,
      ...ComputedUserArticles

    },
    mounted(){

    }
  }
</script>

<style lang="scss">

</style>