<template lang="pug">
DivElement(classCss="tooltips, mb-0")
  NotificationPopup(v-if="notificationMessage" :message="notificationMessage")
  DivElement(classCss="tooltips__list")
    DivElement(classCss="tooltips__item" v-for="(tooltip, index) in tooltips" :key="index")
      DivElement(classCss="tooltips__item, ml_auto")
        DivElement(classCss="tooltips__media")
          SvgElement(:image="tooltip.image")
        DivElement(classCss="tooltips__title, title" :onclick="tooltip.onclick")
          SpanElement(classCss="title__text") {{tooltip.text}}

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import SvgElement from '@/js/components/elements/Svg'
  import HttpClass from '@/js/classes/Http'
  import NotificationPopup from '@/js/components/elements/Notification'

  export default{
    name: 'TooltipsArticleBlockFooter',
    props: ['article'],
    data(){

      return{
        articleData: this.article,
        user: this.$store.state.data.app.user,
        notificationMessage: '',
        tooltips: [
          {
            image: '/image/svg/sprite.svg#tooltips_01',
            text: this.article.view
          },
          {
            image: '/image/svg/sprite.svg#tooltips_02',
            text: this.article.score
          },
          {
            image: '/image/svg/sprite.svg#tooltips_03',
            text: this.article.countComm
          },
          {
            image: '/image/svg/sprite.svg#tooltips_04',
            text: 0
          },
        ]
      }
    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement, NotificationPopup

    },
    methods:{
      ...HttpClass,
      addRait(){
        if(this.$store.state.data.app.user.auth){
          if(this.$store.state.data.app.user.data.id == this.article.user_id){
            this.notificationMessage = 'Вы не можете голосовать за собственную статью'
          }else{
            let fd = new FormData()
            fd.append('article_id', this.article.id)
            this.sendRequest({
              method: 'POST',
              url: "/score/add",
              data: fd,
              success: this.confirmSuccess,
              error: this.handelErrorResponse
            })

          }
        }
      },
      confirmSuccess(result){
        if(result.data.code == 0){
          this.article.score = result.data.score
          this.notificationMessage = result.data.desc
        }else{
          this.article.score = result.data.score
          this.notificationMessage = result.data.desc
        }
      },
      handelErrorResponse(result){
        console.log(result)
      },


    },
    computed: {


    },
    mounted(){

    }
  }


</script>

<style lang="scss" scoped>
</style>