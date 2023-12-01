<template lang="pug">
  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Курсы")
        CourseFull(:data="course.course")
        FooterBlock
    ModalLayout

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SectionElement from '@/js/components/elements/Section'
  import MainElement from '@/js/components/elements/Main'

  import HeaderBlock from '@/js/components/blocks/Header'
  import WrapperBlock from '@/js/components/blocks/Wrapper'
  import AsideBlock from '@/js/components/blocks/Aside'
  import FooterBlock from '@/js/components/blocks/Footer'
  import ContentBlock from '@/js/components/blocks/Content'
  import BreadcrumbsBlock from '@/js/components/blocks/Breadcrumbs'
  import ComputedIndex from '@/js/computed/pages/Index'
  import ModalLayout from '@/js/components/blocks/ModalLayout'
  import CourseFull from '@/js/components/pages/CourseFull'

  import AppMethods from '@/js/methods/App'


  export default {
    name: 'Courses',
    props: [
      'data'
    ],
    data() {

      // var JsonData = JSON.parse(this.data)

      return {
        link: '/courses',
        course: JSON.parse(this.data)

      }
    },
    components: {
      DivElement, LinkElement, SectionElement, MainElement,
      HeaderBlock, WrapperBlock, AsideBlock, BreadcrumbsBlock, ContentBlock, FooterBlock,
      ModalLayout, CourseFull

    },
    methods: {
      ...ComputedIndex,
      ...AppMethods
      
    },
    created(){
      this.appendUserToStore(this.data)
    }
  }

</script>

<style lang="scss">
</style>