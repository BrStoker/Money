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


  name: 'PartnersStatisticsPage',

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


  },
  mounted(){

  }
}

</script>