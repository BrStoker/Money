<template lang="pug">
  aside(class="layout__menu menu")
    DivElement(classCss="menu__list")
        DivElement(:classCss="'menu__item' + (linkActive(link.link)?', menu__item_current':'')" v-for="(link, index) in data.menu" :key="index" v-if="showLink(link)")
          LinkElement(classCss="menu__link" :href="getLink(link)")
            DivElement(classCss="menu__media")
              svg(class="menu__ico menu__ico_main")
                use(:xlink:href="link.ico_main")
              svg(class="menu__ico menu__ico_second")
                use(:xlink:href="link.ico_second")
            DivElement(classCss="menu__title")
              SpanElement(class="title__text") {{ link.name }}
            DivElement(classCss="menu__preview" v-if="link.countMessage && countedMessages != 0" style="margin-left: 10px")
              DivElement(classCss="menu__count, count")
                SpanElement(classCss="count__text") {{countedMessages}}

</template>

<script>

import DivElement from '@/js/components/elements/Div'
import RowElement from '@/js/components/elements/Row'
import LinkElement from '@/js/components/elements/Link'
import ImageElement from '@/js/components/elements/Image'
import BurgerElement from '@/js/components/elements/Burger'
import CloseElement from '@/js/components/elements/Close'
import SpanElement from '@/js/components/elements/Span'
import HttpClass from '@/js/classes/Http'

export default {
  name: 'AsideBlock',
  data()
  {
    return {
      userData: this.$store.state.data.app.user,
      data: this.$store.state.data.aside,
      countedMessages: 0
    }
  },
  computed:{

  },
  methods: {
    ...HttpClass,
    showAuthForm(e) {
      e.preventDefault()
      this.$store.commit('closeModalLogin');
    },
    linkActive(link){
      if(window.location.pathname === link){
        return true
      }
      return false
    },

    isMobile() {

      if(screen.availWidth < 960) {

        this.mobile = true

      } else {

        this.mobile = false

      }

    },
    getLink(link){
      if(link.name == 'Профиль'){
        return link.link + this.userData.data.id
      }else{
        return link.link
      }
    },
    showLink(link){
      if (link.needAuth){
        return this.userData.auth
      }else{
        return true
      }
    },
    countMessages(){

      if (this.userData.auth){
        let sendData = new FormData()

        this.sendRequest({
          method: 'POST',
          url: '/count-messages',
          data: sendData,
          success: this.countSuccess,
          error: this.countError
        })
      }else{
        return ''
      }
    },
    countSuccess(result){
      if(result.data.status == true){
        this.countedMessages = result.data.countMessages
      }
    },
    countError(result){
      console.error(result)
    }
  },
  components: {
    DivElement,
    RowElement, LinkElement, ImageElement, CloseElement, BurgerElement, SpanElement
  },
  created()
  {
    this.isMobile()


  },
  mounted() {
    this.countMessages()
    window.addEventListener('resize', () => {
      this.isMobile()
    })

  }
}
</script>

<style lang="scss">
</style>