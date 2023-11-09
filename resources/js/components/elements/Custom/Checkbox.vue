<template lang="pug">
EmptyElement
  DivElement(classCss="form-item__field")
    DivElement(classCss="custom-check")
      LabelElement(classCss="custom-check__label")
        input(:type="data.type" :name="data.name" class="custom-check__input" @change="change" :class="{error: data.error}" :checked="value" :pattern="data.regExp")
        SvgElement(classCss="custom-check__ico, custom-check__ico_before" :image="data.checkboxBefore")
        SvgElement(classCss="custom-check__ico, custom-check__ico_after" :image="data.checkboxAfter")
        SpanElement(classCss="custom-check__text") {{data.text}}

</template>

<script>
import DivElement from '@/js/components/elements/Div'
import SvgElement from '@/js/components/elements/Svg'
import SpanElement from '@/js/components/elements/Span'
import LabelElement from '@/js/components/elements/Label'
import EmptyElement from '@/js/components/elements/Empty'
export default{
	components: {DivElement, SvgElement, SpanElement, LabelElement, EmptyElement},
	props:['data', 'onchange'],
  data(){
	  return {
      step: this.data,
      value: this.data.value
    }
  },
  methods:{
	  change(e){
      this.value = e.target.checked
      this.data.value = this.value
      this.error = ''
      this.onchange()
    }
  },
  mounted(){

  }
}


</script>
