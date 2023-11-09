export default {

    classIsComplainShown() {

        return this.$store.state.data.app.isComplainShown ? 'modal__layout_active' : ''

    },
    buttonText(){
        return this.$store.state.data.app.filter ? 'Применить' : this.$store.state.schemas.interestsReg.button_submit.text
    },




}