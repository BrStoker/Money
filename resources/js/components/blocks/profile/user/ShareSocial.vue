<template lang="pug">
  DivElement(classCss="socials, mb_m-0")
    DivElement(classCss="socials__title, title")
      SpanElement(classCss="title__text") {{schema.title}}
    DivElement(classCss="socials__list")
      DivElement(classCss="socials__item" v-for="(button, index) in schema.socials" :key="index")
        SocialLinkElement(:href="button.link" classCss="socials__link" :target="button.target" :article="article" :name="button.name")
          SvgElement(:image="button.image")
          SpanElement(classCss="socials__tooltip" v-if="button.hint != ''") {{button.hint}}
</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import SocialLinkElement from '@/js/components/elements/SocialLink'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'



  export default {
    name: 'UserSocialBlock',
    props: ['article'],
    data() {

      return {
        store: this.$store.state.data.usersocial,
        schema: this.$store.state.schemas.shareSocial

    }
    },
    components:{
      DivElement, SocialLinkElement, SvgElement, SpanElement
    },
    computed: {

    },
    methods: 
    {

    },
    mounted(){

    }
  }
</script>

<style lang="scss">

</style>