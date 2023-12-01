<template lang="pug">
DivElement(classCss="subsection__header")
  DivElement(classCss="formular")
    DivElement(classCss="formular__main")
      Form(:action="schema.action" :onsubmit="search")
        fieldset()
          DivElement(classCss="series" v-for="(step, index) in schema.steps" :key="index")
            DivElement(classCss="series__group, w-100")
              DivElement(classCss="form-item, form-item_before, form-item_after, mb-0")
                DivElement(classCss="form-item__main")
                  FormInput(:data="step" :onchange="search")

</template>

<script>

import InputElement from '@/js/components/elements/Input'
import DivElement from '@/js/components/elements/Div'
import Form from '@/js/components/elements/Form'
import MethodsFilter from '@/js/methods/blocks/Filter'
import MethodsFilterCourse from '@/js/methods/blocks/FilterCourses'
import HttpMethods from '@/js/classes/Http'
import FormInput from '@/js/components/elements/FormInput'




export default {
  data() {
    return {
      data: this.$store.state.data.search,
      schema: this.$store.state.schemas.search,
      schemas: this.$store.state.schemas.filter,
      schemaCourse: this.$store.state.schemas.filterCourse

    }
  },
  components:{
    InputElement,
    Form,
    DivElement,
    FormInput
  },
  methods:{
    ...MethodsFilter,
    ...HttpMethods,
    ...MethodsFilterCourse,
    search(e){
      e.preventDefault()
      if(window.location.pathname == '/courses'){
        this.schemaCourse.steps[2].inputs[2].value = this.$store.state.schemas.search.steps[0].inputs[0].value
        this.filterCourseList()
      }else{

        this.schemas.steps[6].value = this.$store.state.schemas.search.steps[0].inputs[0].value

        this.filterList()

      }


    },
    confirmSuccess(result){

      if(result.data.code == 0){

        this.$emit("update_user",result.data.users)

      }
    },
    handelErrorResponse(result){

      console.error(result)

    }
  }
}
</script>

<style lang="scss" scoped>
</style>