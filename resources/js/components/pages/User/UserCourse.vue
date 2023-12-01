<template lang="pug">
  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Курсы" secondText="Добавить курс" :link="link")
        UserCourseAdd
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
import UserCourseAdd from '@/js/components/pages/User/Course/Add'

import ComputedIndex from '@/js/computed/pages/Index'

import ModalLayout from '@/js/components/blocks/ModalLayout'
import AppMethods from '@/js/methods/App'

export default {
    name: 'UserArticle',
    props: ['data'],
    data()
    {
        return {
          user: this.$store.state.data.app.user.data,
          link: '/courses'
        }
    },
    components: {
      DivElement, HeaderBlock, AsideBlock, FooterBlock, WrapperBlock,  ContentBlock, BreadcrumbsBlock, ModalLayout, UserCourseAdd
    },
    methods: {
      ...AppMethods,
      ...ComputedIndex

    },
  created(){
    this.appendUserToStore(this.data)
  },
  computed:
      {

      },
  mounted(){

  },
  updated(){
      // console.log('UserArticleUpdate')
  }
}
</script>

<style lang="scss">
</style>