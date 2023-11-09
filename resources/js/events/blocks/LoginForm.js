export default {
    handlerHeaderTab(index) {
        this.activeTab = index
    },

    showConfirm(){
        this.$store.state.data.app.isModalShown = !this.$store.state.data.app.isModalShown
        this.$store.state.data.app.isLoginShown = !this.$store.state.data.app.isLoginShown
        this.$store.state.data.app.isModalShown = !this.$store.state.data.app.isModalShown
        this.$store.state.data.app.isConfirmShown = !this.$store.state.data.app.isConfirmShown
    }
}