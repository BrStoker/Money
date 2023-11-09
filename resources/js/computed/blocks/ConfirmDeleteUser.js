export default {

    classIsConfirmDeleteUserShown() {

        return this.$store.state.data.app.isConfirmDeleteUserShown ? 'modal__layout_active' : ''

    }



}