
export default {

    title: 'Фильтр',
    method: 'post',
    button_submit: {
        type: 'submit',
        text: 'Применить',
        class: 'btn, w-100'
    },
    imgClose: '/image/svg/sprite.svg#close',
    steps:[
        {
            class: 'form__group, group',
            title: '',
            hasButtons: false,
            simpleSelect: false,
            filterButton: true,
            inputs:[
                {
                    type: 'select',
                    inputType: 'select_geoposition',
                    text: 'Укажите страну',
                    classCss: 'form-item',
                    name: 'courses_type_id',
                    value: '',
                    needAuth: false,
                    options:[],
                    settings: {'width': '100%'},
                    header:{
                        title: 'Тип курса'
                    }
                },
                {
                    type: 'select',
                    inputType: 'select_geoposition',
                    text: 'Укажите страну',
                    classCss: 'form-item',
                    name: 'courses_subject_id',
                    value: '',
                    needAuth: false,
                    options:[],
                    settings: {'width': '100%'},
                    header:{
                        title: 'Тематика'
                    }
                },
                {
                    type: 'input',
                    inputType: 'input',
                    placeholder: 'ID или имя автора',
                    classCss: 'form-item',
                    name: 'autorName',
                    value: ''
                },
            ]
        },
        {
            class: 'form__group, group',
            title: '',
            hasButtons: false,
            simpleSelect: false,
            filterButton: false,
            inputs:[
                {
                    type: 'checkbox',
                    inputType: 'checkbox',
                    text: 'Бесплатные',
                    classCss: 'form-item',
                    name: 'free',
                    value: false,
                    checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',
                    checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',
                },
                {
                    type: 'checkbox',
                    inputType: 'checkbox',
                    text: 'Только с отзывами',
                    classCss: 'form-item',
                    name: 'withComments',
                    value: false,
                    checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',
                    checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',
                },
                {
                    type: 'checkbox',
                    inputType: 'checkbox',
                    text: 'Только новые',
                    classCss: 'form-item, mb-0',
                    name: 'new',
                    value: false,
                    checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',
                    checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',
                },
            ]
        },
        {
            class: 'form__group, group',
            title: 'Оценка',
            hasButtons: false,
            simpleSelect: true,
            filterButton: false,
            inputs:[
                {
                    type: 'option',
                    inputType: 'option',
                    class: 'col, col_6',
                    classParent: '',
                    placeholder: 'Минимальный возраст',
                    name: 'grade_min',
                    needAuth: false,
                    value: '',
                    options:[
                        {
                            label: 'от 1',
                            value: '1',
                        },
                        {
                            label: 'от 2',
                            value: '2',
                        },
                        {
                            label: 'от 3',
                            value: '3',
                        },
                        {
                            label: 'от 4',
                            value: '4',
                        },
                        {
                            label: 'от 5',
                            value: '5',
                        },
                    ]
                },
                {
                    type: 'option',
                    inputType: 'option',
                    class: 'col, col_6',
                    classParent: '',
                    placeholder: 'Минимальный возраст',
                    name: 'grade_max',
                    needAuth: false,
                    value: '',
                    options:[
                        {
                            label: 'до 1',
                            value: '1',
                        },
                        {
                            label: 'до 2',
                            value: '2',
                        },
                        {
                            label: 'до 3',
                            value: '3',
                        },
                        {
                            label: 'до 4',
                            value: '4',
                        },
                        {
                            label: 'до 5',
                            value: '5',
                        },
                    ]
                },
                {
                    name: 'fio',
                    value: ''
                }
            ]
        },
        {
            class: 'form__group, group',
            title: 'Цена',
            hasButtons: true,
            simpleSelect: true,
            filterButton: false,
            inputs:[
                {
                    type: 'number',
                    inputType: 'input',
                    placeholder: 'Цена от',
                    classCss: 'form-item',
                    class: 'col, col_6',
                    name: 'price_min',
                    value: ''
                },
                {
                    type: 'number',
                    inputType: 'input',
                    placeholder: 'Цена до',
                    classCss: 'form-item',
                    class: 'col, col_6',
                    name: 'price_max',
                    value: ''
                },
            ],
            buttons: [
                {
                    type: 'submit',
                    inputType: 'submit',
                    class: 'btn w-100',
                    placeholder: 'Применить',

                }
            ]
        },
    ]

}