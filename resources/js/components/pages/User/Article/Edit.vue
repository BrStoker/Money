<template lang="pug">
  MainElement(classCss="layout__main")
    DivElement(classCss="layout__section, section, section_animation")
      DivElement(classCss="section__header")
        DivElement(classCss="wysiwyg")
          h2 {{'Редактирование статьи'}}
      DivElement(classCss="section__main")
        DivElement(classCss="formular")
          DivElement(classCss="formular__main")
            FormElement(:action="schema.url" :method="schema.method" :onsubmit="SaveArticle")
              fieldset
                DivElement(classCss="section__subsection, subsection" v-for="(step, index) in schema.steps" :key="index")
                  DivElement(classCss="subsection__main")
                    RowElement
                      DivElement(classCss="col, col_10, col_mob-12")
                        DivElement(classCss="form__group, group")
                          DivElement(classCss="group__main")
                            DivElement(classCss="form-item" v-for="(input, subIndex) in step.inputs" :key="subIndex")
                              DivElement(classCss="form-item__header" v-if="input.header")
                                DivElement(classCss="form-item__label")
                                  DivElement(classCss="form-item__title, title")
                                    SpanElement(classCss="title__text") {{input.header.title}}
                                    SpanElement(classCss="title__tooltip") {{input.header.hint}}
                              DivElement(classCss="form-item__main")
                                InputElement(:data="input" :onclick="showAddForm" :onchange="editImage")
                                InputElement(v-for="(category, catIndex) in categories" :key="catIndex" :data="category" v-if="input.name == 'article_group_ids'")
</template>

<script>

import DivElement from '@/js/components/elements/Div'
import MainElement from '@/js/components/elements/Main'
import FormElement from '@/js/components/elements/Form'
import RowElement from '@/js/components/elements/Row'
import SpanElement from '@/js/components/elements/Span'
import InputElement from '@/js/components/elements/Input'
import TextArea from '@/js/components/elements/Textarea'
import LabelElement from '@/js/components/elements/Label'
import SvgElement from '@/js/components/elements/Svg'
import ButtonElement from '@/js/components/elements/Button'

import AppMethods from '@/js/methods/App'

import ComputedIndex from '@/js/computed/pages/Index'
import HttpClass from '@/js/classes/Http'
import MethodsArticle from '@/js/methods/pages/Article'

export default {
  name: 'UserArticleAddPage',
  props: ['data'],
  data()
  {

    return {
      success: [],
      errors: [],
      user: this.$store.state.data.app.user.data,
      schema: this.$store.state.schemas.addArticle,
      categories: this.$store.state.data.app.articleGroups
    }

  },
  components: {
    DivElement, MainElement, FormElement, RowElement, SpanElement, InputElement, TextArea, LabelElement, SvgElement, ButtonElement
  },
  methods: {
    ...AppMethods,
    ...ComputedIndex,
    ...HttpClass,
    ...MethodsArticle,
    ...{
      SaveArticle(e){
        e.preventDefault()

        this.errors = []
        this.success = []
        let userData = this.SchemasToFormData(this.schema)

        Object.entries(this.categories).forEach((item, value)=>{
          let category = this.categories[value]

          if(category.value == true){
            let key = category.name.split('_')
            console.log(key)
            userData.append('article_group_ids[]', key[key.length-1])
          }
        })
        this.sendRequest({
          method: 'POST',
          url: window.location.pathname,
          data: userData,
          success: this.editSuccess,
          error: this.handlerErrorResponse
        })
      },
      editSuccess(result){
        if(result.data.code == 0){
          window.location.href = result.data.location
        }
      },
      handlerErrorResponse(result){
        console.error(result)
      },
      editImage(e){
        let fd = new FormData()
        fd.append('image', e.target.files[0])
        fd.append('article_id', this.data.id)
        this.sendRequest({
          method: 'POST',
          url: '/article/image',
          data: fd,
          headers: { 'Content-Type': 'multipart/form-data' },
          success: this.uploadSuccess,
          error: this.handlerErrorResponse
        })
      },
      uploadSuccess(result){
        if(result.data.code == 0){
          this.$store.commit('storeArticleImage', result.data.image, this.schema)
        }
      }

    }
  },
  created(){
    // console.log(this)
  },
  mounted()
  {
    // console.log(this.data)
  },
  updated()
  {

  }
}

</script>

<style lang="scss">
</style>