
import Login from '@/js/store/blocks/Auth/Login'
import Register from '@/js/store/blocks/Auth/Register'

export default{

    state: {
        images: {
            logo: '/image/svg/logo.svg',
            button: '/image/svg/sprite.svg#user'
        },
        text: {
            logo: 'Деньги на сцене',
            button: 'Вход / Регистрация'
        },
        blocks: {
            login: Login.state,
            register: Register.state
        }
    },
    mutations: {
        ...{
            HeaderUpdate: (state, value) => {

                if(_.isObject(value) == true && _.size(value) > 0) {
                    
                    _.forIn(value, (value, key) => {

                        if(_.hasIn(state.data.header, key)) {

                            _.forIn(value, (subValue, subKey) => {

                                if(_.hasIn(state.data.header[key], subKey)) {

                                    state.data.header[key][subKey] = subValue

                                }

                            })

                        }

                    })

                }
            }
        },
        ...Login.mutations,
        ...Register.mutations
    }

}