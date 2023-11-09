export default {
    showForm(){
        this.$store.commit('showAddArticleCatForm')
        this.error = ''
    },

    addField: (schema) => {
        schema.steps[0].inputs.push({
            class: 'form-item',
            inputType: 'input',
            name: 'category_title',
            placeholder: 'Название категории',
            send: true,
            image: '/image/svg/sprite.svg#closeSecond',
            value: ''
        })
    },
    deleteInput(index){
        let currentStep = this.schema.steps[0];
        if(currentStep.inputs.length > 1){
            let stepIndex = 0;
            for (let i = 0; i < this.schema.steps.length; i++) {
                if (this.schema.steps[i] === currentStep) {
                    stepIndex = i;
                    break;
                }
            }
            if (currentStep.inputs.length > index) {
                currentStep.inputs.splice(index, 1);
            }
        }
    },

}