<template lang="pug">
DivElement(classCss="box")
  DivElement(classCss="wysiwyg")
    SpanElement() {{info}}


</template>

<script>

import DivElement from '@/js/components/elements/Div'
import RowElement from '@/js/components/elements/Row'
import LinkElement from '@/js/components/elements/Link'
import ImageElement from '@/js/components/elements/Image'
import SpanElement from '@/js/components/elements/Span'


export default {
  name: 'ArticlesCategoryPage',
  data()
  {
    return {
      info: 'Страница находится в стадии разработки'
    }
  },
  computed:{

  },
  methods: {

  },
  components: {
    DivElement,
    RowElement, LinkElement, ImageElement, SpanElement
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