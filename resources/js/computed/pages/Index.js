export default{

    classIsModalShown() {

        return this.$store.state.data.app.isModalShown ? 'layout_modal-active' : ''
         
    }

}