<template lang="pug">
DivElement(classCss="form-item")
  DivElement(classCss="form-item__main")
    DivElement(classCss="form-item__field")
      select(class="select" :name="data.name" @change="onChange")

</template>

<script>
import DivElement from '@/js/components/elements/Div'
import VueSelect from 'vue-select'
import Choices from 'choices.js';
import 'vue-select/dist/vue-select.css';
export default{

	props:['data', 'onchange'],
  data(){
	  return{
      simpleSelect: {
        min_age: undefined,
        max_age: undefined,
        reason: undefined,
        gender: undefined
      }
    }
  },
  components: {
    DivElement,
    VueSelect,
    Choices
  },
  methods:{
    onChange(e) {
      this.data.value = e.target.value
      this.onchange()
    },
    initSelect(){
      if(this.data.name == 'age_min'){
        var items = []
        var value = null
        var label = 'Укажите значение'
        var obj = {value, label}
        obj.selected = true
        obj.hidden = true
        items.push(obj)
        for (let option in this.data.options){
            if (this.data.options.hasOwnProperty(option)){
              let value = this.data.options[option].value.toString()
              let label = this.data.options[option].label.toString()
              let obj = {value, label}
              if(this.data.value){
                if(this.data.options[option].value == this.data.value){
                  obj.selected = true
                }
              }
              items.push(obj)

            }
          }
        var selectMinAge = document.querySelector('.select[name="age_min"]')
        if(selectMinAge){
          if(this.simpleSelect.min_age == undefined){
            this.simpleSelect.min_age = new Choices(selectMinAge, {
                    searchEnabled: false,
                    noChoicesText: 'Ничего не найдено',
                    allowHTML: false,
                    placeholder: true,
                    placeholderValue: this.data.placeholder,
                    itemSelectText: '',
                    choices: items
                  });
          }else{
            this.simpleSelect.min_age.clearChoices()
            this.simpleSelect.min_age.setChoices(items, 'value',
                    'label',
                    false,)
          }
        }
      }
      if(this.data.name == 'age_max'){
        var items = []
        var value = null
        var label = 'Укажите значение'
        var obj = {value, label}
        obj.selected = true
        obj.hidden = true
        items.push(obj)
        for (let option in this.data.options){
          if (this.data.options.hasOwnProperty(option)){
            let value = this.data.options[option].value.toString()
            let label = this.data.options[option].label.toString()
            let obj = {value, label}
            if(this.data.value){
              if(this.data.options[option].value == this.data.value){
                obj.selected = true
              }
            }
            items.push(obj)

          }
        }
        var selectMaxAge = document.querySelector('.select[name="age_max"]')
        if(selectMaxAge){
          if(this.simpleSelect.max_age == undefined){
            this.simpleSelect.max_age = new Choices(selectMaxAge, {
              searchEnabled: false,
              noChoicesText: 'Ничего не найдено',
              allowHTML: false,
              placeholder: true,
              placeholderValue: this.data.placeholder,
              itemSelectText: '',
              choices: items
            });
          }else{
            this.simpleSelect.max_age.clearChoices()
            this.simpleSelect.max_age.setChoices(items, 'value',
                'label',
                false,)
          }

        }

      }
      if(this.data.name == 'reason'){
        var items = []
        var value = null
        var label = 'Укажите значение'
        var obj = {value, label}
        obj.selected = true
        obj.hidden = true
        items.push(obj)
        for (let option in this.data.options){
          if (this.data.options.hasOwnProperty(option)){
            let value = this.data.options[option].value.toString()
            let label = this.data.options[option].label.toString()
            let obj = {value, label}
            if(this.data.value){
              if(this.data.options[option].value == this.data.value){
                obj.selected = true
              }
            }
            items.push(obj)

          }
        }
        var selectReason = document.querySelector('.select[name="reason"]')
        if(selectReason){
          if(this.simpleSelect.reason == undefined){
            this.simpleSelect.reason = new Choices(selectReason, {
              searchEnabled: false,
              noChoicesText: 'Ничего не найдено',
              allowHTML: false,
              placeholder: true,
              placeholderValue: this.data.placeholder,
              itemSelectText: '',
              choices: items
            });
          }else{
            this.simpleSelect.reason.clearChoices()
            this.simpleSelect.reason.setChoices(items, 'value',
                'label',
                false,)
          }

        }

      }
      if(this.data.name == 'gender'){
        var items = []
        var value = null
        var label = 'Укажите значение'
        var obj = {value, label}
        obj.selected = true
        obj.hidden = true
        items.push(obj)
        for (let option in this.data.options){
          if (this.data.options.hasOwnProperty(option)){
            let value = this.data.options[option].value.toString()
            let label = this.data.options[option].label.toString()
            let obj = {value, label}
            if(this.data.value){
              if(this.data.options[option].value == this.data.value){
                obj.selected = true
              }
            }
            items.push(obj)

          }
        }
        var selectGender = document.querySelector('.select[name="gender"]')
        if(selectGender){
          if(this.simpleSelect.gender == undefined){
            this.simpleSelect.gender = new Choices(selectGender, {
              searchEnabled: false,
              noChoicesText: 'Ничего не найдено',
              allowHTML: false,
              placeholder: true,
              placeholderValue: this.data.placeholder,
              itemSelectText: '',
              choices: items
            });
          }else{
            this.simpleSelect.gender.clearChoices()
            this.simpleSelect.gender.setChoices(items, 'value',
                'label',
                false,)
          }

        }


      }

    }

  },
  mounted(){
	  this.initSelect()
	}
}

</script>

<style>
.choices{
  width: 100%;
  margin-top: 16px;
}
.choices__inner{
  color: rgb(8, 22, 14);
  background-color: rgb(255, 255, 255);
  font-size: 1rem;
  line-height: 1.25rem;
  font-weight: 500;
  font-family: "Montserrat", sans-serif;
  border-radius: 0.5rem;
  border: 0.125rem solid rgba(8, 23, 14, 0.2);

}
.is-open .choices__inner{
  color: rgb(8, 22, 14);
  background-color: rgb(255, 255, 255);
  font-size: 1rem;
  line-height: 1.25rem;
  font-weight: 500;
  font-family: "Montserrat", sans-serif;
  border-radius: 0.5rem;
  border: 0.125rem solid rgba(8, 23, 14, 0.2);

}
.is-focused .choices__inner{
  color: rgb(8, 22, 14);
  border-color: rgb(11, 118, 54);
}
.is-highlighted{
  background-color: rgb(11, 118, 54);
}

.choices__list--dropdown .choices__item--selectable,.choices__list[aria-expanded] .choices__item--selectable {
  padding-right: 10px
}

</style>