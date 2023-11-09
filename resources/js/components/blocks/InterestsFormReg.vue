<template lang="pug">
  DivElement(classCss="modal__layout, modal__layout_interests" :class="classIsInterestShown()")
    DivElement(classCss="modal__action, action")
      SvgElement(:image="schema.images.close" :onclick="closeForm")
    DivElement(classCss="modal__main")
      DivElement(classCss="wysiwyg" v-if="newUser")
        h3 {{schema.title.regard}}
        h3 {{schema.title.regardText}}
        p {{schema.title.textPreview}}
        p {{schema.title.textChoise}}
      DivElement(classCss="formular, mb-0")
        DivElement(classCss="formular__main")
          FormElement(:action="schema.action" :method="schema.method" :onsubmit="addInterests")
            fieldset
              DivElement(classCss="form__group, group")
                DivElement(classCss="group__main")
                  RowElement(v-for="(step, index) in schemasSteps.steps" :key="index")
                    DivElement(classCss="col, col_4, col_mob-12" v-for="(input, subindex) in step.inputs" :key="subindex")
                        DivElement(classCss="form-item")
                          DivElement(classCss="form-item__main")
                            CustomCheckbox(:data="input" :onchange="()=>{}")
                    DivElement(classCss="col, col_12")
                      DivElement(classCss="form-item")
                        DivElement(classCss="form-item__main")
                          DivElement(classCss="form-item__field, modal-init")
                            ButtonElement(classCss="btn, w-100")
                              SpanElement(classCss="btn__text") {{buttonText()}}

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'
  import FormElement from '@/js/components/elements/Form'
  import RowElement from '@/js/components/elements/Row'
  import FormInput from '@/js/components/elements/FormInput'
  import InputElement from '@/js/components/elements/Custom/CustomInput'
  import ButtonElement from '@/js/components/elements/Button'
  import NotificationPopup from '@/js/components/elements/Notification'

  import AppMethods from '@/js/methods/App'
  import ComputedInterestsForm from '@/js/computed/blocks/InterestsForm'
  import HttpClass from '@/js/classes/Http'
  import MethodsInterest from '@/js/methods/blocks/InteresForm'
  import CustomCheckbox from '@/js/components/elements/Custom/Checkbox'
  import MethodsFilter from '@/js/methods/blocks/Filter'

  export default{

    data(){
      return{

        schemasSteps: this.$store.state.data.app.interests,
        schemas: this.$store.state.schemas.filter,
        data: this.$store.state.data,
        schema: this.$store.state.schemas.interestsReg,
        appStore: this.$store.state.data.app,
        user: this.$store.state.data.app.user,
        newUser: false,
        filter: false,
        notificationMessage: ''
      }
    },
    components:{
      DivElement,
      SvgElement,
      SpanElement,
      FormElement,
      RowElement,
      FormInput,
      ButtonElement,
      InputElement,
      CustomCheckbox,
      NotificationPopup

    },
    methods:{
      ...HttpClass,
      ...ComputedInterestsForm,
      ...AppMethods,
      ...MethodsInterest,
      ...MethodsFilter,
      addInterests(e) {
        e.preventDefault()

        let interests = []
        if (this.isFilter()){

          let data = this.$store.state.data.app.interests
          if(_.has(data, 'steps') == true && _.size(data.steps) > 0){
            let values = []
            data.steps.forEach(function(step, index){
              if(_.has(step, 'inputs') == true && _.size(step.inputs) > 0){
                let inputs = Object.fromEntries(
                    Object.entries(step.inputs).filter(([key, value]) => value.value === true)
                )

                _.forIn(inputs, (input, key)=>{
                  interests.push(input.id)
                })

              }

            })

          }

          this.schemas.steps[5].value = interests

          this.filterList()
          this.closeForm()

        }else{

          let inputs = this.$store.state.data.app.interests.steps[0].inputs
          let interests = []
          inputs.forEach(function (item) {
            if (item.value == true){
              interests.push(item.name.slice(-3))
            }
          })

          let fd = new FormData()
          fd.append('fields[interest]', interests);
          this.sendRequest({
            method: 'POST',
            url: "/user/interests",
            data: fd,
            success: this.confirmSuccess,
            error: this.handelErrorResponse
          })

        }

      },
      confirmSuccess(result){

        if(result.data.code == 0){
          if(result.data.location != undefined){
            // this.appendUserToStore(result.data, false)
            this.$store.commit('closeIntereForm')
          }
        }
      },
      handelErrorResponse(result){
        console.log(result)
      },
      showInterestForm(){
        if(this.newUser){
          this.$store.commit('closeIntereForm')
        }
      }

    },
    computed: {

    },
    created(){
      this.itsNew()
      this.showInterestForm()
    },
    mounted(){

    }

  }


</script>

<style lang="scss" scoped>
</style>