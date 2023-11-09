<template lang="pug">
WrapperBlock
    RowElement(classCss="row_position__relative, row_position__relative2")
        ContentBlock
            .Bloger.content
                TitleElement(classCss="title__size_xl, title__type_page-title") {{ titlePage }}
                RowElement(classCss="row__name_cloud, row__wrap_wrap")
                    FormElement(:onsubmit="Save" :action="action" :method="method")
                        TitleElement(classCss="title__size_sm") {{ title_form }}
                        ScoresBlock(classCss="review__score-item" set="1")
                        RowElement(classCss="row__wrap_wrap")
                            TextareaElement(name="value" styleCss="height:150px;" required="required")
                        RowElement(classCss="row__width_half-full, row__name_centered")
                            ButtonElement(classCss="button__width_full" type="submit") {{ send }}
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
    import TextareaElement from '@/js/elements/Textarea'
    import ButtonElement from '@/js/elements/Button'
    import ScoresBlock from '@/js/blocks/Scores'
    import MethodsPage from '@/js/methods/Page'
    
    export default {
        name: 'UserDetailReview',
        prop: ['data'],
        data()
        {

            let data = JSON.parse(this.$attrs.data)

            return {
                data: data,
                action: location.pathname + location.search,
                method: 'POST',
                title: 'Отзыв о',
                title_form : 'Статистика сторис',
                send: 'Отправить'
            }

            
        },
        components:{
            ContentBlock, AsideBlock, FooterBlock, WrapperBlock, ScoresBlock,
            RowElement, FormElement, TitleElement, TextareaElement, ButtonElement
        },
        computed:
        {
            titlePage()
            {
                return [this.title, this.data.user.first_name, this.data.user.last_name].join(' ')
            }
        },
        methods: {
            ...MethodsPage,
            ...{
                Save(e)
                {
                    e.preventDefault()
                    this.sendRequest(e.target, this.SaveSuccess, this.SaveError)
                },
                SaveSuccess(result)
                {
                    if(result.data.code != undefined && result.data.code == 0){
                        if(result.data.location){
                            location.href = result.data.location
                        } else {
                            alert(result.data.desc);
                        }
                    } else {
                        alert(result.data.desc);
                    }
                },
                SaveError(result) { console.error(result) }
            }
        }
    }
</script>

<style lang="scss">
</style>