<template lang="pug">
DivElement(classCss="wysiwyg" v-if="this.referals && !step.inputs")
  DivElement(classCss="structure")
    DivElement(classCss="structure__item" :class="getClassActive")
      DivElement(classCss="structure__header")
        DivElement(classCss="structure__action, action" :onclick="setActive" v-if="user.referals.length != 0")
          SpanElement(classCss="action__text")
        DivElement(classCss="structure__counts, counts" v-if="user.referals")
          DivElement(classCss="counts__list")
            DivElement(classCss="counts__item" v-for="(count, index) in user.counts" :key="index")
              SpanElement(classCss="counts__text") {{count}}
        DivElement(classCss="structure__main")
          DivElement(classCss="structure__media")
            ImageElement(:src="getImage(user.image)")
          DivElement(classCss="structure__title, title")
            SpanElement(classCss="title__text") {{getFio(user)}}
      DivElement(classCss="structure__layout")
        DivElement(classCss="structure__list")
          PartnerChildBlock(:referal="referal" v-for="(referal, subIndex) in user.referals" :key="subIndex")

</template>

<script>

import EmptyElement from '@/js/components/elements/Empty'
import DivElement from '@/js/components/elements/Div'
import SpanElement from '@/js/components/elements/Span'
import ImageElement from '@/js/components/elements/Image'
import InputElement from '@/js/components/elements/Input'
import Form from '@/js/components/elements/Form'
import RowElement from '@/js/components/elements/Row'
import PartnerChildBlock from '@/js/components/pages/Partners/PartnersChild'

export default{
  name: 'PartnerParentBlock',
  props: ['referals', 'step'],
  data(){


    return{

      user: this.$store.state.data.app.user.data,
      schema: this.$store.state.schemas.partners,
      avatar: '/image/avatar.png',
      active: false

    }
  },
  components:{
    EmptyElement, DivElement, SpanElement, InputElement, Form, RowElement, ImageElement, PartnerChildBlock
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
    setActive(){
      if(_.size(this.user.referals) > 0){
        this.active = !this.active
      }
    },

  },
  computed: {
    getClassActive(){
      if(this.active){
        return 'structure__item_active'
      }else{
        return ''
      }
    }
  },
  mounted(){
    console.log(this.user.counts)
  }
}

</script>