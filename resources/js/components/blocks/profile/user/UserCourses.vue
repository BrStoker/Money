<template lang="pug">
DivElement(classCss="box")
  DivElement(classCss="box__list")
    RowElement
      CourseBlock(v-for="(course, index) in courses" :key="index" :data="course")






</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import RowElement from '@/js/components/elements/Row'
  import LinkElement from '@/js/components/elements/Link'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'
  import ImageElement from '@/js/components/elements/Image'
  import Tooltips from '@/js/components/blocks/TooltipsUserArticleNew'
  import TooltipFooter from '@/js/components/blocks/TooltipsArticleFooter'
  import CourseBlock from '@/js/components/blocks/CourseBlock'
  export default {
    name: 'UserCoursesBlock',

    data() {

      return {
        courses: this.$store.state.data.app.user_detail.courses,

      }
    },
    components:{
      DivElement, RowElement, LinkElement, SvgElement, SpanElement, ImageElement,
      Tooltips, TooltipFooter, CourseBlock

    },
    computed: {

    },
    methods: {

    },
    mounted(){
    }
  }
</script>

<style lang="scss">

</style>
