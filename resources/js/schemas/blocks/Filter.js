
export default {

    title: 'Фильтр',
    method: 'post',
    button_submit: {
        type: 'submit',
        text: 'Применить',
        class: 'btn, w-100'
    },
    steps:[
        {
            title: '',
            name: 'main',
            type: 'checkbox',
            simpleSelect: false,
            inputs:[

                {
                    type: 'checkbox',
                    inputType: 'checkbox',
                    text: 'Сейчас на сайте',
                    classCss: 'form-item',
                    name: 'online',
                    value: false,
                    checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',
                    checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',
                },
                {
                    type: 'checkbox',
                    inputType: 'checkbox',
                    text: 'С фото',
                    classCss: 'form-item',
                    name: 'image',
                    value: false,
                    needAuth: false,
                    checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',
                    checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',
                },
                {
                    type: 'checkbox',
                    inputType: 'checkbox',
                    text: 'Не подписан',
                    classCss: 'form-item, mb-0',
                    name: 'unsubscibe',
                    value: false,
                    needAuth: true,
                    checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',
                    checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',
                },
            ],
        },
        {
            title: 'Активность',
            name: 'activity',
            type: 'radio',
            value: '',
            send: true,
            simpleSelect: false,
            inputs:[
                {
                    type: 'radio',
                    inputType: 'radio',
                    text: '24 часа',
                    classCss: 'form-item',
                    name: 'activity',
                    value: '24h',
                    needAuth: false,
                    radioBefore: '/image/svg/sprite.svg#radioBefore',
                    radioAfter: '/image/svg/sprite.svg#radioAfter'
                },
                {
                    type: 'radio',
                    inputType: 'radio',
                    text: '7 дней',
                    classCss: 'form-item',
                    name: 'activity',
                    value: '7d',
                    needAuth: false,
                    radioBefore: '/image/svg/sprite.svg#radioBefore',
                    radioAfter: '/image/svg/sprite.svg#radioAfter'
                },
                {
                    type: 'radio',
                    inputType: 'radio',
                    text: 'Все время',
                    classCss: 'form-item, mb-0',
                    name: 'activity',
                    value: 'all',
                    needAuth: false,
                    radioBefore: '/image/svg/sprite.svg#radioBefore',
                    radioAfter: '/image/svg/sprite.svg#radioAfter'
                },
            ],
        },
        {
            title: 'Местоположение',
            type: 'select_geoposition',
            name: 'geoposition',
            simpleSelect: false,

            inputs:[
                {
                    type: 'select',
                    inputType: 'select_geoposition',
                    text: 'Укажите страну',
                    classCss: 'form-item',
                    name: 'country_id',
                    value: '',
                    needAuth: false,
                    options:[],
                    settings: {'width': '100%'}
                },
                {
                    type: 'select',
                    inputType: 'select_geoposition',
                    text: 'Укажите город',
                    classCss: 'form-item, mb-0',
                    name: 'city_id',
                    value: '',
                    needAuth: false,
                    options:[],
                    settings: {'width': '100%'}
                },
            ],
        },
        {
            name: 'gender',
            title: 'Пол',
            type: 'radio',
            value: '',
            send: true,
            inputs:[
                {
                    type: 'radio',
                    inputType: 'radio',
                    text: 'Мужской',
                    classCss: 'form-item',
                    name: 'gender',
                    value: 1,
                    needAuth: false,
                    radioBefore: '/image/svg/sprite.svg#radioBefore',
                    radioAfter: '/image/svg/sprite.svg#radioAfter'
                },
                {
                    type: 'radio',
                    inputType: 'radio',
                    text: 'Женский',
                    classCss: 'form-item',
                    name: 'gender',
                    value: 2,
                    needAuth: false,
                    radioBefore: '/image/svg/sprite.svg#radioBefore',
                    radioAfter: '/image/svg/sprite.svg#radioAfter'
                },
                {
                    type: 'radio',
                    inputType: 'radio',
                    text: 'Любой',
                    classCss: 'form-item, mb-0',
                    name: 'gender',
                    value: 0,
                    needAuth: false,
                    radioBefore: '/image/svg/sprite.svg#radioBefore',
                    radioAfter: '/image/svg/sprite.svg#radioAfter'
                },
            ],
        },
        {
            name: 'age',
            title: 'Возраст',
            type: 'option',
            simpleSelect: true,
            inputs:[
                {
                    type: 'option',
                    inputType: 'option',
                    class: 'col, col_6',
                    classParent: '',
                    placeholder: 'Минимальный возраст',
                    name: 'age_min',
                    needAuth: false,
                    value: '',
                    options:[
                        {
                            label: 'от 25',
                            value: '25',
                        },
                        {
                            label: 'от 30',
                            value: '30',
                        },
                        {
                            label: 'от 35',
                            value: '35',
                        },
                    ]
                },
                {
                    type: 'option',
                    inputType: 'option',
                    class: 'col, col_6',
                    classParent: '',
                    placeholder: 'Максимальный возраст',
                    name: 'age_max',
                    needAuth: false,
                    value: '',
                    options:[
                        {
                            label: 'до 25',
                            value: '25',
                        },
                        {
                            label: 'до 30',
                            value: '30',
                        },
                        {
                            label: 'до 35',
                            value: '35',
                        },
                    ]
                },

            ],
        },
        {
            name: 'interest',
            value: []
        },
        {
            name: 'fio',
            value: ''
        }
    ]

}