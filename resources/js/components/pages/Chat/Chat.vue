<template lang="pug">
    DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
        HeaderBlock
        WrapperBlock
            AsideBlock
            ContentBlock
                BreadcrumbsBlock(mainText="Лента" :link="link" secondText="Чат")
                MainElement(classCss="layout__main")
                    SectionElement(classCss="layout__section, section, section_animation")
                        DivElement(classCss="section__main")
                            DivElement(classCss="tabs")
                                DivElement(classCss="tabs__header")
                                    DivElement(classCss="tabs__list")
                                        DivElement(v-for="(item, index) in tabs" classCss="tabs__item" :class="tabsItemClass(index)" :key="index")
                                            DivElement(classCss="tabs__title, title" :onclick="() => { item.click(index) }")
                                                SpanElement(classCss="title__text") {{item.text}}
                                            DivElement(classCss="tabs__count, count")
                                                SpanElement(classCss="count__text") {{'23'}}
                                DivElement(classCss="tabs__body")
                                    DivElement(classCss="tabs__list")
                                        DivElement(:key="index" v-for="(item, index) in tabs" :classCss="tabsItemClass(index, [ 'tabs__item' ])")
                                            template(v-if="index == 0")
                                                ChatDialogs
                                            template(v-if="index == 1")
                                                ChatChannels
                                            template(v-if="index == 2")
                                                ChatBots
                                            template(v-if="index == 3")
                                                ChatQuest
                FooterBlock
        ModalLayout

</template>

<script>
import DivElement from '@/js/components/elements/Div'
import MainElement from '@/js/components/elements/Main'
import SectionElement from '@/js/components/elements/Section'
import SpanElement from '@/js/components/elements/Span'

import HeaderBlock from '@/js/components/blocks/Header'
import WrapperBlock from '@/js/components/blocks/Wrapper'
import AsideBlock from '@/js/components/blocks/Aside'
import FooterBlock from '@/js/components/blocks/Footer'
import ContentBlock from '@/js/components/blocks/Content'
import BreadcrumbsBlock from '@/js/components/blocks/Breadcrumbs'
import ModalLayout from '@/js/components/blocks/ModalLayout'

import ChatDialogs from '@/js/components/pages/Chat/ChatDialogs'
import ChatChannels from '@/js/components/pages/Chat/ChatChanels'
import ChatQuest from '@/js/components/pages/Chat/ChatQuest'
import ChatBots from '@/js/components/pages/Chat/ChatBots'

import ComputedIndex from '@/js/computed/pages/Index'
import AppMethods from '@/js/methods/App'
import ComputedFeed from '@/js/computed/pages/Feed'
import FeedEvents from '@/js/events/pages/Feed'


export default {
    name: "Chat",
    props: ['data'],
    data(){
        return {
            activeTab: 0,
            link: '/feed',
            user: this.$store.state.data.app.user.data,
            tabs: [
                {
                    text: 'Диалоги',
                    click: this.handlerTab
                },
                {
                    text: 'Каналы',
                    click: this.handlerTab
                },
                {
                    text: 'Боты',
                    click: this.handlerTab
                },
                {
                    text: 'Вопрос дня',
                    click: this.handlerTab
                },
            ]

        }
    },
    components:{
        ChatQuest,
        ChatBots,
        DivElement,
        MainElement,
        SectionElement,
        SpanElement,
        HeaderBlock,
        WrapperBlock,
        AsideBlock,
        FooterBlock,
        ContentBlock,
        BreadcrumbsBlock,
        ModalLayout,
        ChatDialogs,
        ChatChannels,
    },
    methods:{
        ...ComputedIndex,
        ...AppMethods,
        ...ComputedFeed,
        ...FeedEvents,
        connectServer(){
            let conn = new WebSocket(process.env.VUE_APP_URL + ':' + process.env.VUE_APP_PORT+ '?user_id=' + this.user.id)
            conn.onopen = (e)=>{
                this.getUserlist(conn, this.user.id)
            }
        },
        getUserlist(conn, user_id){
            let data = {
                user_id: user_id,
                type: 'user_list'
            }
            conn.send(JSON.stringify(data))
        }
    },
    created(){
        this.appendUserToStore(this.data)
        this.connectServer()
    }

}
</script>

<style scoped>

</style>
