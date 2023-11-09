<template lang="pug">
EmptyElement
  AddCommentBlock(:article="articleData")
  ArticleComment(v-for="(comment, index) in comments" :key="index" :comment="comment" :article="articleData")

</template>

<script>

import DivElement from '@/js/components/elements/Div'
import RowElement from '@/js/components/elements/Row'
import LinkElement from '@/js/components/elements/Link'
import SvgElement from '@/js/components/elements/Svg'
import SpanElement from '@/js/components/elements/Span'
import ImageElement from '@/js/components/elements/Image'
import EmptyElement from '@/js/components/elements/Empty'
import AddCommentBlock from '@/js/components/blocks/comments/AddComment'
import ComputedIndex from '@/js/computed/pages/Index'
import AppMethods from '@/js/methods/App'
import ArticleComment from '@/js/components/blocks/comments/Comment'


export default {
  name: 'AllCommentsBlock',
  props: ['article'],
  data()
  {
    return {
      articleData: this.article,
      comments: this.$store.state.data.app.comments
      // schema: this.$store.state.schemas.addComment,
    }

  },
  components: {
    DivElement,
    SvgElement,
    SpanElement,
    RowElement,
    LinkElement,
    AddCommentBlock,
    EmptyElement,
    ImageElement,
    ArticleComment
  },
  computed:{

  },
  methods: {
    ...ComputedIndex,
    ...AppMethods,
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
