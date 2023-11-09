<template lang="pug">
  DivElement(classCss="header__authorization, authorization"  v-if="!userAuth")
    DivElement(classCss="authorization__login, modal-init")
      LinkElement(:onclick="showLoginForm" classCss="btn, btn_secondary, btn_m-circle")
        SvgElement(classCss="btn__ico" :image="data.header.images.button")
        SpanElement(classCss="btn__text") {{data.header.text.button}}
  DivElement(classCss="header__menu, menu" v-else)
    DivElement(classCss="menu__list")
      DivElement(classCss="menu__item" style="z-index: 99")
        LinkElement(:href="linkNotification")
          DivElement(classCss="menu__preview")
            DivElement(classCss="menu__media")
              SvgElement(:image="notification.image")
            DivElement(classCss="menu__count, count" v-if="notification.text")
              SpanElement(classCss="count__text") {{notification.text}}
      DivElement(classCss="menu__item, dropdown-init")
        DivElement(classCss="menu__preview")
          DivElement(classCss="menu__avatar")
            ImageElement(:src="getAvatar")
          DivElement(classCss="menu__action")
            SvgElement(classCss="btn__ico" :image="imageMenu")
        DivElement(classCss="menu__dropdown, layout__dropdown, dropdown")
          DivElement(classCss="dropdown__list")
            DivElement(classCss="dropdown__item" v-for="(item, index) in menu" :key="index")
              LinkElement(classCss="dropdown__link" :href="item.link")
                DivElement(classCss="dropdown__media")
                  SvgElement(classCss="btn__ico" :image="item.image")
                DivElement(classCss="dropdown__title, title")
                  SpanElement(classCss="title__text") {{item.text}}


</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import ImageElement from '@/js/components/elements/Image'
  import SvgElement from '@/js/components/elements/Svg'

  export default{
    name: 'LoginButton',
    props: ['user'],

    data(){

      return{
        UserData: this.$store.state.data.app.user,
        dataNotification: this.$store.state.data.app.notifications,
        notification: {
          image: '/image/svg/sprite.svg#notification',
          text: this.getCountNotification()
        },
        linkNotification: '/user/notification',
        data: this.$store.state.data,
        schemas: this.$store.state.schemas.header,
        link: '/logout',
        logoutText: 'Выйти',
        avatar: '/image/avatar.png',
        imageMenu: '/image/svg/sprite.svg#arrowBottom',
        menu: [
          {
            image: '/image/svg/sprite.svg#menu__ico01',
            link: '/id' + this.$store.state.data.app.user.data.id,
            text: 'Профиль'
          },
          {
            image: '/image/svg/sprite.svg#menu__ico02',
            link: '#',
            text: 'Посмотреть статистику'
          },
          {
            image: '/image/svg/sprite.svg#menu__ico03',
            link: '/logout',
            text: 'Выйти'
          },
        ]

      }
    },
    components:{
      DivElement, LinkElement, SpanElement, ImageElement, SvgElement

    },
    methods:{
      showLoginForm(){
        this.$store.commit('closeModalLogin')
      },
      profile(){
        this.$router.push('/user/profile');
      },
      getCountNotification(){
        if(this.$store.state.data.app.notifications.counts){
          return this.$store.state.data.app.notifications.counts.all > 99 ? '99+' : this.$store.state.data.app.notifications.counts.all
        }else{
          return '';
        }

      }
    },
    computed: {

      getAvatar() {
        return (this.UserData && this.UserData.data.image ? '/storage/' + this.UserData.data.image : this.avatar );
      },
      userAuth(){
        return this.UserData.auth
      },
    },

    mounted(){
      // console.log(this.show)
    }

  }


</script>

<style lang="scss" scoped>
</style>