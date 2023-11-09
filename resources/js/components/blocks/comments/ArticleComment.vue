<template lang="pug">
DivElement(classCss="section__subsection, subsection")
  DivElement(classCss="subsection__main")
    DivElement(classCss="tabs")
      DivElement(classCss="tabs__header")
        RowElement
          DivElement(classCss="col, col_6, col_mob-12")
            DivElement(classCss="wysiwyg, mb_m-0")
              h4 {{'Комментарии  '}}
                SpanElement {{countComm}}
          DivElement(classCss="col, col_6, col_mob-12")
            DivElement(classCss="tabs__list, justify-m-end")
              DivElement(v-for="(item, index) in tabs" classCss="tabs__item" :class="tabsItemClass(index)" :key="index" v-if="article.countComm > 0")
                DivElement(classCss="tabs__title, title" :onclick="() => { item.click(index) }")
                  SpanElement(classCss="title__text") {{item.text}}
      DivElement(classCss="tabs__body")
        DivElement(classCss="tabs__list")
          DivElement(:key="index" v-for="(item, index) in tabs" :classCss="tabsItemClass(index, [ 'tabs__item' ])")
            template(v-if="index == 0")
              AllComments(:article="article")
            template(v-if="index == 1")




</template>

<script>

import DivElement from '@/js/components/elements/Div'
import RowElement from '@/js/components/elements/Row'
import LinkElement from '@/js/components/elements/Link'
import SvgElement from '@/js/components/elements/Svg'
import SpanElement from '@/js/components/elements/Span'

import AllComments from '@/js/components/blocks/comments/AllComments'


import ComputedIndex from '@/js/computed/pages/Index'
import AppMethods from '@/js/methods/App'
import FeedEvents from '@/js/events/pages/Feed'
import ComputedFeed from '@/js/computed/pages/Feed'

export default {
  name: 'ArticleCommentsBlock',
  props: ['article'],
  data()
  {
    return {
      activeTab: 0,
      countComm: this.article.countComm == 0 ? '' : this.article.countComm,
      tabs: [
        {
          text: 'Новые',
          click: this.handlerTab
        },
        {
          text: 'Популярные',
          click: this.handlerTab
        }
      ]

    }
  },
  computed:{

  },
  methods: {
    ...ComputedIndex,
    ...AppMethods,
    ...FeedEvents,
    ...ComputedFeed

  },
  components: {
    DivElement, SvgElement, SpanElement,
    RowElement, LinkElement, AllComments
  },
  created()
  {

  },
  mounted() {

  }
}
</script>

<style lang="scss">
</style>