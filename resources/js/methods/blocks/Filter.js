export default {

    dataToOptions(){

        let error = true
        let options  = this.$store.state.data.app.filterOptions
        let data = this.$store.state.schemas.filter
        let searchBlock = this.$store.state.schemas.search

        if(_.isObject(data) == true){

            if(_.has(data, 'steps') == true){

                if(_.isArray(data.steps) == true && _.size(data.steps) > 0){
                    error = false
                }
            }

        }
        if(_.isObject(searchBlock) == true){

            if(_.has(searchBlock, 'steps') == true && _.size(searchBlock.steps) > 0){
                error = false
            }
        }

        if (error == false){
            let steps = data.steps

            steps.forEach((item)=>{
                if(item.type == 'radio'){
                    if (item.value != ''){
                        options[item.name] = item.value
                    }
                }else{
                    if(_.has(item, 'inputs') == true){

                        let inputs = item.inputs
                        inputs.forEach((row)=>{
                            if(row.type == 'checkbox'){
                                // console.log('dataToOptions->', row)
                                this.$store.commit('updateFilterOptions', row)
                            }
                            if(row.type == 'select' && row.value != ''){
                                this.$store.commit('updateFilterOptions', row)
                            }
                        })

                    }
                }

            })
            if(_.has(searchBlock, 'steps') == true){
                if(_.isArray(searchBlock.steps) == true && _.size(searchBlock.steps) > 0){
                    searchBlock.steps.forEach((step)=>{
                        if(_.has(step, 'inputs') == true){
                            step.inputs.forEach((input)=>{
                                if(_.has(input, 'value') == true && input.value != ''){
                                    this.$store.commit('updateFilterOptions', input)
                                }
                            })
                        }
                    })
                }
            }
        console.log('',options)
        }
    },

    show_modal_interests(){
        this.$store.commit('closeIntereForm')
    },

    filterList(){
        let fd = this.SchemasToFormData(this.schemas)
        fd.append('action', 'filter')
        let confirmSuccess = (result)=>{
            if(result.data.code == 0){
                this.$store.commit('updateUsers', result.data.users)

            }
            this.$store.commit('clearFilterOptions')
        }
        let handelErrorResponse = (result)=>{
            console.log(result)
        }
        var numberOfKeys = 0;

        for (var key of fd.keys()) {
            numberOfKeys++;
        }
        if(numberOfKeys > 1){
            this.$store.state.data.app.filter = true
        }else{
            this.$store.state.data.app.filter = false
        }

        this.sendRequest({
            method: 'POST',
            url: "/people/search",
            data: fd,
            success: confirmSuccess,
            error: handelErrorResponse
        })


    },
}