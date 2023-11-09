export default {

    classIsInterestShown() {

        return this.$store.state.data.app.isInterestShown ? 'modal__layout_active' : ''

    },
    buttonText(){
        return this.$store.state.data.app.filter ? 'Применить' : this.$store.state.schemas.interestsReg.button_submit.text
    },
    itsNew(){
        if(this.$store.state.data.app.user.auth && this.$store.state.data.app.user.data.new){
            this.newUser = true
        }
    },
    isFilter(){
        //Временное решение!!!!
        return window.location.pathname == '/people'
        // return this.$store.state.data.app.filter
    },



}