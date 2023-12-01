<template lang="pug">
  SectionElement(classCss="layout__section, section, section_animation")
    DivElement(classCss="section__header")
      DivElement(classCss="wysiwyg, mb-0")
        h3 {{title}}
        Notification(v-if="error")
    DivElement(classCss="section__main")
      DivElement(classCss="section__subsection, subsection")
        DivElement(classCss="subsection__main")
          RowElement
            DivElement(classCss="col, col_6, col_mob-12")
              DivElement(classCss="formular, mb_m-0")
                DivElement(classCss="formular__main")
                  RowElement
                    DivElement(v-for="(step, index) in schema.steps" :key="index" :classCss="step.class")
                      DivElement(classCss="form__group, form__group_second, group")
                        DivElement(classCss="group__header" v-if="step.header")
                          DivElement(classCss="wysiwyg")
                            h6 {{step.header.title}}
                        DivElement(classCss="group__main")
                          InputElement(v-for="(input, inputIndex) in step.inputs" :key="inputIndex" :data="input" :onclick="generateNumber")
                    DivElement(classCss="error" v-if="error")
                      SpanElement {{error}}
            DivElement(classCss="col, col_6, col_mob-12")
              DivElement(classCss="form__group, form__group_second, group, h-100")
                DivElement(classCss="group__counter, counter, h-100")
                  SpanElement(classCss="counter__text") {{generated_number}}



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
          title: 'Генератор чисел',
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