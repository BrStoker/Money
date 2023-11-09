<template lang="pug">
  DivElement(classCss="col, col_3, col_mob-12")
    DivElement(classCss="section__subsection, section__subsection_modal, modal")
      Form(:action="schemas.method" :onsubmit="showResult" style="width: 100%")
        fieldset
          DivElement(classCss="subsection__header")
            RowElement()
              DivElement(classCss="col, col_10")
                DivElement(classCss="wysiwyg")
                  h3 {{schemas.title}}
              DivElement(classCss="col, col_2")
                DivElement(classCss="modal__remove")
                  SvgElement(:image="store.images.button_remove")
          DivElement(classCss="subsection__main")
            DivElement(classCss="formular")
              DivElement(classCss="formular__main")
                DivElement(v-for="(step, index) in schemas.steps" :key="index" classCss="form__group, group" v-if="step.inputs")
                  DivElement(classCss="group__header" v-if="step.name !== ''")
                    DivElement(classCss="wysiwyg")
                      h6 {{step.title}}
                  DivElement(classCss="group__main")
                    DivElement(classCss="form-item" v-if="step.title == ''")
                      DivElement(classCss="form-item__main")
                        DivElement(classCss="form-item__field, col, col_12" :onclick="show_modal_interests" @addInterests="addInterests")
                          Span(classCss="btn, btn_tertiary")
                            Span(classCss="btn__text") {{'Интересы'}}
                            Span(classCss="btn__label") {{appData.countNishes}}
                    FormInput(:data="step" :onchange="changeFilter")
                DivElement(classCss="col, col_12")
                  DivElement(classCss="form-item, mb-0")
                    DivElement(classCss="form-item__main")
                      DivElement(classCss="form-item__field")
                        ButtonElement(:classCss="schemas.button_submit.class" :type="schemas.button_submit.type") {{schemas.button_submit.text}}

</template>
<script>

    import RowElement from '@/js/components/elements/Row'
    import DivElement from '@/js/components/elements/Div'
    import InputElement from '@/js/components/elements/Input'
    import SelectElement from '@/js/components/elements/Select'
    import OptionElement from '@/js/components/elements/Option'
    import ButtonElement from '@/js/components/elements/Button'
    import SvgElement from '@/js/components/elements/Svg'
    import Form from '@/js/components/elements/Form'
    import Span from '@/js/components/elements/Span'
    import Label from '@/js/components/elements/Label'
    import Select2 from 'v-select2-component'
    import FormInput from '@/js/components/elements/FormInput'
    import MethodsFilter from '@/js/methods/blocks/Filter'
    import HttpClass from '@/js/classes/Http'



    export default {
        name: 'FilterBlock',

        data() {

            return {
                store: this.$store.state.data.filter,
                schemas: this.$store.state.schemas.filter,
                appData: this.$store.state.data.app,
                countries: this.$store.state.data.app.countries,
                options: this.$store.state.data.app.filterOptions,
                myValue: '',
                selectSettings: {
                  width: '100%'
                }
            }

        },
        methods:{
          ...MethodsFilter,
          ...HttpClass,
          showResult(e){
            e.preventDefault()
            this.filterList()
          },
          changeFilter(e){

            this.filterList()

          },
          addInterests(){
            console.log(11111)
          }
        },
      components: {
        DivElement,
        SelectElement,
        OptionElement,
        ButtonElement,
        InputElement,
        Form,
        RowElement,
        Span,
        Label,
        SvgElement,
        Select2,
        FormInput
      },

      mounted() {

        // this.setFilter()

      }


    }
</script>

<style lang="scss">
</style>
