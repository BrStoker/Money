<template lang="pug">
  DivElement(classCss="wysiwyg")
    SpanElement() {{info}}
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

export default{


  name: 'PartnersPromoPage',

  data(){

    return{

      user: this.$store.state.data.app.user.data,
      schema: this.$store.state.schemas.partners,
      avatar: '/image/avatar.png',
      success: '',
      error: '',
      info: 'Страница находится в стадии разработки'

    }





  },
  components:{
    EmptyElement, DivElement, SpanElement, InputElement, Form, RowElement, ImageElement, PartnerParentBlock
  },
  methods:{
    copyToClipboard(){
      navigator.clipboard.writeText(this.schema.steps[0].inputs[0].value)
      .then(() => {
        this.success = 'Ссылка скопирована в буфер обмена'
      })
      .catch(()=>{
        this.error = 'Ошибка копирования ссылки'
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