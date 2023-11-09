export default {
    closeForm(){
        this.$store.commit('showForgotForm')
    },
    showLoginForm(){
        this.$store.commit('closeForgotShowLogin')
    },
    showConfirmForm(){
        this.$store.commit('showConfirmFromForgot')
    }




}