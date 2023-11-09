export default {

    method: 'POST',
    action: '',
    title: 'Добавить папку',
    image: {
        close: '/image/svg/sprite.svg#close'
    },
    steps:[
        {
            header:{
                title: 'Название папки'
            },
            inputs: [
                {
                    type: 'text',
                    inputType: 'input',
                    placeholder: 'Название папки',
                    value: ''
                }
            ]
        },
        {
            header:{
                title: 'Выберите контакты для добавления'
            },
            contacts: []
        },
        {
            inputs:[
                {
                    type: 'submit',
                    inputType: 'submit',
                    placeholder: 'Подтвердить'
                }
            ]
        },
    ]




}
