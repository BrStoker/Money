<template lang="pug">
  DivElement(:classCss="'modal__layout, modal__layout_tiny, modal__layout_authorization' + (data.app.isLoginShown?', modal__layout_active':'')")
    DivElement(classCss="modal__action, action")
      SvgElement(:image="data.header.blocks.login.images.close" :onclick="showForm")
    DivElement(classCss="modal__main")
      DivElement(classCss="tabs")
        DivElement(classCss="tabs__header")
          DivElement(classCss="tabs__list")
            DivElement(:key="index" v-for="(item, index) in tabs" classCss="tabs__item" :class="tabsItemClass(index)")
              DivElement(classCss="tabs__title, title" :onclick="() => { item.click(index) }")
                SpanElement(classCss="title__text") {{ item.text }}
        DivElement(classCss="tabs__body")
          DivElement(classCss="tabs__list")
            DivElement(:key="index" v-for="(item, index) in tabs" :classCss="tabsItemClass(index, [ 'tabs__item' ])")
              template(v-if="index == 0")
                Login
              template(v-else-if="index == 1")
                Register
</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import SvgElement from '@/js/components/elements/Svg'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import RowElement from '@/js/components/elements/Row'
  import Form from '@/js/components/elements/Form'

  import Login from '@/js/components/blocks/Auth/Login'
  import Register from '@/js/components/blocks/Auth/Register'

  import ComputedLoginForm from '@/js/computed/blocks/LoginForm'
  import MethodsLoginForm from '@/js/methods/blocks/LoginForm'
  import EventLoginForm from '@/js/events/blocks/LoginForm'
  import Utils from '@/js/classes/Util'

  export default{

    name: 'LoginFormBlock',

    data() {

      let data = this.$store.state.data,
        schema = this.$store.state.schemas

      return {
        activeTab: 0,
        errors: '',
        tabs: [ {
          text: data.header.blocks.login.text.title,
          click: this.handlerHeaderTab
        }, {
          text: data.header.blocks.register.text.title,
          click: this.handlerHeaderTab
        } ],
        data: data,
        schema: schema
      }

    },
    methods:{
      ...ComputedLoginForm,
      ...EventLoginForm,
      ...MethodsLoginForm,
      ...Utils

    },
    components:{
      DivElement, LinkElement, SpanElement, SvgElement, RowElement, Form,
      Login, Register
    },
  }


</script>

<style lang="scss" scoped>
</style>