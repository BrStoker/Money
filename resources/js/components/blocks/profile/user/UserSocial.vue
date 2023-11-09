<template lang="pug">
  DivElement(classCss="socials")
    DivElement(classCss="socials__list, justify-m-end, w-100")
      DivElement(classCss="socials__item" v-for="(button, index) in store.socials" :key="index" v-if="button.show")
        LinkElement(:href="button.link" classCss="socials__link" :target="button.target")
          SvgElement(:image="button.image")
          SpanElement(classCss="socials__tooltip" v-if="button.hint != ''") {{button.hint}}
</template>

<script>


  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'

  import AppMethods from '@/js/methods/App'
  import MethodsUser from '@/js/methods/pages/User'


  export default {
    name: 'UserSocialBlock',
    props: ['data'],
    data() {

      return {
        User: this.$store.state.data.app.user.data,
        store: this.$store.state.data.usersocial,
        socials: this.getSocials(this.$store.state.data.app.user_detail)

    }
    },
    components:{
      DivElement, LinkElement, SvgElement, SpanElement
    },
    computed: {

    },
    methods: 
    {
      ...AppMethods,
      ...MethodsUser
    },
    mounted(){
      this.appendSocialtoData(this.socials)
    }
  }
</script>

<style lang="scss">

</style>