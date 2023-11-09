<template lang="pug">
WrapperBlock
  RowElement(classCss="row_position__relative, row_position__relative2")
    ContentBlock
      .Profile.content
        TosterBlock(:data="errors" error="1")
        TosterBlock(:data="success")
        RowElement(classCss="row__width_full, row__wrap_wrap")
          TitleElement(classCss="title__size_xl, title__type_page-title") {{titlePage}}
          FormElement(:onsubmit="Save" :action="action" :method="method")
            RowElement(v-for="(row, key) in rows" :key="key" classCss="row__name_cloud, row__wrap_wrap")
              TitleElement(classCss="title__size_xs") {{row.title}}
              template(v-for="(element, key2) in row.inputs")
                InputElement(:input="element" :classWrapper="element.classWrapper" :type="element.type" :name="element.name" :value="element.value" :required="element.required" :autocomplete="element.autocomplete" :text="element.text" :placeholder="element.label" :labelPostion="element.labelPostion" :labelClass="element.labelClass")
            RowElement(classCss="row__width_half-full, row__name_centered")
              ButtonElement(classCss="button__width_full" type="submit") {{ button }}
    AsideBlock(:user="data.user")
  FooterBlock
</template>

<script>

import WrapperBlock from '@/js/blocks/Wrapper'
import FooterBlock from '@/js/blocks/Footer'
import ContentBlock from '@/js/blocks/Content'
import AsideBlock from '@/js/blocks/Aside'
import FormElement from '@/js/elements/Form'
import RowElement from '@/js/elements/Row'
import TitleElement from '@/js/elements/Title'
import InputElement from '@/js/elements/Input'
import ButtonElement from '@/js/elements/Button'
import MethodsPage from '@/js/methods/Page'
import TosterBlock from '@/js/blocks/Toster'


export default {
  name: 'UserDetailStatistic',
  prop: ['data'],
  data()
  {

    let data = JSON.parse(this.$attrs.data)

    return {
      success: [],
      errors: [],
      data: data,
      action: location.pathname + location.search,
      method: 'POST',
      button: 'Сохранить',
      titlePage: 'Статистика по кампании c ',
      rows: [
        {
          title: 'Статистика сторис', classCss: '',
          inputs: [
            { type: 'text', label: 'Просмотров', classCss: '', classWrapper: 'input__name_profile-statistic', name: 'storis[view]' },
            { type: 'text', label: 'Ответило', classWrapper: 'input__name_profile-statistic', name: 'storis[send]' },
            { type: 'text', label: 'Поделилось', classWrapper: 'input__name_profile-statistic', name: 'storis[share]' },
            { type: 'text', label: 'Переходов по ссылке', classWrapper: 'input__name_profile-statistic', name: 'storis[go]' }
          ]
        },
        {
          title: 'Статистика поста',
          inputs: [
            { type: 'text', label: 'Охваченные аккаунты', classWrapper: 'input__name_profile-statistic', name: 'post[coverage]' },
            { type: 'text', label: 'Вовлеченные аккаунты', classWrapper: 'input__name_profile-statistic', name: 'post[involve]' },
            { type: 'text', label: 'Лайков', classWrapper: 'input__name_profile-statistic', name: 'post[like]' },
            { type: 'text', label: 'Комментариев', classWrapper: 'input__name_profile-statistic', name: 'post[comment]' },
            { type: 'text', label: 'Поделилось', classWrapper: 'input__name_profile-statistic', name: 'post[share]' },
            { type: 'text', label: 'Сохранило', classWrapper: 'input__name_profile-statistic', name: 'post[save]' }
          ]
        },
        {
          title: 'Статистика рилс',
          inputs: [
            { type: 'text', label: 'Охваченные аккаунты', classWrapper: 'input__name_profile-statistic', name: 'rils[coverage]' },
            { type: 'text', label: 'Вовлеченные аккаунты', classWrapper: 'input__name_profile-statistic', name: 'rils[involve]' },
            { type: 'text', label: 'Лайков', classWrapper: 'input__name_profile-statistic', name: 'rils[like]' },
            { type: 'text', label: 'Комментариев', classWrapper: 'input__name_profile-statistic', name: 'rils[comment]' },
            { type: 'text', label: 'Поделилось', classWrapper: 'input__name_profile-statistic', name: 'rils[share]' },
            { type: 'text', label: 'Сохранило', classWrapper: 'input__name_profile-statistic', name: 'rils[save]' }
          ]
        }
      ]
    }

  },
  components: {
    ContentBlock, AsideBlock, FooterBlock, WrapperBlock, TosterBlock,
    RowElement, FormElement, TitleElement, InputElement, ButtonElement
  },
  methods: {
    ...MethodsPage,
    ...{
      getUserDetailFio(data)
      {

        if (data.user_detail.first_name != undefined && data.user_detail.last_name != undefined)
        {

          return [this.title, this.data.user.first_name, this.data.user.last_name].join(' ')

        }

      },
      Save(e)
      {
        e.preventDefault()
        this.success = []
        this.errors = []
        this.sendRequest(e.target, this.SaveSuccess, this.SaveError)
      },
      SaveSuccess(result)
      {
        if (result.data.code != undefined && result.data.code == 0)
        {
          if (result.data.location)
          {
            location.href = result.data.location
          } 
          else
          {
            //alert(result.data.desc);
            if (result.data.desc != undefined && result.data.desc.length)
            {
              this.success = result.data.desc
            }
          }
        } 
        else
        {
          //alert(result.data.desc);
          if (result.data.desc != undefined && result.data.desc.length)
            {
              this.errors = result.data.desc
            }
        }
      },
      SaveError(result) { console.error(result) }
    }
  }
}

</script>

<style lang="scss">
</style>