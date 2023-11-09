<template lang="pug">
DivElement(classCss="section__subsection, subsection")
  NotificationPopup(v-if="notificationMessage" :message="notificationMessage")
  DivElement(classCss="subsection__main")
    DivElement(classCss="row, align_m-center")
      DivElement(classCss="col, col_12" v-if="user.auth")
        DivElement(classCss="tooltips, tooltips_second")
          DivElement(classCss="tooltips__list")
            DivElement(classCss="tooltips__item" v-for="(button, index) in votes" :key="index")
              DivElement(:classCss="button.class")
                DivElement(classCss="tooltips__media" v-if="button.type == 'image'" :onclick="button.click")
                  SvgElement(:image="button.image")
                DivElement(classCss="tooltips__title, title")
                  SpanElement(classCss="title__text" v-if="button.name == 'like'") {{scoreLike}}
                  SpanElement(classCss="title__text" v-else) {{scoreDislike}}
            DivElement(classCss="tooltips__item, tooltips__item_dinamic")
              DivElement(classCss="tooltips__title, title")
                SpanElement(classCss="title__text") {{'Сохранить:'}}
              DivElement(classCss="tooltips__media" :class="{'favorite': isFavorite }" :onclick="addToFavorite")
                SvgElement(:image="images.favorite")
      DivElement(classCss="col, col_6, col_mob-12")
        ShareSocial(:article="articleData")
      DivElement(classCss="col, col_6, col_mob-12")
        DivElement(classCss="wysiwyg, text_m-end, mb_m-0")
          p
            SpanElement(:onclick="showComplain" classCss="headline__text") {{'Пожаловаться'}}

</template>

<script>

import DivElement from '@/js/components/elements/Div'
import RowElement from '@/js/components/elements/Row'
import LinkElement from '@/js/components/elements/Link'
import SvgElement from '@/js/components/elements/Svg'
import SpanElement from '@/js/components/elements/Span'
import ShareSocial from '@/js/components/blocks/profile/user/ShareSocial'
import HttpClass from '@/js/classes/Http'
import NotificationPopup from '@/js/components/elements/Notification'

export default {
  name: 'ArticleFooterBlock',
  props: ['article'],
  data()
  {
    return {
      user: this.$store.state.data.app.user,
      articleData: this.article,
      isFavorite: false,
      scoreLike: 0,
      scoreDislike: 0,
      votes: [
        {
          image: '/image/svg/sprite.svg#like-up',
          class: 'tooltips__item, tooltips__item_dinamic',
          type: 'image',
          name: 'like',
          click: ()=>this.addLike()
        },
        {
          image: '/image/svg/sprite.svg#like-bottom',
          class: 'tooltips__item, tooltips__item_dinamic',
          type: 'image',
          name: 'dislike',
          click: ()=> this.addDislike()
        },

      ],
      notificationMessage: '',
      images:{
         favorite: '/image/svg/sprite.svg#favorite'
      },

    }
  },
  computed:{

  },
  methods: {
    ...HttpClass,
    addToFavorite(){
      let fd = new FormData()
      fd.append('key', 'add')
      fd.append('article_id', this.articleData.id)
      this.sendRequest({
        method: 'POST',
        url: '/article/favorite',
        data: fd,
        success: this.addSuccess,
        error: this.handlerErrorResponse
      })
    },
    addSuccess(result){
      if (result.data.code == 0){
        this.isFavorite = result.data.isFavorite
        this.notificationMessage = result.data.desc
      }
    },
    handlerErrorResponse(result){
      console.error(result)
    },
    getFavorite(){
      let sendData = new FormData()
      sendData.append('article_id', this.article.id)
      this.sendRequest({
        method: 'POST',
        url: '/get-favorite',
        data: sendData,
        success: this.addSuccess,
        error: this.handelErrorResponse
      })

    },
    getScore(){
      let sendData = new FormData()
      sendData.append('article_id', this.article.id)
      this.sendRequest({
        method: 'POST',
        'url': '/get-score',
        data: sendData,
        success: this.scoreSuccess,
        error: this.scoreError
      })


    },
    scoreSuccess(result){
      if(result.data.code === 0){
        this.scoreLike = result.data.scores.like
        this.scoreDislike = result.data.scores.disLike
      }
    },
    scoreError(result){
      console.error(result)
    },
    addLike(){
      let sendData = new FormData()
      sendData.append('article_id', this.article.id)
      this.sendRequest({
        method: 'POST',
        url: '/score/add',
        data: sendData,
        success: this.likeSuccess,
        error: this.likeError
      })
    },
    addDislike(){
      let sendData = new FormData()
      sendData.append('article_id', this.article.id)
      this.sendRequest({
        method: 'POST',
        url: '/score/remove',
        data: sendData,
        success: this.likeSuccess,
        error: this.likeError
      })
    },
    likeSuccess(result){
      if(result.data.code == 0){
        if(result.data.like == true){
          this.scoreLike = result.data.score
        }else{
          this.scoreDislike = result.data.score
        }
        if(this.notificationMessage.length > 0){
          this.notificationMessage = ''
        }
        this.notificationMessage = result.data.desc
      }else{
        if(result.data.like == true){
          this.scoreLike = result.data.score
        }else{
          this.scoreDislike = result.data.score
        }
        if(this.notificationMessage){
          this.notificationMessage = ''
        }
        this.notificationMessage = result.data.desc
      }
    },
    likeError(result){
      console.error(result)
    },
    showComplain(){
      if(this.$store.state.data.app.user.auth){
        this.$store.commit('closeComplainForm')
      }else{
        this.$store.commit('closeModalLogin')
      }

    }


  },
  components: {
    DivElement, SvgElement, SpanElement,
    RowElement, LinkElement, ShareSocial,
    NotificationPopup
  },
  created()
  {
    if(this.user.auth){
      this.getScore()
    }

  },
  mounted() {
    if(this.$store.state.data.app.user.auth){
      this.getFavorite()
    }

  }
}
</script>

<style lang="scss">
</style>