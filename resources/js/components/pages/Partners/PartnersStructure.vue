<template lang="pug">
EmptyElement
  PopupNotification(:message="notificationMessage" v-if="notificationMessage")
  DivElement(classCss="section__subsection, subsection" v-for="(step, index) in schema.steps" :key="index")
    DivElement(classCss="subsection__header" v-if="!step.inputs")
      DivElement(classCss="wysiwyg")
        h3 {{step.title}}
    DivElement(classCss="subsection__main")
      DivElement(classCss="formular, mb-0" v-if="step.inputs")
        DivElement(classCss="formular__main")
          Form
            fieldset
              RowElement
                DivElement(classCss="col, col_6, col_tab-8, col_mob-12")
                  DivElement(classCss="form__group, group")
                    DivElement(classCss="group__header")
                      DivElement(classCss="wysiwyg")
                        h5 {{step.title}}
                    DivElement(classCss="group__main")
                      DivElement(classCss="form-item, mb-0")
                        DivElement(:classCss="input.classCss" v-for="(input, subIndex) in step.inputs" :key="subIndex")
                          DivElement(classCss="form-item__field")
                            InputElement(:data="input" :onclick="copyToClipboard")
      PartnerParentBlock(:referals="user.referals" :step="step")




</template>

<script>

import EmptyElement from '@/js/components/elements/Empty'
import DivElement from '@/js/components/elements/Div'
import SpanElement from '@/js/components/elements/Span'
import ImageElement from '@/js/components/elements/Image'
import InputElement from '@/js/components/elements/Input'
import Form from '@/js/components/elements/Form'
import RowElement from '@/js/components/elements/Row'
import PartnerParentBlock from '@/js/components/pages/Partners/PartnersParent'
import PopupNotification from '@/js/components/elements/Notification'

export default{


  name: 'AffilateStructurePage',

  data(){

    return{

      user: this.$store.state.data.app.user.data,
      schema: this.$store.state.schemas.partners,
      avatar: '/image/avatar.png',
      success: '',
      error: '',
      notificationMessage: ''

    }

  },
  components:{
    EmptyElement, DivElement, SpanElement, InputElement, Form, RowElement, ImageElement, PartnerParentBlock, PopupNotification
  },
  methods:{
    copyToClipboard(){
      navigator.clipboard.writeText(this.schema.steps[0].inputs[0].value)
      .then(() => {
        this.notificationMessage = ''
      })
          .then(()=>{
            this.notificationMessage = 'Ссылка скопирована в буфер обмена'
          })
      .catch(()=>{
        this.notificationMessage = 'Ошибка копирования ссылки'
      })
    },
    getImage(image){
      if(image != null){
        return '/storage/' + image
      }else{
        return this.avatar
      }
    },
    getFio(userData){
      return [userData.first_name, userData.last_name].join(' ')
    }

  },
  mounted(){

  }
}

</script>