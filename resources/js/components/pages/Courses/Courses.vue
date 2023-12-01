<template lang="pug">

  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Курсы" :link="link")
        MainElement(classCss="layout__main")
          SectionElement(classCss="layout__section, section, section_animation")
            DivElement(classCss="section__header")
              RowElement
                DivElement(classCss="col, col_6, col_mob-12")
                  DivElement(classCss="wysiwyg")
                    h2 {{title}}
                DivElement(classCss="col, col_6, col_mob-12" v-if="user.auth")
                  DivElement(classCss="form-item, ml_m-auto")
                    DivElement(classCss="form-item__main")
                      DivElement(classCss="form-item__field")
                        ButtonElement(:classCss="button.class" :type="button.type" :placeholder="button.title" :onclick="()=>redirectToAddCourse()")
                          SpanElement(classCss="btn__text") {{button.title}}
            DivElement(classCss="section__main")
              DivElement(classCss="tabs")
                DivElement(classCss="tabs__header")
                  DivElement(classCss="tabs__list")
                    DivElement(v-for="(item, index) in tabs" classCss="tabs__item" :class="tabsItemClass(index)" :key="index" v-if="showTab(item)")
                      DivElement(classCss="tabs__title, title" :onclick="() => { item.click(index) }")
                        SpanElement(classCss="title__text") {{item.text}}
                DivElement(classCss="tabs__body")
                  DivElement(classCss="tabs__list")
                    DivElement(:key="index" v-for="(item, index) in tabs" :classCss="tabsItemClass(index, [ 'tabs__item' ])" v-if="showTab(item)")
                      template(v-if="index == 0")
                        CoursesAll
                      template(v-if="index == 1")
                        CoursesPopular
                      template(v-if="index == 2")
                        CoursesUser


        FooterBlock
    ModalLayout

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SectionElement from '@/js/components/elements/Section'
  import MainElement from '@/js/components/elements/Main'
  import RowElement from '@/js/components/elements/Row'
  import ButtonElement from '@/js/components/elements/Button'
  import SpanElement from '@/js/components/elements/Span'

  import HeaderBlock from '@/js/components/blocks/Header'
  import WrapperBlock from '@/js/components/blocks/Wrapper'
  import AsideBlock from '@/js/components/blocks/Aside'
  import FooterBlock from '@/js/components/blocks/Footer'
  import ContentBlock from '@/js/components/blocks/Content'
  import BreadcrumbsBlock from '@/js/components/blocks/Breadcrumbs'

  import CoursesAll from '@/js/components/pages/Courses/CoursesAll'
  import CoursesPopular from '@/js/components/pages/Courses/CoursesPopular'
  import CoursesUser from '@/js/components/pages/Courses/CoursesUser'

  
  import ModalLayout from '@/js/components/blocks/ModalLayout'
  import AppMethods from '@/js/methods/App'
  import ComputedIndex from '@/js/computed/pages/Index'
  import ComputedFeed from '@/js/computed/pages/Feed'
  import FeedEvents from '@/js/events/pages/Feed'

  export default {
    name: 'FeedPage',
    props: ['data'],
    data() {
      return {
        activeTab: 0,
        user: this.$store.state.data.app.user,
        title: 'Курсы',
        button: {
          title: 'Добавить курс',
          class: 'btn, w-100, w_m-auto',
          type: 'submit'
        },
        link: '/courses',
        tabs: [
          {
            text: 'Все',
            click: this.handlerTab,
            needAuth: false
          },
          {
            text: 'Популярные',
            click: this.handlerTab,
            needAuth: false
          },
          {
            text: 'Мои',
            click: this.handlerTab,
            needAuth: true
          }
        ],

      }
    },
    components: {
      DivElement, LinkElement, SectionElement, MainElement, RowElement, ButtonElement, SpanElement,
      HeaderBlock, WrapperBlock, AsideBlock, BreadcrumbsBlock, ContentBlock, FooterBlock, ModalLayout,
      CoursesAll, CoursesPopular, CoursesUser

    },
    methods: {
      ...AppMethods,
      ...ComputedIndex,
      ...FeedEvents,
      ...ComputedFeed,
      showTab(item){
        if(item.needAuth){
          return !!this.user.auth;
        }else{
          return true
        }
      },
      redirectToAddCourse(){
        window.location.href = '/courses/add'
      }
      
    },
    created(){
      this.appendUserToStore(this.data)
    },
    mounted() {

    }
  }

</script>

<style lang="scss">
</style>