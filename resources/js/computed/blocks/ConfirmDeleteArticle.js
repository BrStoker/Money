export default {

    classIsConfirmShown() {

        return this.$store.state.data.app.isConfirmShown ? 'modal__layout_active' : ''

    }



}