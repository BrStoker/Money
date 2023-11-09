<template lang="pug">
WrapperBlock
  RowElement(classCss="row_position__relative, row_position__relative2")
    ContentBlock
      .Registration.content
        TosterBlock(:data="errors" error="1")
        TosterBlock(:data="success")
        RowElement(classCss="row__text_center, row__width_full, row__wrap_wrap")
          TitleElement(classCss="title__size_xl, title__type_page-title") {{ title }}
          RowElement(classCss="row__justify_center, row__width_full")
            TabsElement(classCss="tabs__hidden_title")
              TabElement(v-for="(tab, index) in tabs" :key="index" :classCss="tab.classCss")
                FormElement(:onsubmit="Register" :action="action" :method="method")
                  RowElement(v-for="(row, key) in tab.inputs" :key="key" :classCss="row.classCss")
                    template(v-for="(input, key2) in row.inputs")
                      InputElement(:input="input" :id="input.id" :classCss="input.classCss" :classWrapper="input.classCss" :type="input.type" :name="input.name" :value="input.value" :required="input.required" :autocomplete="input.autocomplete" :text="input.text" :placeholder="input.label" :checked="input.checked" :dataValue="input.dataValue")
                  RowElement(classCss="row__width_half-full, row__name_centered")
                    ButtonElement(classCss="button__name_auth, button__width_full" type="submit") {{ button }}
    AsideBlock
  FooterBlock
</template>

<script>

import WrapperBlock from '@/js/blocks/Wrapper'
import FooterBlock from '@/js/blocks/Footer'
import RowElement from '@/js/elements/Row'
import ContentBlock from '@/js/blocks/Content'
import AsideBlock from '@/js/blocks/Aside'
import TosterBlock from '@/js/blocks/Toster'
import FormElement from '@/js/elements/Form'
import TitleElement from '@/js/elements/Title'
import TabsElement from '@/js/elements/Tabs'
import TabElement from '@/js/elements/Tab'
import LabelElement from '@/js/elements/Label'
import InputElement from '@/js/elements/Input'
import ButtonElement from '@/js/elements/Button'
import MethodsPage from '@/js/methods/Page'

import HttpClass from '@/js/classes/Http'

export default {
  name: 'RegistratePage',
  data()
  {

    /*let inputs_bloger = [{ classCss: 'row__width_half-full, row__name_centered, row__justify_center', inputs: [
      { text: 'Я также хочу быть рекламодателем', value: '', name: 'type', type: 'checkbox', required: true, dataValue: 1}
    ]}];

    let inputs_advert = [{classCss: 'row__width_half-full, row__name_centered, row__justify_center', inputs: [
      { text: 'Я также хочу рекламировать другие страницы', value: '', name: 'type', type: 'checkbox', required: true, dataValue: 1}
    ]}];*/

    let inputs = [{
      classCss: '', inputs: [
        { label: 'Фамилия', type: 'text', name: 'last_name', classCss: 'input__type_tabs', value: '', autocomplete: "off" },
        { label: 'Имя', type: 'text', name: 'first_name', classCss: 'input__type_tabs', value: '', autocomplete: "off" }
      ]
    }, {
      classCss: '', inputs: [
        { label: 'Email', type: 'email', name: 'email', classCss: 'input__type_tabs', value: '', autocomplete: "off" },
        { label: 'Телефон', type: 'phone', name: 'phone', classCss: 'input__type_tabs', value: '', autocomplete: "off" }
      ]
    }, {
      classCss: 'row__position_relative, row__width_half-full, row__name_centered', inputs: [
        { label: 'Пароль', name: 'password', type: 'password', value: '', autocomplete: "new-password" }
      ]
    }, {
      classCss: 'row__position_relative, row__width_half-full, row__name_centered', inputs: [
        { label: 'Повторите пароль', name: 'password_confirm', type: 'password', value: '', autocomplete: "new-password2" }
      ]
    },
    {
      classCss: 'row__width_half-full, row__name_centered, row__justify_center', inputs: [
        { id: 'type_1', text: 'Я хочу рекламировать другие страницы', value: '', name: 'type[]', type: 'checkbox', dataValue: 0, value: '1'}
      ]
    },
    {
      classCss: 'row__width_half-full, row__name_centered, row__justify_center', inputs: [
        { id: 'type_0', text: 'Я хочу быть рекламодателем', value: '', name: 'type[]', type: 'checkbox', dataValue: 1, value: '1' }
      ]
    }
    ];

    return {
      success: [ ],
      errors: [ ],
      action: location.pathname + location.search,
      method: 'POST',
      title: 'Регистрация',
      button: 'Регистрация',
    }

  },
  components: {
    ContentBlock, AsideBlock, FooterBlock, WrapperBlock, TosterBlock,
    RowElement, FormElement, TitleElement, TabsElement, TabElement, LabelElement, InputElement, ButtonElement,
  },
  methods: {
    // ...MethodsPage,
    ...HttpClass,
    ...{
      Register(e)
      {
        e.preventDefault()
        this.errors = []
        this.success = []

        this.sendRequest(e.target, this.RegisterSuccess, this.RegisterError)
      },
      RegisterSuccess(result)
      {
        if (result.data.code != undefined && result.data.code == 0)
        {
          if (result.data.location)
          {
            location.href = result.data.location
          }
          else
          {
            if(result.data.desc != undefined && result.data.desc.length)
            {
              this.success = result.data.desc
            }
          }
        }
        else
        {
          if(result.data.desc != undefined && result.data.desc.length)
          {
            this.errors = result.data.desc
          }
        }
      },
      RegisterError(result) { console.error(result) }
    }
  }
}

</script>

<style lang="scss">
</style>