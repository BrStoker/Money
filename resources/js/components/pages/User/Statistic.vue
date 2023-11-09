<template lang="pug">
WrapperBlock
  RowElement(classCss="row_position__relative, row_position__relative2")
    ContentBlock
      .Profile.content
        TosterBlock(:data="errors" error="1")
        TosterBlock(:data="success")
        RowElement(classCss="row__width_full, row__wrap_wrap")
          LinkElement(classCss="link__type_back" href="/user/profile") {{ link_back }}
          RowElement(classCss="row__justify_center, row__width_full")
            TabsElement(:classCss="tabsClass")
              TabElement(v-for="(tab, key) in tabs" :key="key" :title="tab.title" :classCss="tab.classCss")
                FormElement(:onsubmit="Save" :action="action" :method="method")
                  InputElement(classWrapper="input__type_hidden" :input="user_type" type="hidden" :name="user_type.name" :value="user_type.value")
                  InputElement(classWrapper="input__type_hidden" :input="tab.platform" type="hidden" :name="tab.platform.name" :value="tab.platform.value")
                  TitleElement(classCss="title__size_xs" v-if="mobile == false") {{ rows[0] }}
                  RowElement(classCss="row__justify_center,row__name_profile-detail" v-if="mobile == false") {{ rows[1] }}
                  RowElement(v-for="(row, rowKey) in tab.inputs[0]" :key="rowKey" :classCss="row.classCss")
                    template(v-for="(element) in prepareRowsInputs(row.inputs, data, user_type.value, tab.platform.code)")
                      div(v-if="element.type == 'view'" :class="element.classCss") {{ element.text }}
                      SelectElement(v-else-if="element.type == 'select'" :name="element.name" :classCss="element.classCss" :classWrapper="element.classWrapper")
                        OptionElement(v-for="(option, optionKey) in element.values" :key="optionKey" :classCss="option.classCss" :value="option.value" :selected="option.selected" :disabled="option.disabled" ) {{option.text}}
                      InputElement(v-else :input="element" :classCss="element.classCss" :classWrapper="element.classWrapper" :type="element.type" :name="element.name" :value="element.value" :required="element.required" :autocomplete="element.autocomplete" :text="element.text" :placeholder="element.label" :labelPostion="element.labelPostion" :labelClass="element.labelClass")
                  TitleElement(classCss="title__size_xs, title__name_profile-detail" v-if="mobile == false") {{ rows[2] }}
                  RowElement(v-for="(row, rowKey) in tab.inputs[1]" :key="rowKey+' '" :classCss="row.classCss")
                    template(v-for="(inputs) in row.inputs")
                      template(v-for="(element) in prepareRowsInputs(inputs, data, user_type.value, tab.platform.code)")
                        div(v-if="element.type == 'view'" :class="element.classCss") {{ element.text }}
                        SelectElement(v-else-if="element.type == 'select'" :name="element.name" :classCss="element.classCss" :classWrapper="element.classWrapper")
                          OptionElement(v-for="(option, optionKey) in element.values" :key="optionKey" :classCss="option.classCss" :value="option.value" :selected="option.selected" :disabled="option.disabled" ) {{option.text}}
                        InputElement(v-else :input="element" :classCss="element.classCss" :classWrapper="element.classWrapper" :type="element.type" :name="element.name" :value="element.value" :required="element.required" :autocomplete="element.autocomplete" :text="element.text" :placeholder="element.label" :labelPostion="element.labelPostion" :labelClass="element.labelClass")
                  RowElement(classCss="row__width_half-full, row__name_centered, row__name_user-statistic, row__name_user-statistic-button")
                    ButtonElement(classCss="button__width_full" type="submit") {{ button }}
                  RowElement(classCss="row__width_half-full, row__name_centered, row__name_user-statistic, row__name_user-statistic-button")
                    LinkElement(classCss="link__type_button, link__width_full, link__name_view-blogers" href="/") {{ link_index }}
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
import TabsElement from '@/js/elements/Tabs'
import TabElement from '@/js/elements/Tab'
import LabelElement from '@/js/elements/Label'
import InputElement from '@/js/elements/Input'
import ButtonElement from '@/js/elements/Button'
import SelectElement from '@/js/elements/Select'
import OptionElement from '@/js/elements/Option'
import MethodsPage from '@/js/methods/Page'
import TosterBlock from '@/js/blocks/Toster'
import LinkElement from '@/js/elements/Link'


export default {
  name: 'UserStatistic',
  prop: ['data'],
  data()
  {

    let data = JSON.parse(this.$attrs.data)

    let inputs = undefined

    if(this.isMobile() == false) {

      inputs = [
        [{
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xl', type: 'text', name: 'coverage', label: 'Охваченные аккаунты', prepareObject: 'user_platforms', prepareName: 'coverage' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', name: 'subscribers_age_18_24', text: '18-24', labelPostion: 'left', labelClass: 'label__position_left label__name_age', prepareObject: 'user_platforms', prepareName: 'subscribers_age_18_24' },
            { classWrapper: 'select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-md', type: 'select', name: 'country_id', label: 'Основная страна', values: this.getOptions(data, 'country', { text: 'Основная страна', value: '' }), prepareObject: 'user_platforms', prepareName: 'country_id' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xs', type: 'text', name: 'country_percent', label: '%', prepareObject: 'user_platforms', prepareName: 'country_percent' }
          ]
        }, {
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xl', type: 'text', name: 'involved', label: 'Вовлеченные аккаунты', prepareObject: 'user_platforms', prepareName: 'involved' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', name: 'subscribers_age_25_34', text: '25-34', labelPostion: 'left', labelClass: 'label__position_left label__name_age', prepareObject: 'user_platforms', prepareName: 'subscribers_age_25_34' },
            { classWrapper: 'select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-md', type: 'select', name: 'city1_id', label: 'Город 1', values: this.getOptions(data, 'city', { text: 'Город 1', value: '' }), prepareObject: 'user_platforms', prepareName: 'city1_id' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xs', type: 'text', name: 'city1_percent', label: '%', prepareObject: 'user_platforms', prepareName: 'city1_percent' }
          ]
        }, {
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xl', type: 'text', name: 'subscribers', label: 'Количество подписчиков', prepareObject: 'user_platforms', prepareName: 'subscribers' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', name: 'subscribers_age_35_44', text: '35-44', labelPostion: 'left', labelClass: 'label__position_left label__name_age', prepareObject: 'user_platforms', prepareName: 'subscribers_age_35_44' },
            { classWrapper: 'select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-md', type: 'select', name: 'city2_id', label: 'Город 2', values: this.getOptions(data, 'city', { text: 'Город 2', value: '' }), prepareObject: 'user_platforms', prepareName: 'city2_id' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xs', type: 'text', name: 'city2_percent', label: '%', prepareObject: 'user_platforms', prepareName: 'city2_percent' }
          ]
        }, {
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'input__wrapper__width_auto, input__name_profile-md, input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-md', type: 'text', name: 'subscribers_men', label: '%', prepareObject: 'user_platforms', prepareName: 'subscribers_men' },
            { classWrapper: 'input__wrapper__width_auto, input__name_profile-md, input__name_sex-women, input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-md, input__name_sex-women', type: 'text', name: 'subscribers_women', label: '%', prepareObject: 'user_platforms', prepareName: 'subscribers_women' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', name: 'subscribers_age_45_54', text: '45-54', labelPostion: 'left', labelClass: 'label__position_left label__name_age', prepareObject: 'user_platforms', prepareName: 'subscribers_age_45_54' },
            { classWrapper: 'select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-md', type: 'select', name: 'city3_id', label: 'Город 3', values: this.getOptions(data, 'city', { text: 'Город 3', value: '' }), prepareObject: 'user_platforms', prepareName: 'city3_id' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xs', type: 'text', name: 'city3_percent', label: '%', prepareObject: 'user_platforms', prepareName: 'city3_percent' }
          ]
        }, {
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_sex-description', text: 'Мужчины' },
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_sex-description', text: 'Женщины' },
          ]
        }
        ], [{
          classCss: 'row__name_user-statistic',
          inputs: [[
            { type: 'view', text: 'Пост', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_field-description' },
            { classWrapper: 'select__wrapper_width_auto, select__name_profile, select__name_profile-sm, select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-sm', type: 'select', label: 'Пост', name: 'post_count', values: this.getOptions(data, 'post_count'), prepareObject: 'user_platforms', prepareName: 'post_count' },
            { classWrapper: 'input__wrapper__width_auto, input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', label: 'Цена', name: 'post_price', prepareObject: 'user_platforms', prepareName: 'post_price' }
          ], [
            { type: 'view', text: 'Рилс', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_field-description' },
            { classWrapper: 'select__wrapper_width_auto, select__name_profile, select__name_profile-sm, select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-sm', type: 'select', label: 'Рилс', name: 'rils_count', values: this.getOptions(data, 'rils_count'), prepareObject: 'user_platforms', prepareName: 'rils_count' },
            { classWrapper: 'input__wrapper__width_auto, input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', label: 'Цена', name: 'rils_price', prepareObject: 'user_platforms', prepareName: 'rils_price' }
          ]]
        }, {
          classCss: 'row__name_user-statistic',
          inputs: [[
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_price-description', text: '* Если у вас в пакете идет от 2ух постов' },
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_price-description', text: '* Если у вас в пакете идет от 2ух постов' }
          ]]
        }, {
          classCss: 'row__name_user-statistic',
          inputs: [[
            { type: 'view', text: 'Сторис', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_field-description' },
            { classWrapper: 'select__wrapper_width_auto, select__name_profile, select__name_profile-sm, select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-sm', type: 'select', label: 'Сторис', description: '* Если у вас в пакете идет от 2ух постов', name: 'storis_count', values: this.getOptions(data, 'storis_count'), prepareObject: 'user_platforms', prepareName: 'storis_count' },
            { classWrapper: 'input__wrapper__width_auto', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', label: 'Цена', name: 'storis_price', prepareObject: 'user_platforms', prepareName: 'storis_price' }
          ], [
            { type: 'view', text: 'Обзор', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_field-description' },
            { classWrapper: 'input__wrapper__width_auto', classCss: 'input__name_profile, input__name_profile-xxl, select__wrapper__name_user-statistic', type: 'text', label: '', name: 'other', prepareObject: 'user_platforms', prepareName: 'other' },
            { classWrapper: 'input__wrapper__width_auto', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', label: 'Цена', name: 'other_price', prepareObject: 'user_platforms', prepareName: 'other_price' }
          ]]
        }, {
          classCss: 'row__name_user-statistic',
          inputs: [[
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic' ,classCss: 'profile-detail_text__type_price-description', text: '* Если у вас в пакете идет от 2ух постов' },
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_price-description', text: '* Что-то свое (например: бартер, обзор)' }
          ]]
        }]
      ];
    } else {

      inputs = [
        [{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'title title__size_xs title__name_profile-detail-main', text: 'Общая статистика' }
          ]
        },{
          classCss: 'row__name_user-statistic, row__wrap_wrap',
          inputs: [
            { classWrapper: 'input__wrapper__name_user-statistic, input__wrapper____name_profile-xl', classCss: 'input__name_profile, input__name_profile-xl', type: 'text', name: 'coverage', label: 'Охваченные аккаунты', prepareObject: 'user_platforms', prepareName: 'coverage' },
            { classWrapper: 'input__wrapper__name_user-statistic, input__wrapper____name_profile-xl', classCss: 'input__name_profile, input__name_profile-xl', type: 'text', name: 'involved', label: 'Вовлеченные аккаунты', prepareObject: 'user_platforms', prepareName: 'involved' },
            { classWrapper: 'input__wrapper__name_user-statistic, input__wrapper____name_profile-xl', classCss: 'input__name_profile, input__name_profile-xl', type: 'text', name: 'subscribers', label: 'Количество подписчиков', prepareObject: 'user_platforms', prepareName: 'subscribers' },
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'input__wrapper__width_auto, input__name_profile-md, input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-md', type: 'text', name: 'subscribers_men', label: '%', prepareObject: 'user_platforms', prepareName: 'subscribers_men' },
            { classWrapper: 'input__wrapper__width_auto, input__name_profile-md, input__name_sex-women, input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-md, input__name_sex-women', type: 'text', name: 'subscribers_women', label: '%', prepareObject: 'user_platforms', prepareName: 'subscribers_women' },
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_sex-description', text: 'Мужчины' },
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_sex-description', text: 'Женщины' },
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-md', type: 'select', name: 'country_id', label: 'Основная страна', values: this.getOptions(data, 'country', { text: 'Основная страна', value: '' }), prepareObject: 'user_platforms', prepareName: 'country_id' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xs', type: 'text', name: 'country_percent', label: '%', prepareObject: 'user_platforms', prepareName: 'country_percent' }
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-md', type: 'select', name: 'city1_id', label: 'Город 1', values: this.getOptions(data, 'city', { text: 'Город 1', value: '' }), prepareObject: 'user_platforms', prepareName: 'city1_id' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xs', type: 'text', name: 'city1_percent', label: '%', prepareObject: 'user_platforms', prepareName: 'city1_percent' }
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-md', type: 'select', name: 'city2_id', label: 'Город 2', values: this.getOptions(data, 'city', { text: 'Город 2', value: '' }), prepareObject: 'user_platforms', prepareName: 'city2_id' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xs', type: 'text', name: 'city2_percent', label: '%', prepareObject: 'user_platforms', prepareName: 'city2_percent' }
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-md', type: 'select', name: 'city3_id', label: 'Город 3', values: this.getOptions(data, 'city', { text: 'Город 3', value: '' }), prepareObject: 'user_platforms', prepareName: 'city3_id' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xs', type: 'text', name: 'city3_percent', label: '%', prepareObject: 'user_platforms', prepareName: 'city3_percent' }
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-md', type: 'select', name: 'city3_id', label: 'Город 3', values: this.getOptions(data, 'city', { text: 'Город 3', value: '' }), prepareObject: 'user_platforms', prepareName: 'city3_id' },
            { classWrapper: 'input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-xs', type: 'text', name: 'city3_percent', label: '%', prepareObject: 'user_platforms', prepareName: 'city3_percent' }
          ]
        },
        {
          classCss: 'row__justify_center,row__name_profile-detail',
          inputs: [
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'title title__size_xs title__name_profile-detail', text: 'Основной возраст' }
          ]
        }, {
          classCss: 'row__name_user-statistic',
          inputs: [
            { classWrapper: 'input__wrapper__name_user-statistic, input__wrapper____name_profile-age', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', name: 'subscribers_age_18_24', text: '18-24', labelPostion: 'left', labelClass: 'label__position_left label__name_age', prepareObject: 'user_platforms', prepareName: 'subscribers_age_18_24' },
            { classWrapper: 'input__wrapper__name_user-statistic, input__wrapper____name_profile-age', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', name: 'subscribers_age_25_34', text: '25-34', labelPostion: 'left', labelClass: 'label__position_left label__name_age', prepareObject: 'user_platforms', prepareName: 'subscribers_age_25_34' },
            { classWrapper: 'input__wrapper__name_user-statistic, input__wrapper____name_profile-age', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', name: 'subscribers_age_35_44', text: '35-44', labelPostion: 'left', labelClass: 'label__position_left label__name_age', prepareObject: 'user_platforms', prepareName: 'subscribers_age_35_44' },
            { classWrapper: 'input__wrapper__name_user-statistic, input__wrapper____name_profile-age', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', name: 'subscribers_age_45_54', text: '45-54', labelPostion: 'left', labelClass: 'label__position_left label__name_age', prepareObject: 'user_platforms', prepareName: 'subscribers_age_45_54' },
          ]
        } ,{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'title title__size_xs title__name_profile-detail', text: 'Цена' }
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', text: 'Пост', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_field-description' },
            { classWrapper: 'select__wrapper_width_auto, select__name_profile, select__name_profile-sm, select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-sm', type: 'select', label: 'Пост', name: 'post_count', values: this.getOptions(data, 'post_count'), prepareObject: 'user_platforms', prepareName: 'post_count' },
            { classWrapper: 'input__wrapper__width_auto, input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', label: 'Цена', name: 'post_price', prepareObject: 'user_platforms', prepareName: 'post_price' }
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_price-description', text: '* Если у вас в пакете идет от 2ух постов' },
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', text: 'Рилс', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_field-description' },
            { classWrapper: 'select__wrapper_width_auto, select__name_profile, select__name_profile-sm, select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-sm', type: 'select', label: 'Рилс', name: 'rils_count', values: this.getOptions(data, 'rils_count'), prepareObject: 'user_platforms', prepareName: 'rils_count' },
            { classWrapper: 'input__wrapper__width_auto, input__wrapper__name_user-statistic', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', label: 'Цена', name: 'rils_price', prepareObject: 'user_platforms', prepareName: 'rils_price' }
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_price-description', text: '* Если у вас в пакете идет от 2ух постов' }
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', text: 'Сторис', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_field-description' },
            { classWrapper: 'select__wrapper_width_auto, select__name_profile, select__name_profile-sm, select__wrapper__name_user-statistic', classCss: 'select_choises, select__name_profile, select__name_profile-sm', type: 'select', label: 'Сторис', description: '* Если у вас в пакете идет от 2ух постов', name: 'storis_count', values: this.getOptions(data, 'storis_count'), prepareObject: 'user_platforms', prepareName: 'storis_count' },
            { classWrapper: 'input__wrapper__width_auto, input__wrapper__name_prices', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', label: 'Цена', name: 'storis_price', prepareObject: 'user_platforms', prepareName: 'storis_price' }
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic' ,classCss: 'profile-detail_text__type_price-description', text: '* Если у вас в пакете идет от 2ух постов' },
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', text: 'Обзор', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_field-description' },
            { classWrapper: 'input__wrapper__width_auto, input__wrapper__name_prices', classCss: 'input__name_profile, input__name_profile-xxl, select__wrapper__name_user-statistic', type: 'text', label: '', name: 'other', prepareObject: 'user_platforms', prepareName: 'other' },
            { classWrapper: 'input__wrapper__width_auto input__wrapper__name_prices', classCss: 'input__name_profile, input__name_profile-sm', type: 'text', label: 'Цена', name: 'other_price', prepareObject: 'user_platforms', prepareName: 'other_price' }
          ]
        },{
          classCss: 'row__name_user-statistic',
          inputs: [
            { type: 'view', classWrapper: 'view__wrapper__name_user-statistic', classCss: 'profile-detail_text__type_price-description', text: '* Что-то свое (например: бартер, обзор)' }
          ]
        }
        ]
      ];
    }

    if (typeof data.user_platforms != 'undefined')
    {

      var tabs = []
      var tabsClass = 'tab__name_user-statistic'
      var count = 0;

      for (let index in data.user_platforms)
      {

        if (data.user_platforms[index][data.user_type] != undefined)
        {

          let element = data.user_platforms[index][data.user_type]

          if (typeof element.link != 'undefined' && element.link.length && typeof data.platforms[index] != 'undefined')
          {

            let platform = data.platforms[index]

            tabs.push({
              title: platform.value,
              classCss: (!count ? 'tab__state_active' : ''),
              platform: { name: 'platform_id', code: platform.code, value: platform.id },
              inputs: this.arrayAssign(inputs)
            });

            count++;
          }

        }

      }

    }

    switch (tabs.length)
    {
      case 2: tabsClass = ', tabs__count-tab_two'; break
      case 3: tabsClass = ', tabs__count-tab_three'; break
      case 4: tabsClass = ', tabs__count-tab_four'; break
      case 5: tabsClass = ', tabs__count-tab_five'; break
    }

    return {
      mobile: false,
      success: [],
      errors: [],
      data: data,
      user_type: {
        name: 'user_type',
        value: data.user_type
      },
      action: location.pathname + location.search,
      method: 'POST',
      button: 'Сохранить',
      link_index: 'Посмотреть блогеров',
      link_back: 'Назад',
      rows: { 0: 'Общая статистика', 1: 'Основной возраст подписчиков:', 2: 'Цена' },
      tabs: tabs,
      tabsClass: tabsClass
    }

  },
  components: {
    ContentBlock, AsideBlock, FooterBlock, WrapperBlock, TosterBlock,
    RowElement, FormElement, TitleElement, TabsElement, TabElement, LabelElement, InputElement, ButtonElement, SelectElement, OptionElement, LinkElement
  },
  methods: {
    ...MethodsPage,
    ...{
      isMobile() {

        if(window.outerWidth < 960) {

          this.mobile = true

          return true

        } else {

          this.mobile = false

          return false

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
        } else
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
  },
  mounted()
  {

    this.isMobile()

    this.choises()

  },
  updated()
  {

    this.isMobile()

    this.choises()

  }

}

</script>

<style lang="scss">
</style>