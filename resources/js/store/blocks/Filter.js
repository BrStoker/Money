
import Login from '@/js/store/blocks/Auth/Login'
import Register from '@/js/store/blocks/Auth/Register'

export default{

    state: {
        isInterestModalShown: false,
        images: {
            button_remove: '/image/svg/sprite.svg#close',
            checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',
            checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',
            radioBefore: '/image/svg/sprite.svg#radioBefore',
            radioAfter: '/image/svg/sprite.svg#radioAfter'
        },
    },
    mutations: {
        set_interests(state, data){
            state.interests = data;
        }

    },

    actions:{
        get_interests({ commit }) {
            axios.get('/people/getinterests')
                .then(response => {
                    commit('set_interests', response.data);
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }

}