<template lang="pug">
  DivElement(classCss="widget, mb-0")
    DivElement(classCss="row, align_m-center")
      DivElement(classCss="col, col_6, col_mob-12")
        DivElement(classCss="wysiwyg, mb_m-0")
          p {{'Цена'}}
          h4 {{getPrice()}}
      DivElement(classCss="col, col_6, col_mob-12")
        DivElement(classCss="buttons, mb-0")
          DivElement(classCss="buttons__list, direction_m-column, align_m-end")
            DivElement(classCss="buttons__item" :onclick="buyCourse")
              LinkElement(classCss="btn")
                SpanElement(classCss="btn__text") {{'Купить курс'}}

</template>

<script>

  import EmptyElement from '@/js/components/elements/Empty'
  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import SvgElement from '@/js/components/elements/Svg'
  import HttpClass from '@/js/classes/Http'
  import NotificationPopup from '@/js/components/elements/Notification'

  export default{
    name: 'CoursePriceBlock',
    props: ['course'],
    data(){

      return{
        courseData: this.course,
        stars: 5,
        imgDownload: '/image/svg/sprite.svg#downloads',
        imgComments: '/image/svg/sprite.svg#comments'

      }
    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement, NotificationPopup, EmptyElement

    },
    methods:{
      ...HttpClass,
      getPrice(){

        if(this.course.free){
          return 'Курс бесплатный'
        }else{
          return [this.courseData.price, '\u20BD'].join(' ')
        }
      },
      buyCourse(){

        if(this.$store.state.data.app.user.auth){

        }else{
          this.$store.commit('closeModalLogin')
        }


      }
    },
    mounted(){


    },
    created(){

    }

  }


</script>

<style lang="scss" scoped>
</style>