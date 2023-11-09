export default {

    arrayAssign(array) {
        return JSON.parse(JSON.stringify(array))
    },

    appendErrorsToSchema(schema, data){


        let error = true

        if(_.has(schema, 'steps') == true && _.isObject(data)){
            error = false
        }

        if(error == false){
            for(let step in schema.steps){
                if(_.has(schema.steps[step], 'inputs') == true){
                    _.forEach(schema.steps[step].inputs, function(input, key){
                        for (let row in data){
                            if(input.error){
                                input.error = ''
                            }
                            if(row == key){
                                input.error = data[key]
                            }else{
                                if(input.name == row){
                                    input.error = data[row]
                                }
                            }
                        }
                    })

                }
            }

        }



    },

    clearErrorsInSchema(schema){

        let error = true

        if(_.has(schema, 'steps') == true){
            error = false
        }

        if(error == false){

            for(let step in schema.steps){
                if(_.has(schema.steps[step], 'inputs') == true){
                    _.forEach(schema.steps[step].inputs, function(input){
                        input.error = ''
                    })
                }
            }


        }


    }

}