<template lang="pug">
  DivElement(classCss="tooltips, tooltips_tertiary")
    DivElement(classCss="tooltips__list")
      DivElement(:classCss="tooltip.parentClass" v-for="(tooltip, index) in tooltips" :key="index")
        DivElement(classCss="tooltips__title, title" v-if="tooltip.image == ''")
          SpanElement(classCss="title__text") {{tooltip.text}}
        DivElement(classCss="tooltips__media" v-else-if="tooltip.image != ''")
          SvgElement(:image="tooltip.image")

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import SvgElement from '@/js/components/elements/Svg'

  export default{
    name: 'TooltipsArticleBlock',
    props: ['article'],
    data(){

      return{
        articleData: this.article,
        tooltips: [
          {
            image: '',
            text: new Date(this.article.created_at).toISOString().slice(0,10).split('-').reverse().join('.'),
            parentClass: 'tooltips__item',
            class: 'tooltips__title, title',
            classChild: 'title__text'
          },
        ]
      }
    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement

    },
    methods:{

    },
    computed: {

    },
    mounted(){
    }

  }


</script>

<style lang="scss" scoped>
</style>