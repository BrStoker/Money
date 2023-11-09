export default {
    showForm(){
        this.errors = ''
        this.$store.commit('closeModalLogin')
        this.activeTab = 0
        this.clearErrorsInSchema(this.$store.state.schemas.header.blocks.login)
        this.clearErrorsInSchema(this.$store.state.schemas.header.blocks.register.steps.StepOne)
    }

}