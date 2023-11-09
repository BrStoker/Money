
export default{

    state: {

    },
    mutations: {
        showForgotForm(){
            this.state.data.app.isModalShown = !this.state.data.app.isModalShown
            this.state.data.app.isForgotShown = !this.state.data.app.isForgotShown
        },
        closeForgotShowLogin(){
            this.state.data.app.isForgotShown = !this.state.data.app.isForgotShown
            this.state.data.app.isLoginShown = !this.state.data.app.isLoginShown
        },
        showConfirmFromForgot(){
            this.state.data.app.isForgotShown = !this.state.data.app.isForgotShown
            this.state.data.app.isConfirmShown = !this.state.data.app.isConfirmShown
        },
        closeModalLogin(){
            this.state.data.app.isModalShown = !this.state.data.app.isModalShown
            this.state.data.app.isLoginShown = !this.state.data.app.isLoginShown
        },
        closeIntereForm(){
            this.state.data.app.isModalShown = !this.state.data.app.isModalShown
            this.state.data.app.isInterestShown = !this.state.data.app.isInterestShown
        },
        showAddArticleCatForm(){
            this.state.data.app.isModalShown = !this.state.data.app.isModalShown
            this.state.data.app.isCategoryAddShown = !this.state.data.app.isCategoryAddShown
        },
        closeConfirmForm(){
            this.state.data.app.isModalShown = !this.state.data.app.isModalShown
            this.state.data.app.isConfirmShown = !this.state.data.app.isConfirmShown
        },
        closeConfirmShowForgot(){
            this.state.data.app.isConfirmShown = !this.state.data.app.isConfirmShown
            this.state.data.app.isForgotShown = !this.state.data.app.isForgotShown
        },
        closeLoginShowForgot(){
            this.state.data.app.isLoginShown = !this.state.data.app.isLoginShown
            this.state.data.app.isForgotShown = !this.state.data.app.isForgotShown
        },
        closeComplainForm(){
            this.state.data.app.isModalShown = !this.state.data.app.isModalShown
            this.state.data.app.isComplainShown = !this.state.data.app.isComplainShown
        },
        closeConfirmForm(){
            this.state.data.app.isModalShown = !this.state.data.app.isModalShown
            this.state.data.app.isConfirmShown = !this.state.data.app.isConfirmShown
        },
        closeDeleteUserForm(){
            this.state.data.app.isModalShown = !this.state.data.app.isModalShown
            this.state.data.app.isConfirmDeleteUserShown = !this.state.data.app.isConfirmDeleteUserShown
        },
        closeConfirmPasswordFromForgot(){
            this.state.data.app.isForgotShown = !this.state.data.app.isForgotShown
            this.state.data.app.isConfirmPasswordShown = !this.state.data.app.isConfirmPasswordShown
        },
        closeConfirmPassword(){
            this.state.data.app.isModalShown = !this.state.data.app.isModalShown
            this.state.data.app.isConfirmPasswordShown = !this.state.data.app.isConfirmPasswordShown
        }



    },
}
