<template lang="pug">
MainElement(classCss="layout__main")
  DivElement(classCss="layout__section, section, section_animation")
    DivElement(classCss="section__header")
      DivElement(classCss="wysiwyg")
        h2 {{schema.title}}
    DivElement(classCss="section__main")
      DivElement(classCss="formular")
        DivElement(classCss="formular__main")
          FormElement(:action="schema.url" :method="schema.method" :onsubmit="createCourse")
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
                              DivElement(classCss="row" v-if="input.type == 'group'")
                                DivElement(:classCss="inp.parentClass" v-for="(inp, inputIndex) in input.inputs" :key="inputIndex")
                                  InputElement(:data="inp")
                              InputElement(:data="input" :onchange="changeImage" v-else)
                          DivElement(classCss="form-item" v-if="step.inputs.length > 1")
                            DivElement(classCss="form-item__header")
                            DivElement(classCss="form-item__main")
                              DivElement(classCss="form-item__field")
                                DivElement(classCss="form-item__tooltip, tooltip")
                                  SpanElement(classCss="tooltip__text") {{tooltipText}}
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
      courseTypes: this.$store.state.data.app.courseTypes,
      courseSubject: this.$store.state.data.app.courseSubject,
      schema: this.$store.state.schemas.addCourse,
      tooltipText: 'Курсы в каталоге проходят модерацию'
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
      createCourse(e){
        e.preventDefault()
        this.errors = []
        this.success = []
        let userData = this.SchemasToFormData(this.schema)
        let file = e.target.querySelector('input[type="file"]')
        userData.append('image', e.target.querySelector('input[type="file"]').files[0])
        // let types = []
        // for (let key in this.courseTypes){
        //   let item = this.courseTypes[key]
        //   if(item.value){
        //     types.push(item.value)
        //   }
        // }
        // userData.append('types[]', types)
        // let subjects = []
        // for (let key in this.courseSubject){
        //   let item = this.courseSubject[key]
        //   if(item.value){
        //     subjects.push(item.value)
        //   }
        // }
        // userData.append('subjects[]', subjects)
        this.sendRequest({
          method: 'POST',
          url: '/courses/add',
          data: userData,
          success: this.confirmSuccess,
          error: this.handelErrorResponse
        })
      },
      createArticle(e){
        e.preventDefault()
        this.errors = []
        this.success = []
        let userData = this.SchemasToFormData(this.schema)
        let file = e.target.querySelector('input[type="file"]')

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
        if(e){
          e.preventDefault()
          if(e.target.files){
            let selectedFile = e.target.files[0]

            let blob = new Blob([selectedFile], { type: selectedFile.type });
            let blobUrl = URL.createObjectURL(blob);
            let img = document.querySelector('.avatar__img').src = blobUrl
          }
        }
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