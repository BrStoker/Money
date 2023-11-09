
export default {

    method: 'post',
    url: '/people/search',
    steps:[
        {
            type: 'input',
            inputs: [
                {
                    name: 'fio',
                    placeholder: 'Поиск...',
                    type: 'text',
                    inputType: 'input',
                    value: '',
                    images: {
                        search: '/image/svg/sprite.svg#search',
                        filter: '/image/svg/sprite.svg#filter',
                    },
                    rules: [
                        v => !!v || 'введите хотя бы один сивол',
                        v => v.length <= 3 || 'ЗАпрос не может содержать меньше трех символов'
                    ],
                }            ]

        }

    ]
}