<template lang="pug">
  DivElement(classCss="layout__breadcrumbs, breadcrumbs")
    DivElement(classCss="breadcrumbs__list")
      DivElement(classCss="breadcrumbs__item")
        LinkElement(:href="link" classCss="breadcrumbs__link")
          SpanElement(class="breadcrumbs__text") {{mainText}}
          SvgElement(classCss="breadcrumbs__ico" :image="arrowRight" v-if="secondText" )
      DivElement(classCss="breadcrumbs__item")
        SpanElement(classCss="breadcrumbs__link")
          SpanElement(classCss="breadcrumbs__text") {{secondText}}

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'


  export default {

    name: 'ContentBlock',
    props: ['mainText', 'secondText', 'link'],
    components: {
      DivElement, LinkElement, SvgElement, SpanElement
    },
    data()
    {
      return{
        arrowRight: '/image/svg/sprite.svg#arrowRight'
      }
    },
    methods:{
      getLink(){
        if (this.link != undefined){
          return window.location = this.link
        }else{
          return window.location.pathname
        }
      }
    },

  }

</script>

<style lang="scss">
</style>