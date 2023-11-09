<template lang="pug">
DivElement(classCss="tabs")
    DivElement(classCss="tabs__header")
        DivElement(classCss="tabs__list, tabs__list_second")
            DivElement(v-for="(tab, index) in tabs" :key="index" :classCss="tab.class" :class="tabsItemClass(index)")
                DivElement(classCss="tabs__title, title" v-if="tab.text")
                    SpanElement(classCss="title__text") {{tab.text}}
                DivElement(classCss="tabs__count, count" v-if="tab.text")
                    SpanElement(classCss="count__text") {{'23'}}
                DivElement(classCss="tabs__media" v-if="tab.image" :onclick="tab.click")
                    SvgElement(:image="tab.image")
    DivElement(classCss="tabs__body")
        DivElement(classCss="tabs__list")
            DivElement(:key="index" v-for="(item, index) in tabs" :classCss="tabsItemClass(index, [ 'tabs__item' ])")

</template>

<script>

import DivElement from '@/js/components/elements/Div'
import SpanElement from '@/js/components/elements/Span'
import FeedEvents from '@/js/events/pages/Feed'
import ComputedFeed from '@/js/computed/pages/Feed'
import SvgElement from '@/js/components/elements/Svg'

import io from 'socket.io-client';

export default {
    name: "ChatDialogs",

    data(){
        return{
            activeTab: 0,
            user: this.$store.state.data.app.user.data,
            tabs: [
                {
                    text: 'Все',
                    click: this.handlerTab,
                    class: 'tabs__item',
                    dropdown: [
                        {
                            text: 'Редактировать'
                        },
                        {
                            text: 'Удалить'
                        }
                    ]
                },
                {
                    click: ()=>this.showAddForm(),
                    class: 'tabs__item, tabs__item_second, modal-init',
                    image: '/image/svg/sprite.svg#plus'
                }
            ]
            // schema: this.$store.state.schemas.chatDialogs
        }
    },
    components:{
        DivElement,
        SpanElement,
        SvgElement

    },
    methods:{
        ...FeedEvents,
        ...ComputedFeed,
        showAddForm(){
            this.$store.commit('showChatAddFolder')
        },

    },
    created(){
        // this.connectServer()
    },
    mounted(){
        console.log(this.schema)
    }
}
</script>

<style scoped>

</style>
