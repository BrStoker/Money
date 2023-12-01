<template lang="pug">
  DivElement(classCss="section__subsection, subsection")
    DivElement(classCss="subsection__header")
      DivElement(classCss="wysiwyg")
        h3 {{'Автор курса'}}
    DivElement(classCss="subsection__main")
      RowElement
        DivElement(classCss="col, col_6, col_mob-12")
          DivElement(classCss="series")
            DivElement(classCss="series__group, series__group_main")
              DivElement(classCss="media")
                ImageElement(:src="getUserImage()")
            DivElement(classCss="series__group")
              DivElement(classCss="wysiwyg")
                h4
                  LinkElement(:href="getUserLink()") {{(getFIO())}}
                p {{userData.signature}}
              DivElement(classCss="tooltips, tooltips_second")
                Tooltips(:user="userData")
              DivElement(classCss="wysiwyg")
                p(v-html="userData.description")
        DivElement(classCss="col, col_6, col_mob-12")
          DivElement(classCss="buttons")
            DivElement(classCss="buttons__list, justify-m-end")
              DivElement(classCss="buttons__item")
                LinkElement(classCss="btn, btn_tertiary")
                  SpanElement(classCss="btn__text") {{'Написать автору'}}

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import SvgElement from '@/js/components/elements/Svg'
  import ImageElement from '@/js/components/elements/Image'
  import Tooltips from '@/js/components/blocks/Tooltips'
  import RowElement from '@/js/components/elements/Row'

  import HttpClass from '@/js/classes/Http'

  export default{
    name: 'TooltipsCourseBlockFooter',
    props: ['user'],
    data(){

      return{
        userData: this.user
      }
    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement, ImageElement, Tooltips, RowElement

    },
    methods:{
      ...HttpClass,
      getUserImage(){
        if(this.userData.image){
          return '/storage/' + this.userData.image
        }else{
          return '/image/avatar.png'
        }
      },
      getFIO(){

        return [this.userData.first_name, this.userData.last_name].join(' ')
      },
      getUserLink(){
        return '/id' + this.userData.id
      }
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