<template lang="pug">
DivElement(classCss="box")
  DivElement(classCss="box__list, box__list_second")
    RowElement
      DivElement(classCss="col, col_12" v-for="(article, index) in articles" :key="index")
        DivElement(classCss="box__item")
          DivElement(classCss="box__media")
            LinkElement(classCss="box__link" :href="'/' + article.slug")
              ImageElement(:src="getImage(article.image)" classCss="media__img")
          DivElement(classCss="box__layout")
            DivElement(classCss="box__group")
              Tooltips(:article="article")
              DivElement(classCss="wysiwyg")
                h4
                  LinkElement(classCss="" :href="'/' + article.slug") {{article.title}}
                p(v-html="article.preview")
              TooltipFooter(:article="article")
            AuthorArticle(:article="article")
      DivElement(classCss="end_content")


</template>

<script>

import DivElement from '@/js/components/elements/Div'
import RowElement from '@/js/components/elements/Row'
import LinkElement from '@/js/components/elements/Link'
import ImageElement from '@/js/components/elements/Image'
import Tooltips from '@/js/components/blocks/TooltipsArticle'
import TooltipFooter from '@/js/components/blocks/TooltipsArticleFull'
import AuthorArticle from '@/js/components/blocks/AuthorArticleFooter'


export default {
  name: 'ArticlePage',
  data()
  {
    return {
      articles: this.$store.state.data.app.articles,
      image: '/image/no-image.png'
    }
  },
  computed:{

  },
  methods: {
    getImage(image){
      if(image != null){
        return '/storage/' + image
      }else{
        return this.image
      }
    }
  },
  components: {
    DivElement,
    RowElement, LinkElement, ImageElement, Tooltips, TooltipFooter, AuthorArticle
  },
  created()
  {

  },
  mounted() {

  }
}
</script>

<style lang="scss">
</style>