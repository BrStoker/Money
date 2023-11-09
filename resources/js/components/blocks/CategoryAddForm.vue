<template lang="pug">
  DivElement(:classCss="'modal__layout, modal__layout_tiny, modal__layout_authorization' + (data.app.isCategoryAddShown?', modal__layout_active':'')")
    DivElement(classCss="modal__action, action")
      SvgElement(:image="data.header.blocks.login.images.close" :onclick="showForm")
    DivElement(classCss="modal__main")
      DivElement(classCss="wysiwyg")
        h3 {{schema.title}}
      DivElement(classCss="formular, mb-0")
        DivElement(classCss="formular__main")
          DivElement(classCss="error" v-if="error") {{error}}
          DivElement(classCss="success" v-if="success") {{success}}
          Form(:method="schema.method" :action="schema.url" :onsubmit="saveCategory")
            fieldset
              DivElement(classCss="form__group, group")
                DivElement(classCss="group__main")
                  RowElement
                    DivElement(v-for="(step, index) in schema.steps" :classCss="step.class" :key="index")
                      DivElement(v-for="(input, subIndex) in step.inputs" :key="subIndex" :classCss="input.class" v-if="step.group")
                        DivElement(classCss="form-item__main")
                          DivElement(classCss="form-item__field")
                            InputElement(classCss="form-item__input" :data="input")
                        DivElement(classCss="form-item__action" :onclick="() => deleteInput(subIndex)")
                          SvgElement(:image="input.image")
                      DivElement(v-for="(input, subIndex) in step.inputs" :key="subIndex" :classCss="input.class" v-if="!step.group")
                        DivElement(classCss="form-item__main")
                          DivElement(classCss="form-item__field")
                            DivElement(classCss="buttons")
                              DivElement(classCss="buttons__list")
                                DivElement(v-for="(button, btIndex) in buttons" :key="btIndex" :classCss="button.parentClass")
                                  ButtonElement(:classCss="button.class" :type="button.type" :onclick="button.value") {{button.placeholder}}
</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import SvgElement from '@/js/components/elements/Svg'
  import LinkElement from '@/js/components/elements/Link'
  import SpanElement from '@/js/components/elements/Span'
  import RowElement from '@/js/components/elements/Row'
  import Form from '@/js/components/elements/Form'
  import InputElement from '@/js/components/elements/Input'
  import ButtonElement from '@/js/components/elements/Button'

  import ComputedLoginForm from '@/js/computed/blocks/LoginForm'
  import MethodsCategoryAdd from '@/js/methods/blocks/CategoryAdd'
  import EventLoginForm from '@/js/events/blocks/LoginForm'

  import HttpClass from '@/js/classes/Http'
  import ComputedAddACategory from '@/js/computed/blocks/AddCategoryForm'
  import AppMethods from '@/js/methods/App'

  export default{

    name: 'CategoryAddForm',

    data() {

      let data = this.$store.state.data,
        schema = this.$store.state.schemas.addCategoryArticle

      return {
        data: data,
        schema: schema,
        error: '',
        success: '',
        buttons:[
          {
            name: 'AddCategory',
            parentClass: 'buttons__item',
            type: 'button',
            class: 'btn, btn_tertiary',
            placeholder: 'Добавить категорию',
            value: ()=>this.addField(this.schema)
          },
          {
            name: 'Save',
            parentClass: 'buttons__item buttons__item_second',
            type: 'submit',
            class: 'btn',
            placeholder: 'Сохранить',
            value: '/category/save'
          },
        ],
      }

    },
    methods: {
      ...ComputedLoginForm,
      ...EventLoginForm,
      ...MethodsCategoryAdd,
      ...HttpClass,
      ...ComputedAddACategory,
      ...AppMethods,
      saveCategory(e){

        e.preventDefault()
        let inputs = e.target.querySelectorAll(".form-item__input")
        let inputsData = [];
        inputs.forEach((input)=>{
          if(!input.value){
            this.addError(input)
          }else{
            inputsData.push(input.value)
          }
        })
        let fd = new FormData()
        inputsData.forEach(inputName => {
          fd.append('category_name[]', inputName)
        })
        this.sendRequest({
          method: 'POST',
          url: '/category/add',
          data: fd,
          success: this.addSuccess,
          error: this.handlerErrorResponse
        })

      },
      addSuccess(result){
        if(result.data.code == 0){

          this.appendUserToStore(result.data, false)

          this.$store.commit('showAddArticleCatForm');

          this.success = result.data.desc

          
        }else{
          this.error = result.data.desc
        }
      },
      handlerErrorResponse(result){
        console.log(result)
      },
      addError(input){
        input.classList.add('error')
        this.error = 'Необходимо заполнить название категории, либо удалить ненужные поля'
      }

    },
    components: {
        DivElement, LinkElement, SpanElement, SvgElement, RowElement, Form, InputElement, ButtonElement
      }
  }


</script>

<style lang="scss" scoped>
</style>