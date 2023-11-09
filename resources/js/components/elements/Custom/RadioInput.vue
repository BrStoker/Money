<template lang="pug">
DivElement(classCss="form-item__main")
  DivElement(classCss="form-item__field")
    DivElement(classCss="custom-check")
      LabelElement(classCss="custom-check__label")
        Input(class="custom-check__input" @change="change" :type="data.type" v-model="data.value" :id="input.value" :name="data.name")
        SvgElement(:image="input.radioBefore" classCss="custom-check__ico, custom-check__ico_before" )
        SvgElement(:image="input.radioAfter" classCss="custom-check__ico, custom-check__ico_after")
        SpanElement(classCss="custom-check__text") {{input.text}}
</template>

<script>
import DivElement from '@/js/components/elements/Div'
import SvgElement from '@/js/components/elements/Svg'
import LabelElement from '@/js/components/elements/Label'
import SpanElement from '@/js/components/elements/Span'
export default{
	components: {DivElement, SvgElement, LabelElement, SpanElement},
	props:['data', 'input', 'onchange'],
  // data(){
	//   return{
  //     Input: this.input,
  //     step: this.data
  //   }
  // },
	methods:{
		change(e){
			this.data.value = this.input.value
      this.onchange()
		}
	},
  mounted(){
	
  }
}

</script>
