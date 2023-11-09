<template lang="pug">
  DivElement(classCss="box__group")
    DivElement(classCss="series, mb-0, align_m-center, no-wrap")
      DivElement(classCss="series__group, series__group_tertiary")
        DivElement(classCss="media, media_tertiary, mb_m-0")
          ImageElement(:src="getAvatar()")
      DivElement(classCss="series__group")
        DivElement(classCss="wysiwyg, mb_m-0")
          h6
            LinkElement(:href="getLink()") {{Fio()}}
          p {{userData.signature}}
      DivElement(classCss="series__group, ml_m-auto")
        DivElement(classCss="buttons")
          DivElement(classCss="buttons__list, buttons__list, direction_m-column, align_m-end")
            DivElement(classCss="buttons__item")
              LinkElement(classCss="btn, btn_tiny, btn_tertiary, btn_m-half-circle" v-if="showButton" :onclick="buttonSubscribe.onclick")
                SvgElement(classCss="btn__ico, m_hide" :image="buttonSubscribe.image")
                SpanElement(classCss="btn__text") {{buttonText}}

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import SvgElement from '@/js/components/elements/Svg'
  import ImageElement from '@/js/components/elements/Image'

  import HttpClass from '@/js/classes/Http'

  export default{
    name: 'TooltipsArticleBlockFooter',
    props: ['article'],
    data(){

      return{
        userData: this.article.article_user,
        currentUser: this.$store.state.data.app.user,
        avatar: '/image/avatar.png',
        isSubscribe: false,
        showButton: false,
        buttonSubscribe:{
          image: '/image/svg/sprite.svg#subscribeArticle',
          text: 'Подписаться',
          onclick: ()=>this.subscribe()
        },
      }
    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement, ImageElement

    },
    methods:{
      ...HttpClass,
      subscribe(){
        let fd = new FormData()
        fd.append('currentUser', this.currentUser.data.id)
        fd.append('userId', this.userData.id)
        fd.append('subscribe', true)
        this.sendRequest({
          method: 'POST',
          url: "/subscribe",
          data: fd,
          success: this.confirmSuccess,
          error: this.handleErrorResponse
        })
      },
      confirmSuccess(result){
        if (result.data.code == 0){
          this.isSubscribe = result.data.subscribe
        }
      },
      handleErrorResponse(result){
        console.error(result)
      },
      unSubscribe(){
        let fd = new FormData()
        fd.append('currentUser', this.currentUser.data.id)
        fd.append('userId', this.userData.id)
        fd.append('subscribe', false)
        this.sendRequest({
          method: 'POST',
          url: "/subscribe",
          data: fd,
          success: this.confirmSuccess,
          error: this.handleErrorResponse
        })
      },

      Fio(){
        return [this.userData.first_name, this.userData.last_name].join(' ')
      },
      getAvatar(){
        return (this.userData && this.userData.image ? this.userData.image : this.avatar );
      },
      getLink(){
        if(this.currentUser.auth){
          if(this.currentUser.data.id == this.userData.id){
            return '/user/profile'
          }else{
            return '/id' + this.userData.id
          }
        }else{
          return '/id' + this.userData.id
        }
      },
      ButtonShow(){
        return true
        if(this.currentUser.data.id == this.userData.id){
          this.showButton = false
        }else{
          this.showButton = true
        }
      },
    },
    computed: {
      buttonText(){
        return this.isSubscribe ? 'Отписаться' : 'Подписаться'
      }

    },
    created(){
      this.ButtonShow()
    },
    mounted(){

      this.isSubscribe = this.article.article_user.isSubscribe



    }

  }


</script>

<style lang="scss" scoped>
</style>