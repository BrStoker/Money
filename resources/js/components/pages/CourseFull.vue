<template lang="pug">
MainElement(classCss="layout__main")
  SectionElement(classCss="layout__section, section, section_animation")
    DivElement(classCss="section__header")
      DivElement(classCss="wysiwyg")
        h2 {{course.title}}
      TooltipsHeader(:course="course")
    DivElement(classCss="section__main")
      DivElement(classCss="section__subsection, subsection")
        DivElement(classCss="subsection__main")
          DivElement(classCss="banner, banner_top")
            ImageElement(:src="getCourseImage()")
          DivElement(classCss="wysiwyg")
            h3 {{'Описание'}}
            p(v-html="course.detail_text")
          CoursePrice(:course="course")
      AuthorCourseFooter(:user="course.author")
      CommentsBlock(:course="course")

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SectionElement from '@/js/components/elements/Section'
  import MainElement from '@/js/components/elements/Main'
  import ImageElement from '@/js/components/elements/Image'



  import TooltipsHeader from '@/js/components/blocks/TooltipsCourse'
  import AuthorHeader from '@/js/components/blocks/AuthorArticleFooter'
  import CoursePrice from '@/js/components/blocks/CoursePrice'
  import CommentsBlock from '@/js/components/blocks/reviews/CourseReviews'
  import AppMethods from '@/js/methods/App'
  import SpanElement from "@/js/components/elements/Span"
  import SvgElement from '@/js/components/elements/Svg'
  import RowElement from "@/js/components/elements/Row";
  import AuthorCourseFooter from '@/js/components/blocks/AuthorCourseFooter'

  export default {
    name: 'CourseFullPage',
    props: ['data'],
    data() {

      return {
        course: this.data,
        image: '/image/no-image.png',

      }
    },
    components: {
      RowElement,
      SpanElement,
      DivElement, LinkElement, SectionElement, MainElement, ImageElement,
      TooltipsHeader, AuthorHeader, CoursePrice, CommentsBlock, SvgElement, AuthorCourseFooter

    },
    methods: {
      ...AppMethods,

      getCourseImage(){
        if(this.course.image){
          return '/storage/' + this.course.image
        }else{
          return this.image
        }
      },


      
    },
    created(){
      // console.log(this.data)
    },
    mounted(){
      console.log(this.data)
    }
  }

</script>

<style lang="scss">
</style>