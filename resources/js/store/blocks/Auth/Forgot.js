export default {

    mutations:{
        closeForgotForm(){
            this.$store.state.data.app.isModalShown = !this.$store.state.data.app.isModalShown
            this.$store.state.data.app.isForgotShown = !this.$store.state.data.app.isForgotShown
        },
        showForgotShowLogin(){
            // this.$store.state.data.app.isModalShown = !this.$store.state.data.app.isModalShown
            this.$store.state.data.app.isForgotShown = !this.$store.state.data.app.isForgotShown
            // this.$store.state.data.app.isModalShown = !this.$store.state.data.app.isModalShown
            this.$store.state.data.app.isLoginShown = !this.$store.state.data.app.isLoginShown
        }

    }

}