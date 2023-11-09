 export default {
        action: '/profile/forget',
        method: 'post',
        header: {
            title: 'Введите код',
            text: 'Впишите последние 4 цифры номера входящего звонка',
            textBack: 'Назад',
            textLink: 'Запросить новый код'
        },
        images: {
            arrowLeft: '/image/svg/sprite.svg#arrowLeft',
            close: '/image/svg/sprite.svg#close'
        },
        inputs:[
            {
                type: 'number',
                typeInput: 'input',
                required: true,
                value: '',
                max: 1
            },
            {
                type: 'number',
                typeInput: 'input',
                required: true,
                value: '',
                max: 1
            },
            {
                type: 'number',
                typeInput: 'input',
                required: true,
                value: '',
                max: 1
            },
            {
                type: 'number',
                typeInput: 'input',
                required: true,
                value: '',
                max: 1
            },
        ],
        button:{
            type: 'submit',
            buttonText: 'Сменить пароль',
        }
 }