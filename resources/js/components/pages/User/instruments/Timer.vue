<template lang="pug">
  SectionElement(classCss="layout__section, section, section_animation")
    DivElement(classCss="section__header")
      DivElement(classCss="wysiwyg, mb-0")
        h3 {{title}}
    DivElement(classCss="section__main")
      DivElement(classCss="section__subsection, subsection")
        DivElement(classCss="subsection__main")
          DivElement(classCss="times")
            DivElement(classCss="times__list")




</template>

<script>

import DivElement from '@/js/components/elements/Div'
import SectionElement from '@/js/components/elements/Section'
import RowElement from '@/js/components/elements/Row'
import SpanElement from '@/js/components/elements/Span'
import InputElement from '@/js/components/elements/Input'
import Notification from '@/js/components/elements/Notification'

import ComputedIndex from '@/js/computed/pages/Index'
import AppMethods from '@/js/methods/App'
import UserDetailMethods from '@/js/methods/pages/UserDetail'
import ComputedFeed from '@/js/computed/pages/Feed'

export default {
    name: 'UserInstrumentsTabBlock',

    data()
    {
        return {
          activeTab: 0,
          user: this.$store.state.data.app.user.data,
          userDetail: this.$store.state.data.app.user_detail,
          title: 'Таймер',
          schema: this.$store.state.schemas.NumberGeneratorSchema,
          generated_number: 0,
          minMin: 0,
          min: 1,
          max: 2,
          error: ''


        }

    },
    components: {
      DivElement, SectionElement, RowElement, InputElement, SpanElement, Notification
    },
    methods: {
      ...ComputedIndex,
      ...AppMethods,
      ...UserDetailMethods,
      ...ComputedFeed,
      generateNumber(e){
        e.preventDefault()
        if(this.error){
          this.error = ''
        }
        let min = document.querySelector('input[name="min_number"]')
        let max = document.querySelector('input[name="max_number"]')
        if(min.value > max.value){
          this.error = 'Максимальное число должно быть больше минимального'
        }else if(min.value == max.value){
          this.error = 'Числа не могут быть одинаковыми'
        }

        if(this.error == ''){

          this.generated_number = Math.floor(Math.random() * (max.value) + min.value)

        }else{
          this.$store.commit('updateNotificationMessage', this.error)

        }

      }
    },
  created(){

  },
  computed:
      {


      },
  mounted(){
  },
}
</script>

<style lang="scss">
</style>