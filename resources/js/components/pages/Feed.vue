<template lang="pug">
DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
  HeaderBlock
  WrapperBlock
    AsideBlock
    ContentBlock
      BreadcrumbsBlock(mainText="Лента" :link="link")
      MainElement(classCss="layout__main")
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
                        ArticlesAll
                      template(v-if="index == 1")

                      template(v-if="index == 2")
                        ArticlesCategory
      FooterBlock
  ModalLayout

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import SpanElement from '@/js/components/elements/Span'
  import LinkElement from '@/js/components/elements/Link'
  import SectionElement from '@/js/components/elements/Section'
  import MainElement from '@/js/components/elements/Main'

  import HeaderBlock from '@/js/components/blocks/Header'
  import WrapperBlock from '@/js/components/blocks/Wrapper'
  import AsideBlock from '@/js/components/blocks/Aside'
  import FooterBlock from '@/js/components/blocks/Footer'
  import ContentBlock from '@/js/components/blocks/Content'
  import BreadcrumbsBlock from '@/js/components/blocks/Breadcrumbs'
  import ArticlesAll from '@/js/components/blocks/ArticlesAll'
  import ArticlesCategory from '@/js/components/blocks/ArticlesCategory'

  import ModalLayout from '@/js/components/blocks/ModalLayout'


  import ComputedIndex from '@/js/computed/pages/Index'
  import AppMethods from '@/js/methods/App'
  import FeedEvents from '@/js/events/pages/Feed'
  import ComputedFeed from '@/js/computed/pages/Feed'

  import HttpClass from '@/js/classes/Http'

  export default {
    name: 'FeedPage',
    props: [
      'data'
    ],
    data() {

      let datas = JSON.parse(this.data)
      return {
        activeTab: 0,
        link: '/',
        articles: datas,
        page: 1,
        limit: 10,
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
        ]

      }
    },
    components: {
      DivElement, SpanElement, LinkElement, SectionElement, MainElement,
      HeaderBlock, WrapperBlock, AsideBlock, BreadcrumbsBlock, ContentBlock, FooterBlock, ArticlesAll,
      ModalLayout, ArticlesCategory

    },
    methods: {
      ...ComputedIndex,
      ...ComputedFeed,
      ...AppMethods,
      ...FeedEvents,
      ...HttpClass,
      handleScroll(){
        const options = {
          root: document.querySelector(".layout__overflow"),
          rootMargin: "0px",
          threshold: 1.0,
        };
        var callback = (entries, observer) => {
          entries.forEach((entry)=>{

            if(entry.isIntersecting){
              this.loadArticles()
            }
          })
        };
        var observer = new IntersectionObserver(callback, options);

        var target = document.querySelector(".end_content")
        if(target){
          observer.observe(target)
        }


      },
      loadArticles(){
        var sendData = new FormData
        sendData.append('page', this.page + 1)
        sendData.append('limit', this.limit)
        sendData.append('action', 'filter')
        this.sendRequest({
          method: 'POST',
          url: '/article/search',
          data: sendData,
          success: this.loadArticlesSuccess,
          error: this.handlerErrorResponse
        })

      },
      loadArticlesSuccess(result){
        if(result.data.code == 0){
          this.page = this.page + 1
          this.$store.commit('pushArticlesToStore', result.data.articles);
        }
      },
      handlerErrorResponse(result){
        console.error(result)
      }

      
    },
    created(){
      this.appendUserToStore(this.data)
    },
    mounted() {
      window.addEventListener('scroll', this.handleScroll());
    }
  }

</script>

<style lang="scss">
</style>