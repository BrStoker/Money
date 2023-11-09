<template lang="pug">
  DivElement(classCss="tooltips, tooltips_tertiary")
    NotificationPopup(v-if="notificationMessage" :message="notificationMessage")
    DivElement(classCss="tooltips__list")
      DivElement(:classCss="tooltip.parentClass" v-for="(tooltip, index) in tooltips" :key="index")
        DivElement(:classCss="tooltip.class" v-if="tooltip.image == '' && tooltip.text != ''")
          SpanElement(:classCss="tooltip.classChild") {{tooltip.text}}
        DivElement(classCss="tooltips__media" v-else-if="tooltip.image != '' && user.auth" :onclick="tooltip.click" :class="{'favorite': isFavorite }")
          SvgElement(:image="tooltip.image")

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import SvgElement from '@/js/components/elements/Svg'
  import HttpClass from '@/js/classes/Http'
  import NotificationPopup from '@/js/components/elements/Notification'

  export default{
    name: 'TooltipsArticleBlock',
    props: ['article'],
    data(){

      return{
        user: this.$store.state.data.app.user,
        articleData: this.article,
        isFavorite: this.article.favorite,
        notificationMessage: '',
        tooltips: [
          {
            image: '',
            text: new Date(this.article.created_at).toISOString().slice(0,10).split('-').reverse().join('.'),
            parentClass: 'tooltips__item',
            class: 'tooltips__title, title',
            classChild: 'title__text'
          },
          {
            image: '',
            text: this.getGroups(),
            parentClass: 'tooltips__item',
            class: 'tooltips__tag, tag',
            classChild: 'tag__text'
          },
          {
            image: '/image/svg/sprite.svg#favorite',
            text: '',
            parentClass: 'tooltips__item, tooltips__item_dinamic, ml_auto',
            class: 'tooltips__item, tooltips__item_dinamic, ml_auto',
            classChild: '',
            click: ()=>this.addFavorite()

          },
        ]
      }
    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement, NotificationPopup

    },
    methods:{
      ...HttpClass,
      addFavorite() {
        let sendData = new FormData()
        sendData.append('article_id', this.articleData.id)
        this.sendRequest({
          method: 'POST',
          url: "/article/favorite",
          data: sendData,
          success: this.confirmSuccess,
          error: this.handlerErrorResponse
        });


      },
      confirmSuccess(result){
        if(result.data.code == 0){
          if(result.data.favorite){
            if(this.$store.state.data.app.user.data.id == this.$store.state.data.app.user_detail.id){
              this.$store.commit('addFavoriteToStore', result.data.favorite)
            }
          }
          this.isFavorite = result.data.isFavorite
          if(this.notificationMessage.length > 0){
            this.notificationMessage = ''
          }
          this.notificationMessage = result.data.desc
        }
      },
      handlerErrorResponse(result) {
        console.error(result)
      },
      getGroups(){
        var groups = []
        var resultString
        if(this.article.articleGroups){
          this.article.articleGroups.forEach(group=>{
            groups.push(group.name)
          })
          resultString = groups.map(function(item) {
            return item;
          }).join(', ')

        }
        return resultString

      },
      getFavorite(){
        let sendData = new FormData()
        sendData.append('article_id', this.article.id)
        this.sendRequest({
          method: 'GET',
          url: '/article/favorite',
          data: sendData,
          success: this.confirmSuccess,
          error: this.handlerErrorResponse
        })

      }

    },
    mounted(){
      this.getGroups()

    },
    created(){
      if(this.$store.state.data.app.user.auth){
        this.getFavorite()
      }



    }

  }


</script>

<style lang="scss" scoped>
</style>