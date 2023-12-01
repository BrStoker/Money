<template lang="pug">
  DivElement(classCss="section__subsection, section__subsection_second, subsection")
    //DivElement(classCss="subsection__header")
    //  SearchForm
    DivElement(classCss="subsection__main")
      RowElement
        DivElement(classCss="col, col_8, col_mob-12")
          DivElement(classCss="box")
            DivElement(classCss="box__list")
              RowElement
                CourseBlock(v-for="(item, index) in courses" :key="index" :data="item")
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
  
  import ModalLayout from '@/js/components/blocks/ModalLayout'
  import AppMethods from '@/js/methods/App'
  import ComputedIndex from '@/js/computed/pages/Index'
  import ComputedFeed from '@/js/computed/pages/Feed'
  import FeedEvents from '@/js/events/pages/Feed'

  export default {
    name: 'CoursesUserPage',
    props: ['data'],
    data() {
      return {
        user: this.$store.state.data.app.user,
        info: 'Страница находится в стадии разработки',
        courses: this.filterCourses()

      }
    },
    components: {
      DivElement, LinkElement, SectionElement, MainElement, RowElement, ButtonElement, SpanElement,
      HeaderBlock, WrapperBlock, AsideBlock, BreadcrumbsBlock, ContentBlock, FooterBlock,
      ModalLayout, CourseBlock

    },
    methods: {
      ...AppMethods,
      ...ComputedIndex,
      ...FeedEvents,
      ...ComputedFeed,
      filterCourses(){
        var userCourses = []
        var allCourses = this.$store.state.data.app.courses
        _.forEach(allCourses, (course)=>{
          // console.log(this.$store.state.data.app.user.data.id, course)
          if(course.user_id == this.$store.state.data.app.user.data.id){
            userCourses.push(course)
          }
        })
        return userCourses
      }
      
    },
    created(){


    },
    mounted() {

    }
  }

</script>

<style lang="scss">
</style>