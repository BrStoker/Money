<template lang="pug">
  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Люди" :link="link" :secondText="fio()")
        UserCardDetail
        FooterBlock
    ModalLayout
</template>

<script>

import DivElement from '@/js/components/elements/Div'

import HeaderBlock from '@/js/components/blocks/Header'
import WrapperBlock from '@/js/components/blocks/Wrapper'
import AsideBlock from '@/js/components/blocks/Aside'
import FooterBlock from '@/js/components/blocks/Footer'
import ContentBlock from '@/js/components/blocks/Content'
import BreadcrumbsBlock from '@/js/components/blocks/Breadcrumbs'
import UserCardDetail from '@/js/components/pages/User/UserCardDetail'

import ComputedIndex from '@/js/computed/pages/Index'
import ModalLayout from '@/js/components/blocks/ModalLayout'
import AppMethods from '@/js/methods/App'
import UserDetailMethods from '@/js/methods/pages/UserDetail'

export default {
    name: 'UserDetail',
    props: ['data'],
    data()
    {
        this.appendUserToStore(this.data)
        return {

          user: this.$store.state.data.app.user.data,
          userDetail: this.$store.state.data.app.user_detail,
          fio: ()=>this.Fio(),
          link: '/people'


        }

    },
    components: {
      DivElement, HeaderBlock, AsideBlock, FooterBlock, WrapperBlock,  ContentBlock, BreadcrumbsBlock, ModalLayout, UserCardDetail
    },
    methods: {
      ...ComputedIndex,
      ...AppMethods,
      ...UserDetailMethods,
      setCookie(){
        if(this.user.auth){
          if(this.user.id !== this.userDetail.id){
            let name = 'userRef'

            let days = 30

            let value = window.location.protocol + '//' + window.location.hostname + '/ref' + this.userDetail.id

            let expires = "";

            if (days) {
              let date = new Date();

              date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

              expires = "; expires=" + date.toUTCString();

            }
            let existingCookie = document.cookie.split(';').find(function(cookie) {

              return cookie.trim().startsWith(name + "=");

            })

            if (existingCookie){

              document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/"

            }else{

              document.cookie = `${name}=${encodeURIComponent(value)}${expires}; path=/`

            }
          }
        }
      }
    },
  created(){
    // this.appendUserToStore(this.data)
  },
  computed:
      {

      },
  mounted(){
    this.setCookie()
  },
}
</script>

<style lang="scss">
</style>