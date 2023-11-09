<template lang="pug">
DivElement(classCss="structure__item" :class="setClassActive")
  DivElement(classCss="structure__header")
    DivElement(classCss="structure__action, action" v-if="referal.referals.length != 0")
      SpanElement(classCss="action__text" :onclick="setActiveClass")
    DivElement(classCss="structure__counts, counts")
      DivElement(classCss="counts__list")
        DivElement(classCss="counts__item" v-for="(count, index) in referal.counts" :key="index")
          SpanElement(classCss="counts__text") {{count}}
    DivElement(classCss="structure__main")
      DivElement(classCss="structure__media")
        ImageElement(:src="getImage(referal.image)")
      DivElement(classCss="structure__title, title")
        LinkElement(:href="getLink(referal)")
          SpanElement(classCss="title__text") {{getFio(referal)}}
  DivElement(classCss="structure__layout")
    DivElement(classCss="structure__list")
      PartnersChildBlock(:referal="referalChild" v-for="(referalChild, subIndex) in referal.referals" :key="subIndex")


</template>

<script>

import EmptyElement from '@/js/components/elements/Empty'
import DivElement from '@/js/components/elements/Div'
import SpanElement from '@/js/components/elements/Span'
import ImageElement from '@/js/components/elements/Image'
import InputElement from '@/js/components/elements/Input'
import Form from '@/js/components/elements/Form'
import RowElement from '@/js/components/elements/Row'
import LinkElement from '@/js/components/elements/Link'

export default{
  name: 'PartnersChildBlock',
  props: ['referal'],

  data(){

    return{

      user: this.$store.state.data.app.user.data,
      schema: this.$store.state.schemas.partners,
      avatar: '/image/avatar.png',
      activeClass: false
    }





  },
  components:{
    EmptyElement, DivElement, SpanElement, InputElement, Form, RowElement, ImageElement, LinkElement
  },
  methods:{
    getImage(image){
      if(image != null){
        return '/storage/' + image
      }else{
        return this.avatar
      }
    },
    getFio(userData){
      return [userData.first_name, userData.last_name].join(' ')
    },
    setActiveClass(){
      if(_.size(this.referal.referals) > 0){
        this.activeClass = !this.activeClass
      }
    },
    getLink(dataUser)
    {
      return '/id' + dataUser.id
    }

  },
  computed: {
    setClassActive(){
      return this.activeClass ? 'structure__item_active' : ''
    }
  },
  mounted(){

  }
}

</script>