<template lang="pug">

  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Партнёрка" :link="link")
        DivElement(classCss="layout__main")
          SectionElement(classCss="layout__section, section, section_animation")
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
                          PartnersStructure
                        template(v-if="index == 1")
                          PartnersPromo
                        template(v-if="index == 2")
                          PartnersStatistics
                        template(v-if="index == 3")
                          PartnersConditions

        FooterBlock
    ModalLayout

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SectionElement from '@/js/components/elements/Section'
  import MainElement from '@/js/components/elements/Main'
  import SpanElement from '@/js/components/elements/Span'
  import HeaderBlock from '@/js/components/blocks/Header'
  import WrapperBlock from '@/js/components/blocks/Wrapper'
  import AsideBlock from '@/js/components/blocks/Aside'
  import FooterBlock from '@/js/components/blocks/Footer'
  import ContentBlock from '@/js/components/blocks/Content'
  import BreadcrumbsBlock from '@/js/components/blocks/Breadcrumbs'

  import PartnersStructure from '@/js/components/pages/Partners/PartnersStructure'
  import PartnersPromo from '@/js/components/pages/Partners/PartnersPromo'
  import PartnersStatistics from '@/js/components/pages/Partners/PartnersStatistics'
  import PartnersConditions from '@/js/components/pages/Partners/PartnersConditions'

  import AppMethods from '@/js/methods/App'
  import ComputedIndex from '@/js/computed/pages/Index'
  import ModalLayout from '@/js/components/blocks/ModalLayout'
  import ComputedFeed from '@/js/computed/pages/Feed'
  import FeedEvents from '@/js/events/pages/Feed'




  export default {
    name: 'PartnersPage',
    props: ['data'],
    data() {
      return {
        activeTab: 0,
        user: this.$store.state.data.app.user,
        tabs: [
          {
            text: 'Структура',
            click: this.handlerTab
          },
          {
            text: 'Рекламные материалы',
            click: this.handlerTab
          },
          {
            text: 'Статистика',
            click: this.handlerTab
          },
          {
            text: 'Условия',
            click: this.handlerTab
          },
        ],
        link: '/partners'
      }
    },
    components: {
      DivElement, LinkElement,  SectionElement, MainElement,
      HeaderBlock, WrapperBlock, AsideBlock, BreadcrumbsBlock, ContentBlock, FooterBlock,
      ModalLayout, SpanElement, PartnersStructure, PartnersPromo, PartnersStatistics, PartnersConditions

    },
    methods: {
      ...ComputedIndex,
      ...FeedEvents,
      ...ComputedFeed,
      ...AppMethods
      
    },
    created(){
      // console.log(JSON.parse(this.data))
      this.appendUserToStore(this.data)
    },
    mounted() {

      // this.appendUserToStore(this.data)

    }
  }

</script>

<style lang="scss">
</style>