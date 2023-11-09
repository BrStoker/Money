<template lang="pug">
  
  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()") 
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock

        FooterBlock
    ModalLayout

</template>

<script>

  import DivElement from '@/js/components/elements/Div'

  import HeaderBlock from '@/js/components/blocks/Header'
  import WrapperBlock from '@/js/components/blocks/Wrapper'
  import AsideBlock from '@/js/components/blocks/Aside'
  import FooterBlock from '@/js/components/blocks/Footer'
  import ContentBlock from '@/js/components/blocks/Content'
  import BreadcrumbsBlock from '@/js/components/blocks/Breadcrumbs'
  

  import ModalLayout from '@/js/components/blocks/ModalLayout'

  import ComputedIndex from '@/js/computed/pages/Index'

  export default {
    name: 'IndexPage',
    props: [
      'data'
    ],
    data() {

      let user = JSON.parse(this.data)

      return {
        userData: user,
        interests: user,

      }
    },
    components: {
      DivElement,
      HeaderBlock, WrapperBlock, BreadcrumbsBlock, AsideBlock, ContentBlock, FooterBlock,
      ModalLayout

    },
    methods: {
      ...ComputedIndex
    },
    mounted(){

    },
    created(){

    }
  }

</script>

<style lang="scss">
</style>