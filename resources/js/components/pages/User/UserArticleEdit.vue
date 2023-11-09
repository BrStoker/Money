<template lang="pug">
  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Профиль" link="/user/profile" secondText="Редактирование статьи")
        UserArticleEdit(:data="article")
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
import UserArticleEdit from '@/js/components/pages/User/Article/Edit'

import ComputedIndex from '@/js/computed/pages/Index'

import ModalLayout from '@/js/components/blocks/ModalLayout'
import AppMethods from '@/js/methods/App'

export default {
    name: 'UserArticle',
    props: ['data'],
    data()
    {
      let InputData = JSON.parse(this.data)
      return {
            user: this.$store.state.data.app.user.data,
            schema: this.appendDataToSchema(this.$store.state.schemas.addArticle, InputData.article),
            article: InputData.article
        }

    },
    components: {
      DivElement, HeaderBlock, AsideBlock, FooterBlock, WrapperBlock,  ContentBlock, BreadcrumbsBlock, ModalLayout, UserArticleEdit
    },
    methods: {
      ...AppMethods,
      ...ComputedIndex

    },
  created(){

    this.appendUserToStore(this.data)
    this.appendArticleIdToSchema(this.$store.state.schemas.addArticle, this.$store.state.data.app.articleGroups)
  },
  computed:
      {

      },
  mounted(){
      // console.log(JSON.parse(this.data))

  },
}
</script>

<style lang="scss">
</style>