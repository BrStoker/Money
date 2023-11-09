<template lang="pug">





</template>

<script>


/*
 
  RowElement(classCss="row__width_full, row__wrap_wrap, row__justify_right")
    LinkElement(:href="link.link" classCss="link__name_auth-form") {{ link.text }}

*/

import DivElement from '@/js/components/elements/Div'
import SvgElement from '@/js/components/elements/Svg'
import Span from '@/js/components/elements/Span'
import Form from '@/js/components/elements/Form'
import RowElement from '@/js/components/elements/Row'


export default {
  name: 'LoginPage',

  data()
  {
    return {
      success: [ ],
      errors: [ ],
      data: this.$store.state.data,
      schema: this.$store.state.schemas
    }
  },
  components: {
    DivElement, SvgElement, Span, Form, RowElement

  },
  created(){

  },
  methods:
  {
    ...{
      showForm(){
        this.$store.state.data.app.isModalShown = !this.$store.state.data.app.isModalShown
      },
      Login(e)
      {
        e.preventDefault()
        this.errors = []
        this.success = []
        this.sendRequest(e.target, this.LoginSuccess, this.LoginError)
      },
      LoginSuccess(result)
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
      LoginError(result) {
        console.error(result)
      }

    }

  }

}
</script>

<style lang="scss">
</style>