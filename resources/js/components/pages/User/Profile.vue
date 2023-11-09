<template lang="pug">
  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Профиль" :link="link")
        UserCard
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
import UserCard from '@/js/components/pages/User/UserCard'
import ModalLayout from '@/js/components/blocks/ModalLayout'
import AppMethods from '@/js/methods/App'
import ComputedIndex from '@/js/computed/pages/Index'

export default {
  name: 'UserProfilePage',
  props: ['data'],
  data(){
    return {
      success: [],
      errors: [],
      method: 'POST',
      link: '/user/profile'
    }

  },
  components: {
    DivElement,
    HeaderBlock,
    AsideBlock,
    FooterBlock,
    WrapperBlock,
    ContentBlock,
    BreadcrumbsBlock,
    ModalLayout,
    UserCard
  },
  methods: {
    ...AppMethods,
    ...ComputedIndex
  },
  created() {
    this.appendUserToStore(this.data)
  },
  mounted(){

  }
}

</script>

<style lang="scss">
</style>