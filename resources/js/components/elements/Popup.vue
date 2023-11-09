<template lang="pug">
.popup(v-if="getOpen" :class="classPopup")
  .popup__wrapper(:class="classPopupWrapper" )
    .popup__close(v-if="!getInline" @click="closePopup") &#215;
    .popup__body(:class="classPopupBody")
      template(v-if="getDataLength")
        span(v-html="getData")
      template(v-else)
        slot
</template>

<script>

import ComputedElement from '@/js/computed/elements/Index'

export default {
  name: 'PopupElement',
  prop: ['classCss', 'classWrapper', 'classBody', 'element', 'inline', 'data'],
  computed:
  {
    ...{
      getDataLength()
      {
        if(this.$attrs.data != undefined)
        {
          return this.$attrs.data.length
        }
      },
      getData()
      {
        if(this.$attrs.data != undefined)
        {
          return this.$attrs.data
        }
      },
      getOpen()
      {
        if(this.$attrs.element.open != undefined)
        {
          return parseInt(this.$attrs.element.open)
        }
      },
      getInline()
      {
        if(this.$attrs.inline != undefined)
        {
          return parseInt(this.$attrs.inline)
        }
      },
      classPopup()
      {

        let classCss = (this.classCss != undefined ? this.classCss.replaceAll(' ','').replaceAll(',',' ') : '')

        let inline;

        if(this.$attrs.inline != undefined)
        {
          inline = parseInt(this.$attrs.inline)
        }

        return classCss + (!inline ? 'popup__type_modal' : 'popup__type_inline')

      },
      classPopupWrapper()
      {

        let classCss = (this.classWrapper != undefined ? this.classWrapper.replaceAll(' ','').replaceAll(',',' ') : '')

        let inline;

        if(this.$attrs.inline != undefined)
        {
          inline = parseInt(this.$attrs.inline)
        }

        return classCss + (!inline ? 'popup__wrapper__type_modal' : 'popup__wrapper__type_inline')

      },
      classPopupBody()
      {

        let classCss = (this.classBody != undefined ? this.classBody.replaceAll(' ','').replaceAll(',',' ') : '')

        let inline;

        if(this.$attrs.inline != undefined)
        {
          inline = parseInt(this.$attrs.inline)
        }

        return classCss + (!inline ? 'popup__body__type_modal' : '')

      }
    }
  },
  methods:{
    closePopup(e)
    {

      e.preventDefault()
      e.stopPropagation()

      if(this.$attrs.element.open != undefined && parseInt(this.$attrs.element.open) == 1)
      {
        
        this.$attrs.element.open = 0

        document.body.classList.remove('body__state_hidden')

      }

    }
  }
}

</script>

<style lang="scss">

</style>