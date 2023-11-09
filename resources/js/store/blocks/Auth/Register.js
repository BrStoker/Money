export default {

    state: {
        images: {

        },
        step: 1,
        userID: '',
        text: {
            title: 'Зарегистрироваться'
        },
        urls: {
    
        }
    },
    mutations:{
        changeStep(state, data){

            this.state.data.header.blocks.register.step = 2
            this.state.data.header.blocks.register.userID = data.userId
        },
        reloadStep(state){
            this.state.data.header.blocks.register.step = 1
            this.state.data.header.blocks.register.userID = ''
        }
    }

}