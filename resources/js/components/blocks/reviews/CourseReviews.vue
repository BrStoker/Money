<template lang="pug">
  DivElement(classCss="section__subsection, subsection")
    DivElement(classCss="subsection__header")
      RowElement
        DivElement(classCss="col, col_6, col_mob-12")
          DivElement(classCss="wysiwyg, mb_m-0")
            h4 {{title}}
              SpanElement {{' ' + course.countComm}}
        DivElement(classCss="col, col_6, col_mob-12")
          DivElement(classCss="buttons")
            DivElement(classCss="buttons__list, justify-m-end")
              DivElement(classCss="buttons__item")
                DivElement(classCss="btn, btn_tertiary, disabled")
                  SpanElement(classCss="btn__text") {{btnText}}
                  SpanElement(classCss="btn__tooltip") {{notificationText}}
    DivElement(classCss="subsection__main")
      

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

  export default{
    name: 'CoursePriceBlock',
    props: ['course'],
    data(){

      return{
        courseData: this.course,
        title: 'Отзывы',
        btnText: 'Оставить отзыв',
        notificationText: 'Оставить отзыв можно будет после покупки курса'

      }
    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement, NotificationPopup, EmptyElement,
      RowElement

    },
    methods:{
      ...HttpClass,
      getPrice(){

        if(this.course.free){
          return 'Курс бесплатный'
        }else{
          return [this.courseData.price, '\u20BD'].join(' ')
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