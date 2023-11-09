<template lang="pug">
  DivElement(classCss="box")
    DivElement(classCss="box__list, box__list_second")
      RowElement
        DivElement(classCss="col, col_12" v-for="(article, index) in this.$store.state.data.app.user_detail.favorite" :key="index")
          DivElement(classCss="box__item")
            DivElement(classCss="box__media")
              LinkElement(classCss="box__link" href="#")
                ImageElement(:src="article.image" classCss="media__img")
            DivElement(classCss="box__layout")
              DivElement(classCss="box__group")
                Tooltips(:article="article")
                DivElement(classCss="wysiwyg")
                  h4
                    LinkElement(classCss="" :href="'/' + article.slug") {{article.title}}
                  p(v-html="article.preview")
                TooltipFooter(:article="article")
              AuthorArticle(:article="article")

</template>

<script>

  import MainElement from '@/js/components/elements/Main'
  import SectionElement from '@/js/components/elements/Section'
  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import RowElement from '@/js/components/elements/Row'
  import ImageElement from '@/js/components/elements/Image'
  import Tooltips from '@/js/components/blocks/TooltipsArticle'
  import TooltipFooter from '@/js/components/blocks/TooltipsArticleFooter'
  import AuthorArticle from '@/js/components/blocks/AuthorArticleFooter'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'
  import AppMethods from '@/js/methods/App'

  export default {
    name: 'UserFavoriteBlock',
    props: ['data'],

    data() {

      return {
        userData: this.$store.state.data.app.user,
        articles: this.$store.state.data.app.user_detail.favorite,
      }
    },
    components:{
      MainElement, SectionElement, DivElement, LinkElement, RowElement, SvgElement, SpanElement,
      ImageElement, Tooltips, TooltipFooter, AuthorArticle
    },
    computed: {

    },
    methods: {
      ...AppMethods

    }
  }
</script>

<style lang="scss">

</style>