<template lang="pug">
DivElement(classCss="series, no-wrap")
  DivElement(classCss="series__group, series__group_tertiary")
    DivElement(classCss="media, media_tertiary, mb_m-0")
      ImageElement(:src="getAvatar(commentData.comment_user.image)")

      

</template>

<script>

  import EmptyElement from '@/js/components/elements/Empty'
  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import SvgElement from '@/js/components/elements/Svg'
  import HttpClass from '@/js/classes/Http'
  import NotificationPopup from '@/js/components/elements/Notification'
  import RowElement from '@/js/components/elements/Row'
  import ImageElement from "./Image";

  export default{
    name: 'CoursePriceBlock',
    props: ['comment'],
    data(){

      return{
        commentData: this.comment,

      }
    },
    components:{
      ImageElement,
      DivElement, LinkElement, SpanElement, SvgElement, NotificationPopup, EmptyElement,
      RowElement

    },
    methods:{
      ...HttpClass,
      getAvatar(image){
        return image == null ? this.avatar : '/storage/' + image
      },
    },
    mounted(){


    },
    created(){

    }

  }


</script>

<style lang="scss" scoped>
</style>