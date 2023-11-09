<template lang="pug">
WrapperBlock
    RowElement(classCss="row_position__relative, row_position__relative2")
        ContentBlock
            .SearchPage.content
                RowElement(classCss="row__justify_center, row__text_center, row__wrap_wrap")
                TitleElement(classCss="title__size_xl") {{ rows[0] }}
                RowElement(classCss="row__text_center, row__name_index-description") {{ rows[1] }}
                FormElement(:onsubmit="Filter" :onreset="FilterReset" :action="action" :method="method")
                    FilterBlock(:data="data")
                UserBlock(v-for="(user, userKey) in data.users" :key="user.id" :data="user" :userType="data.user_type" )
                RowElement(v-if="emptyList" classCss="row__empty_list" ) {{ rows[2] }}
        AsideBlock(:user="data.user")
    FooterBlock
</template>

<script>

import WrapperBlock from '@/js/blocks/Wrapper'
import FooterBlock from '@/js/blocks/Footer'
import RowElement from '@/js/elements/Row'
import ContentBlock from '@/js/blocks/Content'
import AsideBlock from '@/js/blocks/Aside'
import FormElement from '@/js/elements/Form'
import TitleElement from '@/js/elements/Title'
import FilterBlock from '@/js/blocks/Filter'
import UserBlock from '@/js/blocks/User'
import MethodsPage from '@/js/methods/Page'


export default {
    name: 'Search',
    prop: ['data'],
    data()
    {

        let data = JSON.parse(this.$attrs.data)
      console.log(data)

        return {
            emptyList: false,
            action: location.pathname + location.search,
            method: 'POST',
            limit: data.limit,
            offset: data.offset,
            data: data,
            rows: {
                0: 'Найди своего блогера и сделай свой продукт частью жизни людей!',
                1: 'Блогеры - лидеры мнений. И если они рекомендуют продукт или услугу, часть аудитории заинтересуется и как минимум перейдет по ссылке.',
                2: 'По вашему запросу на данный момент таких блогеров нет. Попробуйте указать другие данные'
            }
        }

    },
    methods: {
        ...MethodsPage,
        ...{
            Filter(e)
            {
                e.preventDefault()
                this.emptyList = false
                this.sendRequest(e.target, this.FilterSuccess, this.FilterError)

            },
            FilterSuccess(result)
            {

                if (result.data.code != undefined && result.data.code == 0)
                {

                    if (result.data.users != undefined)
                    {
                        this.data.users = result.data.users
                    }
                    
                    if(result.data.users == 0)
                    {
                        this.emptyList = true
                    }
                }
                

            },
            FilterError(result) {
                console.error(result)
            },
            FilterReset(e){

                this.Filter(e)

            }
        }
    },
    components: {
        ContentBlock, AsideBlock, WrapperBlock, FooterBlock, UserBlock, FilterBlock,
        RowElement, FormElement, TitleElement
    },
    mounted() {

        this.choises()

    },
    updated() {

        this.choises()
        
    }
}
</script>

<style lang="scss">
</style>