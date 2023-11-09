<template lang="pug">
  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Профиль" :link="link" secondText="Редактировать профиль")
        UserEdit
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
import UserEdit from '@/js/components/pages/User/EditCard'

import AppMethods from '@/js/methods/App'

import ComputedIndex from '@/js/computed/pages/Index'
import ModalLayout from '@/js/components/blocks/ModalLayout'


export default {
  name: 'UserProfilePage',
  props: ['data'],
  data()
  {

    return {
      success: [],
      errors: [],
      action: location.pathname + location.search,
      method: 'POST',
      title: 'Профиль',
      button: 'Сохранить',
      link: '/id'


    }

  },
  components: {
    DivElement, HeaderBlock, AsideBlock, FooterBlock, WrapperBlock,  ContentBlock, BreadcrumbsBlock, ModalLayout,
    UserEdit
  },
  methods: {
    ...AppMethods,
    ...ComputedIndex,
  },
  created(){
    this.appendUserToStore(this.data)
  },
  mounted()
  {
    this.link = this.link + this.$store.state.data.app.user.data.id
  },
  updated()
  {

  }
}

</script>

<style lang="scss">
</style>