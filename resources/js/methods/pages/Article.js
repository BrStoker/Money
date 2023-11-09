export default{

    showAddForm(){
        this.$store.commit('showAddArticleCatForm')
    },

    appendErrorToSchema(schema, data){

        let error = true

        if(_.has(schema, 'steps') == true) {

            if( _.isArray(schema.steps) == true && _.size(schema.steps) > 0 && _.isObject(data) == true && _.size(data) > 0) {
                error = false
            }

        }
        if(error == false){
            for(let row in schema.steps){
                if(_.has(schema.steps[row], 'inputs') == true){
                    if(_.isArray(schema.steps[row].inputs) == true && _.size(schema.steps[row].inputs) > 0){
                        _.forEach(schema.steps[row].inputs, function(item){
                            for(let key in data){
                                if(item.name == key){
                                    item.error = data[key][0]
                                }
                            }
                        })
                    }
                }

            }
        }

    }
}