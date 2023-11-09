<template lang="pug">
  DivElement(classCss="series, no-wrap")
    NotificationPopup(v-if="notificationMessage" :message="notificationMessage")
    DivElement(classCss="series__group, series__group_tertiary")
      LinkElement(:href="getUserLink(comment.comment_user)")
        DivElement(classCss="media, media_tertiary, mb_m-0")
          ImageElement(:src="getAvatar(comment.comment_user.image)")
    DivElement(classCss="series__group")
      DivElement(classCss="tooltips, mb_m-0")
        DivElement(classCss="tooltips__list")
          DivElement(classCss="tooltips__item")
            LinkElement(:href="getUserLink(comment.comment_user)")
              DivElement(classCss="tooltips__headline, headline")
                SpanElement(classCss="headline__text") {{getFio(comment.comment_user)}}
          DivElement(classCss="tooltips__item" v-if="isAuthor(comment.comment_user)")
            DivElement(classCss="tooltips__tag, tag")
              SpanElement(classCss="tag__text") {{'Автор статьи'}}
          DivElement(classCss="tooltips__item")
            DivElement(classCss="tooltips__title, title")
              SpanElement(classCss="title__text") {{getDate(comment)}}
      DivElement(classCss="wysiwyg")
        p(v-html="comment.text")
      DivElement(classCss="tooltips")
        DivElement(classCss="tooltips__list")
          DivElement(classCss="tooltips__item")
            DivElement(classCss="tooltips__title, title")
              LinkElement(classCss="title__text" href="#" :onclick="()=>addAnswer(comment)" v-if="user.auth") {{'Ответить'}}
          DivElement(classCss="tooltips__item, tooltips__item_dinamic")
            DivElement(classCss="tooltips__item" v-if="user.auth")
              DivElement(classCss="tooltips__media" :onclick="()=> setLike()" :class="{'favorite': isLiked}")
                SvgElement(:image="images.like")
              DivElement(classCss="tooltips__title, title" :class="commentNegative()")
                SpanElement(classCss="title__text") {{countLikes}}
          DivElement(classCss="tooltips__item, tooltips__item_dinamic")
            DivElement(classCss="tooltips__item" v-if="user.auth")
              DivElement(classCss="tooltips__media" :onclick="()=>setDisLike()")
                SvgElement(:image="images.dislike")
              DivElement(classCss="tooltips__title, title" :class="commentNegative()")
                SpanElement(classCss="title__text") {{countDislikes}}
      DivElement(classCss="medias" v-if="comment.childrens")
        DivElement(classCss="medias__list")
          DivElement(classCss="media" v-for="(response, subIndex) in comment.childrens" :key="subIndex")
            ImageElement(:src="getAvatar(response.comment_user.image)")
        DivElement(classCss="medias__title, title")
          SpanElement(classCss="title__text") {{getCountResponse(comment)}}
      ChildComment(:comment="child" :article="article" v-for="(child, subindex) in comment.childrens" :key="subindex")

</template>

<script>

import DivElement from '@/js/components/elements/Div'
import ImageElement from '@/js/components/elements/Image'
import SpanElement from '@/js/components/elements/Span'
import LinkElement from '@/js/components/elements/Link'
import SvgElement from '@/js/components/elements/Svg'
import AppMethods from '@/js/methods/App'
import NotificationPopup from '@/js/components/elements/Notification'
import HttpClass from '@/js/classes/Http'

export default {
  name: 'ChildComment',
  props: ['comment', 'article'],
  data()
  {
    return {

      avatar: '/image/avatar.png',
      user: this.$store.state.data.app.user,
      isLiked: false,
      countLikes: 0,
      schema: this.$store.state.schemas.addComment,
      countDislikes: 0,
      notificationMessage: '',
      images:{
        like: '/image/svg/sprite.svg#like-up',
        dislike: '/image/svg/sprite.svg#like-bottom'
      },


    }
  },
  components: {
    DivElement,
    ImageElement,
    SpanElement,
    LinkElement,
    SvgElement,
    NotificationPopup

  },
  computed:{
    haveResponse() {
      return this.comment.childrens.length > 0
    }

  },
  methods: {
    ...AppMethods,
    ...HttpClass,
    getAvatar(image){
      return image == null ? this.avatar : '/storage/' + image
    },
    getUserLink(commentUser){
      return commentUser.id ? '/id' + commentUser.id : '#'
    },
    getFio(commentUser){
      return [commentUser.first_name, commentUser.last_name].join(' ')
    },
    isAuthor(commentUser){
      return commentUser.id === this.article.user_id
    },
    getDate(comment){
      let date = new Date(comment.created_at)
      return ("0" + date.getDate()).slice(-2) + "." + ("0" + (date.getMonth() + 1)).slice(-2) + ", " + ("0" + date.getHours()).slice(-2) + ":" + ("0" + date.getMinutes()).slice(-2)
    },
    setLike(){
        let sendData = new FormData()
        sendData.append('comment_id', this.comment.comment_id)
        this.sendRequest({
          method: 'POST',
          url: '/comment/like',
          data: sendData,
          success: this.responseSuccces,
          error: this.responseError
        })
    },
    setDisLike(){
        let sendData = new FormData()
        sendData.append('comment_id', this.comment.comment_id)
        this.sendRequest({
          method: 'POST',
          url: '/comment/dislike',
          data: sendData,
          success: this.responseSuccces,
          error: this.responseError
        })
    },
    getCountResponse(commentData){
      if(Object.keys(commentData.childrens).length == 0){
        return ''
      }else{
        return this.declineWord(Object.keys(commentData.childrens).length)
      }
    },
    commentNegative(){
      if(this.countLikes > 0){
        return 'success'
      }else if(this.countLikes < 0){
        return 'error'
      }else{
        return ''
      }
    },
    declineWord(number) {
      let word = 'ответ'
      let absoluteNumber = Math.abs(number);
      if (absoluteNumber >= 11 && absoluteNumber <= 19) {
        return word + 'ов';
      }

      let remainder = absoluteNumber % 10;
      if (remainder === 1) {
        return [number, word].join(' ');
      } else if (remainder >= 2 && remainder <= 4) {
        return [number, word + 'а'].join(' ');
      } else {
        return [number, word + 'ов'].join(' ');
      }
    },
    addAnswer(e){

      let items = []
      Object.keys(e).forEach(item=>{
        let value = e[item]
        if(item == 'text'){
          value = e[item]
        }
        let name = item
        let obj = {name, value}
        items.push(obj)
      })

      this.$store.commit('updateAnswerComment', items)

      this.scrollToElement()
    },
    scrollToElement() {

      let element = document.getElementsByName('text');

      if(element){
        element[0].scrollIntoView({behavior: 'smooth', block: 'center'})
      }
    },
    getLikes(){
      let sendData = new FormData()
      sendData.append('comment_id', this.comment.comment_id)
      this.sendRequest({
          method: 'POST',
          url: '/comment/get-like',
          data: sendData,
          success: this.responseSuccces,
          error: this.responseError
      })
    },
    responseSuccces(result){

      if(result.data.code == 0){
        this.countLikes = result.data.counts.likes
        this.countDislikes = result.data.counts.disLikes
      }else{
        if(result.data.code == 1){
          if(this.notificationMessage.length > 0){
            this.notificationMessage = ''
          }
          this.notificationMessage = result.data.error
        }
      }
    },
    responseError(result){
      console.error(result)
    }


  },

  mounted(){
    this.getLikes()
  }
}
</script>

<style lang="scss">
</style>