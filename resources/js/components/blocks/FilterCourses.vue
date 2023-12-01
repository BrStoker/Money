<template lang="pug">
DivElement(classCss="col, col_4, col_mob-12")
  DivElement(classCss="section__subsection, section__subsection_modal, modal")
    Form(classCss="form, w-100" :onsubmit="showResult")
      fieldset
        DivElement(classCss="subsection__header")
          RowElement
            DivElement(classCss="col, col_10")
              DivElement(classCss="wysiwyg")
                h3 {{schemaCourse.title}}
            DivElement(classCss="col, col_2")
              DivElement(classCss="modal__remove")
                SvgElement(:image="schemaCourse.imgClose")
        DivElement(classCss="subsection__main")
          DivElement(classCss="formular")
            DivElement(classCss="formular__main")
              DivElement(classCss="form__group, group" v-for="(step, index) in schemaCourse.steps" :key="index")
                DivElement(classCss="group__header" v-if="step.title != ''")
                  DivElement(classCss="wysiwyg")
                    h6 {{step.title}}
                DivElement(classCss="group__main")
                  DivElement(classCss="row, row_second" v-if="step.simpleSelect")
                    InputElement(v-for="(input, inputIndex) in step.inputs" :key="inputIndex" :data="input" :onchange="changeFilter")
                  InputElement(v-for="(input, inputIndex) in step.inputs" :key="inputIndex" :data="input" :onchange="changeFilter" v-else)
                DivElement(classCss="group__footer" v-if="step.hasButtons")
                  DivElement(classCss="form-item, mb-0")
                    DivElement(classCss="form-item__main")
                      DivElement(classCss="form-item__field")
                        ButtonElement(classCss="btn, w-100" type="submit") {{'Применить'}}


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
    import EmptyElement from '@/js/components/elements/Empty'

    import HttpClass from '@/js/classes/Http'
    import FilterCoursesMethods from '@/js/methods/blocks/FilterCourses'


    export default {
        name: 'FilterCourseBlock',

        data() {

            return {
              schemaCourse: this.$store.state.schemas.filterCourse

            }

        },
        methods:{
          ...HttpClass,
          ...FilterCoursesMethods,
          showResult(e){
            e.preventDefault()
            this.filterCourseList()
          },
          changeFilter(e){

            this.filterCourseList()

          },

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
        FormInput,
        EmptyElement,
      },

      mounted() {

      }


    }
</script>

<style lang="scss">
</style>
