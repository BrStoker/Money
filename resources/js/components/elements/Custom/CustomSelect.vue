<template lang="pug">
select(v-if="data.name == 'country_id'" :value="data.value" id="country_id" @choice="change" @change="onchange")
select(v-else-if="cities" id="city_id" :value="data.value" @search="fetchOptions" @choice="change" @change="onchange")

</template>

<script>

  import EmptyElement from '@/js/components/elements/Empty'

  import Select2 from 'v-select2-component'
  import VueSelect from 'vue-select'
  import Choices from 'choices.js';

  import 'vue-select/dist/vue-select.css';
	import HttpClass from '@/js/classes/Http'

  export default{
    components: {Select2, VueSelect, EmptyElement, Choices},
    props:['data','cities', 'onchange'],
    data() {

      return{
				countries:  this.$store.state.data.app.countries,
        myValue: '',
        city: this.cities,
        tempChoices: {
          country: undefined,
          city: undefined
        },
        settings: '{width: -webkit-fill-available}',
				key: 0,
      }

    },
		methods:{
			...HttpClass,
			selectCity(e){
				this.data.value=e.code
        this.onchange(e)
			},
			fetchOptions(e){
				this.data.value=e.code
				const form_data= new FormData();
        form_data.append('country_id',this.$store.state.schemas.filter.steps[2].inputs[0].value);
				form_data.append('q', e.detail.value);
				this.sendRequest({
						method:'post',
						url: '/city',
						data: form_data,
						success: this.RegisterSuccess,
						error: this.handelErrorResponse
					})
				},

      change(e){

        this.data.value = e.detail.choice.value

      },

      RegisterSuccess(result) {


          const cities =  result.data.data.map(item=>{
            return {
              code:item.id,
              label:item.value
            }
          })
          this.$store.commit('setCities',cities)
          let selectCity = document.querySelector('#city_id')
          if(this.tempChoices.city == undefined) {

            this.tempChoices.city = new Choices(selectCity, {
              silent: true,
              choices: this.$store.state.data.app.cities,
              noChoicesText: 'Ничего не выбрано',
              noResultsText: 'Ничего не найдено',
              searchEnabled: true,
              searchChoices: true,
              searchFloor: 1,
              loadingText: 'Поиск...',
              searchResultLimit: 4,
              searchFields: ['label'],
              placeholder: true,
              placeholderValue: 'Укажите значение',
              itemSelectText: '',
              allowHTML: false
            })

          }else{
            this.tempChoices.city.clearChoices()
            this.tempChoices.city.setChoices(this.$store.state.data.app.cities, 'value', 'label', true)
          }
			},
			selectCountry(e){
			  if(e){
          this.data.value = e.target.value
        }else{
          this.data.value = ''
        }
        const form_data= new FormData();
        form_data.append('country_id', this.data.value)
        this.sendRequest({
            method:'post',
            url: '/city',
            data: form_data,
            success: this.RegisterSuccess,
            error: this.handelErrorResponse
          })
			},

			handelErrorResponse(result) {
				console.error('RegisterError -> ', result)
			},
      clearCities(){
			  this.$store.commit('clearCities')
        this.onchange()
      },
      initCountry(){

        if(this.data.name == 'country_id'){

          const selectCountry = document.querySelector('#country_id')

          let countries = this.countries
          let items = []
          let value = null
          let label = 'Укажите значение'
          let obj = {value, label}
          obj.selected = true
          obj.hidden = true
          items.push(obj)
          for(let country in countries){
            let value = countries[country].value.toString()
            let label = countries[country].label.toString()
            let obj = {value, label}
            if(countries[country].value == this.data.value){
              obj.selected = true
            }
            items.push(obj)
          }

          if(this.tempChoices.country == undefined) {

            this.tempChoices.country = new Choices(selectCountry,{
              silent: true,
              choices: items,
              noChoicesText: 'Ничего не выбрано',
              noResultsText: 'Ничего не найдено',
              placeholder: true,
              placeholderValue: 'Укажите значение',
              itemSelectText: '',
              loadingText: 'Загрузка...',
              allowHTML: false
            })

          }
          if(this.tempChoices.country.getValue() != undefined){
            this.initCity(this.tempChoices.country.getValue().value)
          }

          selectCountry.addEventListener('change', (event)=>{
            if(this.tempChoices.city != undefined){
              this.tempChoices.city.removeActiveItems()
            }
            this.initCity(event.target.value)
          })

        }

      },

      initCity(country_id){

        let sendData = new FormData()
        sendData.append('country_id', country_id)
        this.sendRequest({
          method: 'POST',
          url: '/city',
          data: sendData,
          success: this.citySuccess,
          error: this.cityError
        })

      },

      citySuccess(result){
			  var city_id
        if(this.data.hasId){
          city_id = this.$store.state.data.app.user.data.city_id
        }

        let items = []
        let value = null
        let label = 'Укажите значение'
        let obj = {value, label}
        obj.selected = true
        items.push(obj)

        const cities =  result.data.data.map(item=>{
          if (city_id == item.id){
            return {
              value: item.id,
              label: item.value,
              selected: true
            }
          }else{
            return {
              value: item.id,
              label: item.value
            }
          }
        })

        this.$store.commit('setCities', cities)

        let selectCity = document.querySelector('#city_id')
        if(this.tempChoices.city == undefined) {

          this.tempChoices.city = new Choices(selectCity, {
            choices: this.$store.state.data.app.cities,
            noChoicesText: 'Ничего не выбрано',
            noResultsText: 'Ничего не найдено',
            placeholder: true,
            placeholderValue: 'Укажите значение',
            itemSelectText: '',
            allowHTML: false
          })

        }
        this.tempChoices.city.clearChoices()
        this.tempChoices.city.setChoices(this.$store.state.data.app.cities, 'value',
            'label',
            false,)
      },
      cityError(result){
			  console.error(result)
      }

		},
    mounted(){

      this.initCountry()
    }
  }

</script>

<style lang="scss">
.choices{
  margin-top: 16px;
}

</style>