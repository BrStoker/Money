<template lang="pug">
  DivElement(classCss="col, col_6, col_mob-12")
    DivElement(classCss="box__item")
      DivElement(classCss="box__media")
        LinkElement(:href="getCourseLink()" classCss="box__link")
          ImageElement(:src="getImage()" classCss="media__img")
      DivElement(classCss="box__layout")
        DivElement(classCss="box__group")
          TooltipCourse(:course="course")
          DivElement(classCss="wysiwyg, mb-0")
            h4
              LinkElement(:href="getCourseLink()") {{course.title}}
            p(v-html="course.preview")
        DivElement(classCss="box__group")
          DivElement(classCss="series, mb-0, align_m-center, no-wrap")
            DivElement(classCss="series__group, series__group_tertiary")
              DivElement(classCss="media, media_tertiary, mb_m-0")
                ImageElement(:src="getUserImage()")
            DivElement(classCss="series__group")
              DivElement(classCss="wysiwyg, mb_m-0")
                h6
                  LinkElement(:href="getUserLink()") {{authorFIO()}}
                p(v-if="course.courses_user.signature") {{course.courses_user.signature}}
        DivElement(classCss="box__group")
          DivElement(classCss="series, mb-0, align_m-center, no-wrap")
            DivElement(classCss="series__group, mb-0, d-flex, align_center")
              DivElement(classCss="wysiwyg, mb-0")
                h4 {{getPrice()}}
            DivElement(classCss="series__group, ml-auto, mb-0")
              DivElement(classCss="buttons, mb-0")
                DivElement(classCss="buttons__list, buttons__list, direction_m-column, align_m-end")
                  DivElement(classCss="buttons__item, mb-0" :onclick="buyCourse")
                    LinkElement(classCss="btn, btn_tiny, btn_tertiary, btn_m-half-circle")
                      SvgElement(classCss="btn__ico, m_hide" :image="imgCourseAdd")
                      SpanElement(classCss="btn__text") {{'Подписаться'}}




</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import ImageElement from '@/js/components/elements/Image'
  import SpanElement from '@/js/components/elements/Span'
  import SvgElement from '@/js/components/elements/Svg'
  import TooltipCourse from '@/js/components/blocks/TooltipsCourse'

  export default {
    name: 'CourseBlock',
    props: ['data'],
    data(){
      return{
        course: this.data,
        userImage: '/image/avatar.png',
        imgCourseAdd: '/image/svg/sprite.svg#courseAdd'
      }
    },
    components: {
      DivElement,
      LinkElement,
      ImageElement,
      SpanElement,
      SvgElement,
      TooltipCourse
    },
    methods:{
      getImage(){
        if(this.course.image){
          return '/storage/' + this.course.image
        }else{
          return '/image/no-image.png'
        }
      },
      getUserImage(){
        if(this.course.courses_user.image){
          return this.course.courses_user.image
        }else{
          return this.userImage
        }

      },
      authorFIO(){
        return [this.course.courses_user.first_name, this.course.courses_user.last_name].join(' ')
      },
      getUserLink(){
        return '/id' + this.course.courses_user.id
      },
      getCourseLink(){
        return '/course/' + this.course.slug
      },
      getPrice(){
        if(this.course.free){
          return 'Курс бесплатный'
        }else{
          return [this.course.price, '\u20BD'].join(' ')
        }
      },
      getRaiting(number){
        if(number >= this.course.score){
          return this.imgStar
        }else{
          return this.imgStarFiled
        }
      },
      filterCourse(e){
        e.preventDefault()
        //TODO Дописать кусок по фильтрации курсов по клику на тип курса
      },
      buyCourse(){
        if (this.$store.state.data.app.user.auth){

        }else{
          this.$store.commit('closeModalLogin')
        }
      }
    }
  }
  
</script>

<style lang="scss"></style>