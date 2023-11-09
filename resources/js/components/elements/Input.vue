<template lang="pug">
EmptyElement(v-if="data.inputType == 'checkbox'")
  DivElement(:classCss="data.classCss")
    CheckboxInput(:data="data" :onchange="onchange")
EmptyElement(v-else-if="data.inputType == 'radio'")
  DivElement(:classCss="data.classCss")
    RadioInput(:data="step" :input="data" :onchange="onchange")
EmptyElement(v-else-if="data.inputType == 'select_geoposition'")
  CustomSelect(:cities="$store.state.data.app.cities" :data="data" :onchange="onchange")
EmptyElement(v-else-if="data.inputType == 'input'")
  CustomInput(:data="data" :input="data" :onchange="onInputChange")
EmptyElement(v-else-if="data.inputType == 'number'")
  CustomInput(:data="data" :input="data" :onchange="onchange")
EmptyElement(v-else-if="data.inputType == 'submit'")
  DivElement(classCss="form-item")
    CustomButton(:data="data")
EmptyElement(v-else-if="data.inputType == 'option'")
  DivElement(classCss="row, row_second")
    DivElement(:classCss="data.class")
      SimpleSelect(:data="data" :onchange="onchange")
EmptyElement(v-else-if="data.inputType == 'link'")
  p(class="dropdown-init" style="width: 100%")
    SpanElement(classCss="modal-init" :onclick="onclick") {{data.placeholder}}
    DivElement(classCss="menu__dropdown, layout__dropdown, dropdown" style="width: 382px; left: 0" v-if="data.delete")
      DivElement(classCss="dropdown__list" style="padding: 16px;")
        DivElement(classCss="dropdown__item")
          SpanElement(classCss="title__text") {{'Вы можете отменить удаление страницы в течении 30 дней'}}
        DivElement(classCss="dropdown__item" style="margin-top: 12px")
          btnDeleteUser
EmptyElement(v-else-if="data.inputType == 'supportLink'")
  SupportBlock
EmptyElement(v-else-if="data.inputType == 'textArea'")
  Editor(:data="data" :onchange="onchange")
EmptyElement(v-else-if="data.inputType == 'button'")
  CustomButton(:data="data" :onclick="onclick")
EmptyElement(v-else-if="data.inputType == 'file'")
  Avatar(:user="user" :input="data" :onchange="onchange")
EmptyElement(v-else-if="data.inputType == 'image'")
  ImageBlock(:user="user" :input="data" :onchange="onchange")
  //  slot
  //EmptyElement(v-else)
  //  input(v-if="type == 'file'" @change="onInputChange" :id="id" :class="className" :required="isRequired" :disabled="isDisabled" :name="name" :type="type" :multiple="multiple" :style="styleCss" :accept="accept")
  //  input(v-else-if="type" v-model="input.value" @blur="OnBlur" @focus="OnFocus" @keyup="OnKeyUp" :id="id" :class="className" :required="isRequired" :disabled="isDisabled" :name="name" :type="getType" :multiple="multiple" :maxlength="maxlength" :placeholder="placeholder" :min="min" :max="max" :autocomplete="autocomplete" :checked="isChecked" :style="styleCss" :readonly="readonly")
  //  DivElement(classCss="form-item__ico" v-if="input.type == 'password'")
  //    SvgElement(:image="image" :onclick="togglePasswordVisibility")

</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'
  import MethodsElements from '@/js/methods/elements/Input'
  import EventsInput from '@/js/events/elements/Input'


  import ImageElement from '@/js/components/elements/Image'
  import LabelElement from '@/js/components/elements/Label'
  import EmptyElement from '@/js/components/elements/Empty'
  import DivElement from '@/js/components/elements/Div'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'
	import RadioInput from '@/js/components/elements/Custom/RadioInput'
	import CheckboxInput from '@/js/components/elements/Custom/Checkbox'
  import Select2 from 'v-select2-component'
  import CustomInput from '@/js/components/elements/Custom/CustomInput'
  import CustomButton from '@/js/components/elements/Custom/Button'
  import CustomSelect from '@/js/components/elements/Custom/CustomSelect'
  import SimpleSelect from '@/js/components/elements/Custom/SimpleSelect'
  import SupportBlock from '@/js/components/blocks/Support'
  import Editor from '@/js/components/elements/Custom/EditorArea'
  import Avatar from '@/js/components/blocks/profile/user/Avatar'
  import ImageBlock from '@/js/components/blocks/Image'
  import btnDeleteUser from '@/js/components/elements/Custom/ButtonDeleteUser'

  export default {
    name: 'InputElement',
    props: ['data', 'step', 'onclick', 'user', 'onchange'],
    components:{
      ImageElement,
      LabelElement,
      DivElement,
      SvgElement,
      EmptyElement,
      RadioInput,
      CheckboxInput,
      Select2,
      CustomInput,
      CustomButton,
      CustomSelect,
      SimpleSelect,
      SpanElement,
      SupportBlock,
      Editor,
      Avatar,
      ImageBlock,
      btnDeleteUser
    },
    computed:
    {
      ...ComputedElements,
    },
    methods: {
      ...MethodsElements,
      ...EventsInput,
      ...{togglePasswordVisibility() {
        this.show = !this.show;
      },
      onInputChange(event) {
        if (this.element !== undefined) {
          if (typeof this.onchange === 'function') {
            this.onchange(event, this.element);
          }
        } else {
          if (typeof this.onchange === 'function') {
            this.onchange(event);
          }
        }
      }
      }
    },
    mounted(){

    }
  }
</script>

<style lang="scss">
</style>