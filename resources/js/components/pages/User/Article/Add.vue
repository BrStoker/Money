<template lang="pug">
MainElement(classCss="layout__main")
  DivElement(classCss="layout__section, section, section_animation")
    DivElement(classCss="section__header")
      DivElement(classCss="wysiwyg")
        h2 {{schema.title}}
    DivElement(classCss="section__main")
      DivElement(classCss="formular")
        DivElement(classCss="formular__main")
          FormElement(:action="schema.url" :method="schema.method" :onsubmit="createArticle")
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
                              InputElement(:data="input" :onclick="showAddForm" :onchange="changeImage")
                              InputElement(v-for="(category, catIndex) in $store.state.data.app.articleGroups" :key="category" :data="category" :onchange="onChange" v-if="input.name == 'article_group_ids'")
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
      categories: this.$store.state.data.app.articleGroups,
      schema: this.appendDataToSchema(this.$store.state.schemas.addArticle, this.$store.state.data.app.articleGroups),
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
      createArticle(e){
        e.preventDefault()
        this.errors = []
        this.success = []
        let userData = this.SchemasToFormData(this.schema)
        let file = e.target.querySelector('input[type="file"]')
        console.log(e.target.querySelector('input[type="file"]').files[0])
        userData.append('image', e.target.querySelector('input[type="file"]').files[0])
        let Ids = []
        for(let key in this.categories){
          let item = this.categories[key]
            if(item.value != false){
              let id = item.name.split('_')
              Ids.push(id[id.length-1])
            }
          }
          userData.append('article_group_ids[]', Ids)
          this.sendRequest({
            method: 'POST',
            url: "/user/article",
            data: userData,
            success: this.confirmSuccess,
            error: this.handelErrorResponse
          })

      },
      confirmSuccess(result){
        if(result.data.code == 0){
          location.href = result.data.location
        }else{
          this.appendErrorToSchema(this.schema, result.data.errors)
        }
      },
      handelErrorResponse(result){
        console.error(result)
      },
      onChange(e){
        console.log(111,e)
      },
      changeImage(e){
        e.preventDefault()

        let selectedFile = e.target.files[0]

        let blob = new Blob([selectedFile], { type: selectedFile.type });
        let blobUrl = URL.createObjectURL(blob);
        let img = document.querySelector('.avatar__img').src = blobUrl
      },
      validateForm(){
        let error = true
        let form = this.schema
        if(_.has(form, 'steps') == true && form.steps != undefined){
          for (let step in form.steps){
            let formStep = form.steps[step]
            if(formStep.group == true){
              if(_.has(formStep, 'inputs') == true && formStep.inputs != undefined){
                for(let input in formStep){
                  if(input.value == false){

                  }
                }
              }
            }
          }
        }

        return error
      }

    }
  },
  computed:{
    category_reactive(){
      return  this.$store.state.data.app.articleGroups
    }
  },
  created(){

  },
  mounted()
  {

  },
  updated()
  {

  }
}

</script>

<style lang="scss">
</style>