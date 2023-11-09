<template lang="pug">
  header(class="layout__header header")
    DivElement(classCss="header__logo, logo")
      LinkElement(href="/" classCss="logo__link" :title="store.text.logo")
        ImageElement(:src="store.images.logo" :alt="store.text.logo")
    LoginButton

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import ImageElement from '@/js/components/elements/Image'
  import LinkElement from '@/js/components/elements/Link'
  import LoginButton from '@/js/components/blocks/LoginButton'


  export default {
    name: 'HeaderBlock',

    data() {

      return {

        userData: this.$store.state.data.app.user,
        store: this.$store.state.data.header,
        schemas: this.$store.state.schemas.header
      }

    },
    components: {
      DivElement, ImageElement, LinkElement, LoginButton
    },
  }

</script>

<style lang="scss">
</style>