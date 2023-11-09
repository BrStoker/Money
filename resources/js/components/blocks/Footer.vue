<template lang="pug">
  DivElement(classCss="layout__footer, footer")
    RowElement
      DivElement(classCss="col, col_6, col_mob-12")
        DivElement(classCss="footer__copyright, copyright")
          span(class="copyright__text") {{data.text}}
      DivElement(classCss="col, col_6, col_mob-12")
        DivElement(classCss="footer__photographer, photographer, text_m-end")
          span(class="photographer__text") {{data.picText}}


</template>

<script>

import DivElement from '@/js/components/elements/Div'
import RowElement from '@/js/components/elements/Row'
import ImageElement from '@/js/components/elements/Image'
import LinkElement from '@/js/components/elements/Link'
import PopupElement from '@/js/components/elements/Popup'
import { mapState } from 'vuex'

export default {
  name: 'FooterBlock',
  data()
  {
    return {
      text: '',
      data: this.$store.state.data.footer

    }
  },

  created(){

  },
  components: {
    DivElement,
    RowElement,
    ImageElement,
    LinkElement,
    PopupElement
  },
  methods:
  {
    getYear() {
      return new Date().getFullYear()
    }
  },

}
</script>

<style lang="scss">
</style>