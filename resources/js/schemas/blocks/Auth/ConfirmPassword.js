 export default {
        action: '/profile/confirm',
        method: 'post',
        header: {
            title: 'Введите новый пароль',
            text: 'Введите и подтвердите новый пароль',
            textBack: 'Назад',
            textLink: 'Запросить новый код'
        },
        images: {
            arrowLeft: '/image/svg/sprite.svg#arrowLeft',
            close: '/image/svg/sprite.svg#close'
        },
        inputs:[
            {
                type: 'password',
                typeInput: 'input',
                required: true,
                value: ''
            },
            {
                type: 'password',
                typeInput: 'input',
                required: true,
                value: ''
            },
        ],
        button:{
            type: 'submit',
            buttonText: 'Сохранить и войти',
        }
 }