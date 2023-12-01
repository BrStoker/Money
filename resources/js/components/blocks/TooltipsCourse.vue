<template lang="pug">
  EmptyElement
    DivElement(classCss="tooltips")
      DivElement(classCss="tooltips__list")
        DivElement(classCss="tooltips__item" v-for="(type, index) in courseData.courseType" :key="index")
          DivElement(classCss="tooltips__tag, tag")
            SpanElement(classCss="tag__text") {{type.title}}
    DivElement(classCss="tooltips")
      DivElement(classCss="tooltips__list")
        DivElement(classCss="tooltips__item, mr-0" v-for="number in stars" :key="number")
          DivElement(classCss="tooltips__media" :onclick="()=>rateCourse(number)")
            SvgElement(:image="getRaiting(number)")
        DivElement(classCss="tooltips__item, ml_m-auto")
          DivElement(classCss="tooltips__media")
            SvgElement(:image="imgDownload")
          DivElement(classCss="tooltips__title, title")
            SpanElement(classCss="title__text") {{courseData.downloaded}}
        DivElement(classCss="tooltips__item")
          DivElement(classCss="tooltips__media")
            SvgElement(:image="imgComments")
          DivElement(classCss="tooltips__title, title")
            SpanElement(classCss="title__text") {{courseData.countComm}}

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
  name: 'TooltipsArticleBlock',
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
    getRaiting(number){
      if(number >= this.course.score){
        return '/image/svg/sprite.svg#star'
      }else{
        return '/image/svg/sprite.svg#star-filed'
      }
    },
    rateCourse(number){
      let sendData = new FormData
      sendData.append('rate', number)
      sendData.append('course_id', this.course.id)
      console.log(sendData)
      this.sendRequest({
        method: 'POST',
        url: '/course/vote',
        data: sendData,
        success: this.rateSuccess,
        error: this.rateError
      })


    },
    rateSuccess(result){
      console.log(result)
    },
    rateError(result){
      console.error(result)
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