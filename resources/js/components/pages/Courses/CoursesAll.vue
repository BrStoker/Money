<template lang="pug">
  DivElement(classCss="section__subsection, section__subsection_second, subsection")
    DivElement(classCss="subsection__header")
      SearchForm
    DivElement(classCss="subsection__main")
      RowElement
        DivElement(classCss="col, col_8, col_mob-12")
          DivElement(classCss="box")
            DivElement(classCss="box__list")
              RowElement
                CourseBlock(v-for="(item, index) in this.$store.state.data.app.courses" :key="index" :data="item")
        FilterCourses
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
  import CourseBlock from '@/js/components/blocks/CourseBlock'
  import FilterCourses from '@/js/components/blocks/FilterCourses'

  import SearchForm from '@/js/components/blocks/SearchForm'

  import ModalLayout from '@/js/components/blocks/ModalLayout'
  import AppMethods from '@/js/methods/App'
  import ComputedIndex from '@/js/computed/pages/Index'
  import ComputedFeed from '@/js/computed/pages/Feed'
  import FeedEvents from '@/js/events/pages/Feed'


  export default {
    name: 'CoursesAllpage',
    props: ['data'],
    data() {
      return {
        user: this.$store.state.data.app.user,
        courses: this.$store.state.data.app.courses,
        info: 'Страница находится в стадии разработки'
      }
    },
    components: {
      DivElement, LinkElement, SectionElement, MainElement, RowElement, ButtonElement, SpanElement,
      HeaderBlock, WrapperBlock, AsideBlock, BreadcrumbsBlock, ContentBlock, FooterBlock,
      ModalLayout, SearchForm, CourseBlock, FilterCourses

    },
    methods: {
      ...AppMethods,
      ...ComputedIndex,
      ...FeedEvents,
      ...ComputedFeed,
      
    },
    created(){

    },
    mounted() {

    }
  }

</script>

<style lang="scss">
</style>