<template lang="pug">
MainElement(classCss="layout__main")
  SectionElement(classCss="layout__section, section section_animation")
    DivElement(classCss="section__header")
      DivElement(classCss="wysiwyg")
        h2 {{title}}
    DivElement(classCss="section__main")
      DivElement(classCss="formular")
        DivElement(classCss="formular__main")
          FormElement(:action="schema.url" :method="schema.method" :onsubmit="saveChanges")
            fieldset
              DivElement(classCss="section__subsection, subsection" v-for="(step, index) in this.$store.state.schemas.editpofile.steps" :key="index")
                DivElement(classCss="subsection__header")
                  DivElement(classCss="wysiwyg")
                    h3 {{step.name}}
                DivElement(classCss="subsection__main")
                  RowElement
                    DivElement(classCss="col, col_10, col_mob-12")
                      DivElement(classCss="form__group, group")
                        DivElement(classCss="group__main")
                          DivElement(classCss="form-item, form-item_after" v-for="(input, inputIndex) in step.inputs" :key="inputIndex")
                            DivElement(classCss="form-item__header" v-if="input.header")
                              DivElement(classCss="form-item__label")
                                DivElement(classCss="form-item__media" v-if="input.header.image")
                                  SvgElement(:image="input.header.image")
                                DivElement(classCss="form-item__title, title")
                                  SpanElement(classCss="title__text") {{input.header.title}}
                            DivElement(classCss="form-item__main")
                              DivElement(classCss="form-item__field")
                                InputElement(:classCss="input.class" :data="input" :onclick="showInterests" :user="user" :onchange="changeImage")
</template>

<script>


import MainElement from '@/js/components/elements/Main'
import SectionElement from '@/js/components/elements/Section'
import DivElement from '@/js/components/elements/Div'
import FormElement from '@/js/components/elements/Form'
import RowElement from '@/js/components/elements/Row'
import SvgElement from '@/js/components/elements/Svg'
import SpanElement from '@/js/components/elements/Span'
import InputElement from '@/js/components/elements/Input'

import AppMethods from '@/js/methods/App'
import ComputedEditUser from '@/js/computed/pages/User/EditUser'
import HttpClass from '@/js/classes/Http'
import MethodsUser from '@/js/methods/pages/User'
import Utils from '@/js/classes/Util'

export default {
  name: 'UserEditPage',

  props: ['data'],
  data()
  {

    return {
      title: 'Редактировать профиль',
      success: [],
      errors: [],
      user: this.$store.state.data.app.user.data,
      schema: this.appendDataToSchema(this.$store.state.schemas.editpofile, this.$store.state.data.app.user.data),
      nishes: this.$store.state.data.app.nishes,
      countries: this.$store.state.data.app.countries,
      cities: this.$store.state.data.app.cities,
      avatar: '/image/avatar.png'
    }

  },
  components: {
    MainElement, SectionElement,
    DivElement, FormElement,
    RowElement, SvgElement,
    SpanElement, InputElement,
  },
  computed:{

  },
  methods: {
    ...AppMethods,
    ...ComputedEditUser,
    ...HttpClass,
    ...MethodsUser,
    ...Utils,
    changeImage(event) {
      if(event != undefined){
        if(event.target.files){
          let file = event.target.files[0]
          if (file.type.match('image.*') && file.type.indexOf('image.*') === -1) {
            this.avatar = URL.createObjectURL(file)
            this.user.image = this.avatar
          } else {
            this.avatar = '/image/avatar.png';
          }
        }
      }
    },
    getAvatar()
    {
      return (this.user && this.user.avatar? '/storage/' + this.user.avatar : this.avatar );
    },

    saveChanges(e){
      e.preventDefault()
      this.errors = []
      this.success = []
      let error = this.validateForm()
      if(error === false){
        let userData = this.profileToFormData(this.schema)
        this.sendRequest({
          method: this.schema.method,
          url: this.schema.url,
          data: userData,
          success: this.SaveSuccess,
          error: this.SaveError
        })
      }else{
        var errors = document.querySelector('.error')
        if(errors){
          errors.scrollIntoView({behavior: 'smooth'})
        }
      }
    },
    SaveSuccess(result){
      console.log(result)
      if(result.data.code === 0){
        if(result.data.location){
          window.location = result.data.location
        }
      }else{
        if(result.data.desc){
          this.appendErrorsToSchema(this.schema, result.data.desc)
        }
      }
    },
    SaveError(result){
      console.error('Save error =>', result)
    },
    showInterests(e){
      if(!e.target.classList.contains('modal-init')){
        this.$store.commit('closeIntereForm')
      }else{

      }

    },

    getCity(countryId) {
      let userData = new FormData()
      userData.append('country_id', countryId)
      this.sendRequest({
        method: 'get',
        url: '/getcity',
        data: userData,
        success: this.SaveSuccess,
        error: this.SaveError
      })
    },
    citySuccess(result){
      if(result.data.code === 0){
        this.appendCityToData(result.data.cities)
      }else{
        this.errors = result.data.desc
      }
    },

    validateForm(e){
      let error = false
      let schema = this.schema
      if(_.has(schema, 'steps') === true){
        _.forEach(schema.steps, function(step){
          if(_.has(step, 'inputs') === true){
            _.forEach(step.inputs, function(input){
              if(_.has(input, 'validate')){
                if(input.validate === true){
                  if(step.name === 'Социальные сети'){
                    const urlPattern = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([/\w.-]*)*\/?$/
                    if(input.value.length > 0){
                      if (!urlPattern.test(input.value)) {
                        input.error = 'Невалидная ссылка'
                        error = true
                      }
                    }
                  }else if(step.name === 'Изменить пароль'){
                    const newPassword = schema.steps.find(s => s.name === 'Изменить пароль').inputs.find(i => i.name === 'newpassword').value;
                    const repeatNewPassword = schema.steps.find(s => s.name === 'Изменить пароль').inputs.find(i => i.name === 'repeat_newpassword').value;
                    if(newPassword.length > 0){
                      if (newPassword !== repeatNewPassword) {
                        input.error = 'Пароли не совпадают';
                        error = true
                      } else {
                        input.error = '';
                        error = false
                      }
                    }
                  }else{
                    if(input.value === ''){
                      input.error = 'Обязательное поле'
                      error = true
                    }
                  }
                }
              }
            })
          }
        })
      }
      return error
    },

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