
export default {
    
    choisesChangeChild(event) {

      let item = event.target

      let parent = item.closest('.choices')

      if(parent != null) {

        let option = item.querySelectorAll('option')

        if(option.length) parent.setAttribute('data-child', true)
          else parent.removeAttribute('data-child', true)

      }
    },
    choisesDropdownTruck(event){

      let item = event.target

      item.style.left = '-100%'
      
      setTimeout(() => { item.style.left = 'auto' }, 1)

    },
    choisesChangeCountryCity(event) {

      if(window.choisesItems != undefined && Object.keys(window.choisesItems).length > 0) {
                    
        let item = event.target
        
        let itemParent = item.closest('form'),
          name = item.getAttribute('name')

        if(name != null && name.indexOf('country_id')+1 != 0) {

          let countries = [],
            options = Array.from(item.options)

          if(options.length > 0)
            options.forEach(function(element){ if(element.value.length > 0) countries.push(parseInt(element.value)) })

          for(let index in window.choisesItems) {

            let subItem = window.choisesItems[index]

            if(subItem.length > 0) {
              
              subItem.forEach(function(subSubItem){

                if(index.indexOf('city')+1 != 0) {

                  let subSubItemParent = subSubItem.passedElement.element.closest('form')

                  if(itemParent === subSubItemParent) {

                    let fd = new FormData()

                    if(countries.length > 0)
                      countries.forEach(function (country){ fd.append('countries[]', country) })

                    window.axios({ method: 'POST', url: '/city/get', data: fd, headers: { 'Content-Type': 'application/json' },}) .then((result) => {
                      
                      if(result.status == 200 || result.status == 301 || result.status == 302) {

                        if(result.data.cities != undefined && Object.keys(result.data.cities).length > 0 ) {

                          let choises = subSubItem.dropdown.element.querySelectorAll('.choices__item')
                          let choisesDefault = subSubItem.dropdown.element.querySelector('.choices__item[data-value=""],.choices__item[data-value="0"]')

                          // console.log(choises,choisesDefault)

                          if(choises && choisesDefault) {

                            let currentItem = subSubItem.getValue(true);
                            
                            subSubItem.clearChoices()

                            let newItems = [];

                            let choisesDefaultValue = choisesDefault.getAttribute('data-value')

                            if(choisesDefaultValue != null && choisesDefaultValue.length > 0) {
                              choisesDefaultValue = 0;
                            }

                            newItems.push({
                              value: ( choisesDefaultValue ? choisesDefaultValue : "0" ),
                              label: choisesDefault.innerHTML,
                              selected: false
                            });

                            if(currentItem.length == 0)
                              currentItem = "0";

                            for(let index in result.data.cities) {

                              let element = result.data.cities[index]

                              if(element.id != undefined && element.value != undefined) {

                                newItems.push({ value: element.id, label: element.value, selected: false})

                              }

                            }

                            if(newItems != undefined && newItems.length > 0) {

                              subSubItem.setValue(newItems);

                              subSubItem.setChoiceByValue(String(currentItem));

                              if(subSubItem.getValue(true) != currentItem) {

                                subSubItem.setChoiceByValue(parseInt(currentItem));

                              }

                            }

                          }

                        }

                      }
                    });

                  }

                }

              })

            }

          }

        }

      }

    },
    choises(){

      let select_options = {
        renderChoiceLimit: 0,
        allowHTML: false,
        editItems: false,
        duplicateItemsAllowed: false,
        delimiter : ',',
        removeItemButton: true,
        searchEnabled: false,
        searchChoices: false,
        position: 'bottom',
        resetScrollPosition: true,
        noChoicesText: 'Нет результатов для выбора',
        itemSelectText: '',
        addItemText: '',
        maxItemText: 'Выбрано максимальное кол-во результатов',
        shouldSort: false,
        shouldSortItems: false
      };

      let select_choises = document.querySelectorAll('.select_choises');

      if(select_choises != null) {

        select_choises.forEach((item, index) => {

          let dataChoice = item.getAttribute('data-choice')

          if(dataChoice == null) {
            
            let name = item.getAttribute('name')

            if(name != null && name.length > 0)
            {

              let choise = new window.choises(item, select_options);

              item.addEventListener( 'change', this.choisesChangeChild, false, );
              item.addEventListener( 'change', this.choisesChangeCountryCity, false, );
              item.addEventListener( 'showDropdown', this.choisesDropdownTruck, false, );

              if(window.choisesItems.hasOwnProperty(name) == false)
                Object.defineProperty(window.choisesItems, name,{ writable: true, enumerable: true, configurable: true, value: [] });

              window.choisesItems[name].push(choise)

            }
            
          }

        });

      }

      if(window.choisesItems != undefined && Object.keys(window.choisesItems).length > 0) {

        for(let index in window.choisesItems) {

          if(index.indexOf('country_id')+1 != 0) {

            let subItem = window.choisesItems[index]

            if(subItem.length > 0) {

              subItem.forEach((element) => {

                this.choisesChangeCountryCity({target : element.passedElement.element})


              })

            }

          }

        }

      }
      
    },

    getOptions(data, indexOption, startOption = undefined) {

      let result = []

      if(startOption != undefined)
        result.push(startOption)

      if(data.hasOwnProperty(indexOption) && Object.keys(data[indexOption]).length > 0) {

        for(let index in data[indexOption]) {

          let item = data[indexOption][index]

          let itemResult = { text: (item.value != undefined ? item.value : item), value: (item.id != undefined ? item.id : (item.value != undefined ? item.value : item)) }

          for(let subIndex in item) {

            if(subIndex != 'id' && subIndex != 'value') {

              if(itemResult.data == undefined)
                Object.defineProperty(itemResult, 'data', { writable: true, enumerable: true, configurable: true, value: {  } })

              Object.defineProperty(itemResult.data, subIndex, { writable: true, enumerable: true, configurable: true, value: item[subIndex] })

            }

          }
          
          result.push(itemResult);

        }

      }

      return result;
    },

    prepareRowsInputs(inputs, data, userType, platformCode = undefined) {
      
      if(inputs.length != undefined && inputs.length > 0) {

          inputs.forEach((element) => {

            if(element.length == undefined) {

              if(element.prepareFunction != undefined) {
                this[element.prepareFunction](element, data, userType, platformCode)
              }
              else if(element.prepareObject != undefined && element.prepareName != undefined && element.type != undefined) {

                  let tmp = false

                  if(platformCode != undefined) {

                    if(data[element.prepareObject] != undefined &&
                      data[element.prepareObject][platformCode] != undefined &&
                        data[element.prepareObject][platformCode][userType] != undefined &&
                        data[element.prepareObject][platformCode][userType][element.prepareName] != undefined) {

                      tmp = data[element.prepareObject][platformCode][userType][element.prepareName];
                    
                    }
                    
                  }
                  
                  if(!tmp) {

                    if(data[element.prepareObject] != undefined &&
                      data[element.prepareObject][element.prepareName] != undefined &&
                        data[element.prepareObject][element.prepareName][userType] != undefined) {

                      tmp = data[element.prepareObject][element.prepareName][userType];
                    
                    }

                  }

                  if(tmp != false) {

                    if(element.type == 'text') {
                      element.value = tmp;
                    } else if(element.type == 'select') {

                      if(element.values != undefined && element.values.length != undefined && element.values.length) {

                        element.values.forEach(function(option) {

                          let selected = false

                          if(tmp.length != undefined) {

                            if(tmp.includes(String(option.value)))
                              selected = true;

                          } else {

                            if(tmp == String(option.value))
                              selected = true;

                          }

                          if(selected == true)
                            Object.defineProperty(option, 'selected',{ configurable:true, writable: true, enumerable: true, value: 'selected' });

                        });

                      }

                    }

                  }

              }
              
            }

          });

      }

      return inputs;

    }

}