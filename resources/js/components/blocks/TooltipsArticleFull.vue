<template lang="pug">
  DivElement(classCss="tooltips, tooltips_tertiary")
    DivElement(classCss="tooltips__list")
      DivElement(classCss="tooltips__item" v-for="(tooltip, index) in tooltips" :key="index")
        DivElement(classCss="tooltips__item, ml_auto")
          DivElement(classCss="tooltips__title, title" v-if="tooltip.image == ''")
            SpanElement(classCss="title__text") {{tooltip.text}}
          DivElement(classCss="tooltips__media" v-else-if="tooltip.image != ''")
            SvgElement(:image="tooltip.image")
          DivElement(classCss="tooltips__title, title" v-if="tooltip.image != ''")
            SpanElement(classCss="title__text") {{tooltip.text}}
</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import SvgElement from '@/js/components/elements/Svg'

  export default{
    name: 'TooltipsArticleFullBlock',
    props: ['article'],
    data(){

      return{
        articleData: this.article,
        views: 0,
        score: 0,
        countComm: 0,
        tooltips: [
          {
            image: '',
            text: new Date(this.article.created_at).toISOString().slice(0,10).split('-').reverse().join('.'),
            parentClass: 'tooltips__item',
            class: 'tooltips__title, title',
            classChild: 'title__text'
          },
          {
            image: '/image/svg/sprite.svg#tooltips_01',
            text: this.article.view,
            parentClass: 'tooltips__item',
            class: 'tooltips__tag, tag',
            classChild: 'tag__text'
          },
          {
            image: '/image/svg/sprite.svg#tooltips_02',
            text: this.article.score,
            parentClass: 'tooltips__item',
            class: 'tooltips__tag, tag',
            classChild: 'tag__text'
          },
          {
            image: '/image/svg/sprite.svg#tooltips_03',
            text: this.article.countComm,
            parentClass: 'tooltips__item',
            class: 'tooltips__tag, tag',
            classChild: 'tag__text'
          },
          {
            image: '/image/svg/sprite.svg#tooltips_04',
            text: 0,
            parentClass: 'tooltips__item, tooltips__item_dinamic, ml_auto',
            class: 'tooltips__item, tooltips__item_dinamic, ml_auto',
            classChild: ''
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
    created(){

    },
    mounted(){

    }

  }


</script>

<style lang="scss" scoped>
</style>