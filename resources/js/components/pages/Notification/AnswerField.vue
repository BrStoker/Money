<template lang="pug">
DivElement(classCss="series__group")
  DivElement(classCss="success" v-if="success") {{success}}
  DivElement(classCss="series, mb-0, no-wrap")
    DivElement(classCss="series__group, series__group_tertiary")
      DivElement(classCss="media, media_tertiary, mb_m-0")
        ImageElement(:src="getUserImage()")
    DivElement(classCss="series__group, w-100")
      DivElement(classCss="formular")
        DivElement(classCss="formular__main")
          FormElement(:onsubmit="addAnswer")
            fieldset
              DivElement(classCss="form__group, group")
                DivElement(classCss="group__main" v-for="(step, index) in schema.steps" :key="index")
                  DivElement(classCss="form-item" v-for="(input, subIndex) in step.inputs" :key="subIndex")
                    DivElement(classCss="form-item__main")
                      InputElement(:data="input")
</template>

<script>
import EmptyElement from '@/js/components/elements/Empty'
import DivElement from '@/js/components/elements/Div'
import MainElement from '@/js/components/elements/Main'
import SectionElement from '@/js/components/elements/Section'
import SpanElement from '@/js/components/elements/Span'
import ImageElement from '@/js/components/elements/Image'
import FormElement from '@/js/components/elements/Form'
import InputElement from '@/js/components/elements/Input'


import AppMethods from '@/js/methods/App'
import HttpClass from '@/js/classes/Http'


export default {
  name: 'NotificationCommentPage',
  props: ['data', 'article'],


  data(){
    return{
      title: 'Комментарии',
      user: this.$store.state.data.app.user,
      schema: this.$store.state.schemas.answerField,
      image: '/image/avatar.png',
      success: ''

    }
  },
  components:{
    EmptyElement,
    MainElement,
    SectionElement,
    SpanElement,
    DivElement,
    ImageElement,
    FormElement,
    InputElement

  },
  methods:{
    ...AppMethods,
    ...HttpClass,
    getUserImage(){
      return this.user.data.image ? '/storage/' + this.user.data.image : this.image
    },
    addAnswer(e){
      e.preventDefault()
      this.sendRequest({
        method: this.schema.method,
        url: this.schema.action + this.article.id,
        data: this.SchemasToFormData(this.schema),
        success: this.AddSuccess,
        error: this.handlerErrorResponse
      })
    },
    AddSuccess(result){
      if(result.data.code == 0){
        this.appendUserToStore(result.data, false)
        this.schema.steps[0].inputs[0].value = ''
        this.success = result.data.desc
      }
    },
    handlerErrorResponse(result){
      console.log(result)
    }
  },
  created(){
    this.appendDataToSchema(this.schema, this.data)
  },
  mounted(){

  }
}


</script>

