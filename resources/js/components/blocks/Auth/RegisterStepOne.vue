<template lang="pug">
FormElement(:action="schema.url" :method="schema.method" :onsubmit="Register")
  DivElement(classCss="error" v-for="(error, index) in errors" :key="index") {{error}}
  fieldset
    DivElement(classCss="form__group, group")
      DivElement(classCss="group__main")
        RowElement
          DivElement(classCss="col, col_12" v-for="(step, index) in schema.steps" :key="index")
            DivElement(:classCss="step.classChild" v-if="step.classChild")
            DivElement(:classCss="step.classChild" v-if="step.classChild")
              InputElement(:data="input" v-for="(input, inputIndex) in step.inputs" :key="inputIndex" :onchange="checkboxChange")
            InputElement(:data="input" v-for="(input, inputIndex) in step.inputs" :key="inputIndex" v-else)

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import RowElement from '@/js/components/elements/Row'
  import FormElement from '@/js/components/elements/Form'
  import FormInput from '@/js/components/elements/FormInput'
  import InputElement from '@/js/components/elements/Input'

  import HttpClass from '@/js/classes/Http'
  import Utils from '@/js/classes/Util'

  export default{
    props: [ ],
    data() {
      return{
        success: [],
        errors: [],
        userId: '',
        store: this.$store.state.data.app,
        schema: this.$store.state.schemas.header.blocks.register.steps.StepOne,
        checked: false
      }
    },
    components:{
      DivElement,
      RowElement,
      FormElement,
      FormInput,
      InputElement

    },
    methods:{
      ...HttpClass,
      ...Utils,
      ...{
        Register(e){
          e.preventDefault()
          let error = this.validate()
          if (error == false){
            this.sendRequest({
              method: this.schema.method,
              url: this.schema.action,
              data: this.SchemasToFormData(this.schema),
              success: this.RegisterSuccess,
              error: this.handlerErrorResponse
            })
          }else{
            this.removeErrors()
          }

        },
        RegisterSuccess(result) {
          if(_.has(result.data, 'code') === true && result.data.code === 0) {
            this.$store.commit('changeStep', result.data)
          } else {
            this.appendErrorsToSchema(this.schema, result.data.desc)
          }
        },
        handlerErrorResponse(result) {
          console.error('RegisterError -> ', result)
        },
        checkboxChange(e){
          this.checked = !this.checked
        },
        validate(){
          let errors = false
          _.forEach(this.schema.steps, function(step){
            _.forEach(step.inputs, function(input){
              if(input.error){
                input.error = ''
              }
              if(input.validate){
                if(input.required && input.value.length == 0){
                  input.error = 'Обязательное поле'
                  errors = true;
                }
              }
            })
          })
          return errors
        },
        removeErrors(){
          this.schema.steps.forEach(step =>{
            if(step.inputs){
              step.inputs.forEach(input=>{

                if(input.error){

                  input.error = ''

                }


              })

            }

          })

        }

      }
    },
    mounted(){
    }

  }


</script>

<style lang="scss" scoped>
</style>