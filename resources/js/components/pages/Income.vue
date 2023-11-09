<template lang="pug">

  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Доход" :link="link")

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
  
  import ModalLayout from '@/js/components/blocks/ModalLayout'
  import ComputedIndex from '@/js/computed/pages/Index'
  import AppMethods from '@/js/methods/App'


  export default {
    name: 'FeedPage',
    props: ['data'],
    data() {
      return {

        user: this.$store.state.data.app.user,
        link: '/income'

      }
    },
    components: {
      DivElement, LinkElement, SectionElement, MainElement,
      HeaderBlock, WrapperBlock, AsideBlock, BreadcrumbsBlock, ContentBlock, FooterBlock,
      ModalLayout

    },
    methods: {
      ...ComputedIndex,
      ...AppMethods
      
    },
    created(){
      this.appendUserToStore(this.data)
    },
    mounted() {
      // console.log(this.data)
    }
  }

</script>

<style lang="scss">
</style>