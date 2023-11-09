<template lang="pug">
MainElement(classCss="layout__main")
  SectionElement(classCss="layout__section, section, section_animation")
    DivElement(classCss="section__header")
      TooltipsHeader(:article="article")
      DivElement(classCss="wysiwyg")
        h2 {{article.title}}
      DivElement(classCss="section__main")
        DivElement(classCss="section__subsection, subsection")
          DivElement(classCss="subsection__main")
            DivElement(classCss="banner, banner_top")
              ImageElement(:src="getImage()")
            AuthorHeader(:article="article")
            DivElement(classCss="wysiwyg" v-html="article.detail_text")
        ArticleFooter(:article="article")
        CommentsBlock(:article="article")

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SectionElement from '@/js/components/elements/Section'
  import MainElement from '@/js/components/elements/Main'
  import ImageElement from '@/js/components/elements/Image'

  import TooltipsHeader from '@/js/components/blocks/TooltipsArticleFull'
  import AuthorHeader from '@/js/components/blocks/AuthorArticleFooter'
  import ArticleFooter from '@/js/components/blocks/ArticleFooter'
  import CommentsBlock from '@/js/components/blocks/comments/ArticleComment'
  import AppMethods from '@/js/methods/App'

  export default {
    name: 'ArticleFullPage',
    props: ['data'],
    data() {

      return {
        article: this.data,
        image: '/image/no-image.png'

      }
    },
    components: {
      DivElement, LinkElement, SectionElement, MainElement, ImageElement,
      TooltipsHeader, AuthorHeader, ArticleFooter, CommentsBlock

    },
    methods: {
      ...AppMethods,
      getImage(){
        if(this.article.image != null){
          return '/storage/' + this.article.image
        }else{
          return this.image
        }

      }
      
    },
    created(){
      this.appendUserToStore(this.data, false)
    }
  }

</script>

<style lang="scss">
</style>