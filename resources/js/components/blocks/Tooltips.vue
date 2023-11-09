<template lang="pug">
  DivElement(classCss="tooltips, tooltips_second")
    DivElement(classCss="tooltips__list")
      template(v-for="(tooltip) in this.tooltips")
        DivElement(classCss="tooltips__item")
          DivElement(classCss="tooltips__media")
            SvgElement(:image="tooltip.image")
          DivElement(classCss="tooltips__title")
            SpanElement(classCss="title__text" ) {{tooltip.text()}}

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import SvgElement from '@/js/components/elements/Svg'
  import HttpClass from '@/js/classes/Http'

  export default{
    props: ['user'],
    data(){
      return{

        Userid: '',
        userData: this.user,
        tooltips: [
          {
            image: '/image/svg/sprite.svg#tooltips_01',
            text: ()=>this.getViews(this.user)
          },
          {
            image: '/image/svg/sprite.svg#tooltips_05',
            text: ()=>this.getRaiting(this.user)
          },
          {
            image: '/image/svg/sprite.svg#tooltips_06',
            text: ()=>this.getRegards(this.user)
          }
        ]
      }
    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement

    },
    methods:{
      getViews(data) {
        return data.view
      },
      getRaiting(data){
        return data.score
      },
      getRegards(data){
        return data.subscribe
      },
    },
    mounted(){

    }

  }


</script>

<style lang="scss" scoped>
</style>