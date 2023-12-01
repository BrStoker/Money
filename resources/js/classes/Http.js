export default {

    SchemasToFormData(schema) {

        let result = null

        if(_.isObject(schema) == true && _.size(schema) ) {

            let formData = new FormData(),
                inputs = []
            
            if(_.has(schema, 'steps') == true){

                _.forEach(schema.steps, function(item){
                    if(item.name == 'interest' || item.name == 'fio'){
                        inputs.push(item)
                        return
                    }


                    if(item.type == 'radio'){
                        inputs.push(item)
                    }else{

                        if(_.has(item, 'inputs') == true) {

                            if(_.isArray(item) == true) {

                                _.forEach(item.inputs, function(subItem){

                                    inputs.push(subItem);

                                })

                            } else if(_.isObject(item.inputs) == true) {

                                _.forIn(item.inputs, function(subItem){



                                    if(_.has(subItem, 'inputs') == true){
                                        for (let key in subItem.inputs){
                                            inputs.push(subItem.inputs[key])
                                        }
                                    }else{
                                        inputs.push(subItem)
                                    }


                                })

                            }
                        }

                    }

                })
            } else if(_.has(schema, 'inputs') == true) {

                inputs = schema.inputs


            }

            if(_.size(inputs) > 0) {

                _.forEach(inputs, function(item) {
                    if(_.has(item,'name') == true && _.has(item, 'value')) {
                        if(_.has(item,'send') == true && item.send == false){
                            return
                        }

                        if(_.isArray(item.value) || _.isObject(item.value) == true) {
                            if(_.size(item.value) > 0) {
                                _.forEach(item.value, function(value){
                                    formData.append(item.name + '[]', value)
                                })
                            }
                        } else {
                            if(item.type == 'checkbox'){
                                if(item.send == true){
                                    formData.append(item.name, item.value)
                                }else{
                                    if(item.value === true){
                                        formData.append(item.name, item.value)
                                    }
                                }
                            }else{

                                if(item.value != null && item.value.length != 0 && item.value != false){

                                    formData.append(item.name, item.value)

                                }
                            }

                        }
                    }else{

                    }

                })

            }

            result = formData

        }

        return result

    },

    sendRequest(params = {}) {

        let settings = { method: null, url: null, data: null, headers: null},
            success = (result) => { },
            error = (result) => { console.error(result) }

        _.forEach(['method', 'url'], function(value){
            if(_.has(params, value) == false){
                console.error('sendRequest -> ' + value)
            } else {
                settings[value] = params[value]
            }
        })
        if(_.has(params, 'data') == true && !(params.data instanceof FormData)){
            settings.data = new FormData();
        } else {
            settings.data = params.data
        }

        settings.data.append('_token', this.csrf())
        if(_.has(params, 'headers') == true) {
            settings.headers = params.headers
        }

        if(_.has(settings.headers, 'Content-Type') == false) {
            _.set(settings, 'headers.Content-Type', 'multipart/form-data')
        }

        if(_.has(params, 'success') == true && _.isFunction(params.success) == true) {
            success = params.success
        }

        if(_.has(params, 'error') == true && _.isFunction(params.error) == true) {
            error = params.error
        }

        window.axios(settings)
        .then((result) => {
            if(result.status == 200 || result.status == 301 || result.status == 302) {
                success(result)
            } else {
                error(result)
            }
        }) .catch((result) => {
            error(result)
        })

    },
    csrf() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },

   confirmCodeToInt(form){
        let code = ''

        if(form != null && form.tagName === 'FORM'){
            let inputsForm = form.querySelectorAll('input')
            inputsForm.forEach(input => {
                if(input.name != '_token'){
                    code = code + input.value
                }
            })
        }
        return code

    },
   



}