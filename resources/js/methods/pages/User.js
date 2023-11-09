export default{

    getSocials(data){
        let socials = []
        let that = data
        for (let key in that){

            let items = that[key]
            if(key == 'socials'){
                Object.keys(items).forEach(item=>{
                    socials[item] = items[item]
                })
            }
        }
        return socials
    },
    Fio(User)
    {

        return [User.first_name, User.last_name].join(' ')

    },

    profileToFormData(schema){

        let result = null

        if(_.isObject(schema) == true && _.size(schema)){

            let formData = new FormData(),
                inputs = []

            if(_.has(schema, 'steps') == true){

                _.forEach(schema.steps, function(step){
                    if(_.has(step, 'inputs')){
                        _.forEach(step.inputs, function(input){
                            if(input.send == true){
                                inputs.push(input)
                            }
                        })

                    }
                })


            }

            if(_.size(inputs) > 0){

                _.forEach(inputs, function(input){
                    if(_.has(input, 'name') == true && _.has(input, 'value') == true){
                        formData.append(input.name, input.value)
                    }
                })


            }

            result = formData

        }


        return result



    }
}