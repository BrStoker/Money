<template lang="pug">
  MainElement(classCss="layout__main")
    DivElement(classCss="tabs")
      DivElement(classCss="tabs__header")
        DivElement(classCss="tabs__list")
          DivElement(v-for="(item, index) in tabs" classCss="tabs__item" :class="tabsItemClass(index)" :key="index")
            DivElement(classCss="tabs__title, title" :onclick="()=>tabClick(index)")
              DivElement(classCss="title__text") {{item.title}}
      DivElement(classCss="tabs__body")
        DivElement(classCss="tabs__list")
          DivElement(:key="index" v-for="(item, index) in tabs" :classCss="tabsItemClass(index, [ 'tabs__item' ])")
            template(v-if="index == 0")

            template(v-if="index == 1")

            template(v-if="index == 2")

            template(v-if="index == 3")

            template(v-if="index == 4")

            template(v-if="index == 5")

            template(v-if="index == 6")
              Timer
            template(v-if="index == 7")
              NumberGenerator
</template>

<script>

import DivElement from '@/js/components/elements/Div'
import MainElement from '@/js/components/elements/Main'

import ComputedIndex from '@/js/computed/pages/Index'
import AppMethods from '@/js/methods/App'
import UserDetailMethods from '@/js/methods/pages/UserDetail'
import ComputedFeed from '@/js/computed/pages/Feed'

import NumberGenerator from '@/js/components/pages/User/instruments/NumberGenerator'
import Timer from '@/js/components/pages/User/instruments/Timer'


export default {
    name: 'UserInstrumentsTabBlock',

    data()
    {
        return {
          activeTab: 0,
          user: this.$store.state.data.app.user.data,
          userDetail: this.$store.state.data.app.user_detail,
          tabs: this.$store.state.schemas.instrumentsTab.tabs

        }

    },
    components: {
      DivElement, MainElement, NumberGenerator, Timer
    },
    methods: {
      ...ComputedIndex,
      ...AppMethods,
      ...UserDetailMethods,
      ...ComputedFeed,
      tabClick(index){
        this.activeTab = index
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