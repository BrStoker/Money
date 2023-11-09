<template lang="pug">
DivElement(classCss="series, align_center, no-wrap")
  DivElement(classCss="series__group, series__group_second")
    DivElement(classCss="media, media_tertiary, mb_m-0")
      ImageElement(:src="getImage(user)")
      DivElement(classCss="media__ico")
        SvgElement(:image="statusIco")
  DivElement(classCss="series__group")
    DivElement(classCss="tooltips, mb-0")
      DivElement(classCss="tooltips__list")
        DivElement(classCss="tooltips__item")
          DivElement(classCss="tooltips__title, title")
            SpanElement(classCss="title__text") {{user.subscribe_date}}
    DivElement(classCss="wysiwyg, mb_m-0")
      h6
        LinkElement(:href="getLink(user)") {{getFio(user)}}
      p {{user.signature}}
</template>

<script>
import EmptyElement from '@/js/components/elements/Empty'
import DivElement from '@/js/components/elements/Div'
import MainElement from '@/js/components/elements/Main'
import SectionElement from '@/js/components/elements/Section'
import SpanElement from '@/js/components/elements/Span'
import RowElement from '@/js/components/elements/Row'
import ImageElement from '@/js/components/elements/Image'
import SvgElement from '@/js/components/elements/Svg'
import LinkElement from '@/js/components/elements/Link'


import AppMethods from '@/js/methods/App'


export default {
  name: 'NotificationLikesPage',
  props: ['user'],
  data(){
    return{
      avatar: '/image/avatar.png',
      statusIco: '/image/svg/sprite.svg#status'

    }
  },
  components:{
    EmptyElement,
    MainElement,
    SectionElement,
    SpanElement,
    DivElement,
    RowElement,
    ImageElement,
    SvgElement,
    LinkElement

  },
  methods:{
    ...AppMethods,
    getImage(userData){
      return userData.image ? '/storage/' + userData.image : this.avatar
    },
    getFio(userData){
      console.log('getFio',userData)
      return [userData.first_name, userData.last_name].join(' ')
    },
    getLink(userData){
      return userData.id ? '/id' + userData.id : '#'
    }
  },
  mounted(){
  }
}


</script>

