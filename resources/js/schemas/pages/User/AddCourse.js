export default {

    method: 'POST',
    url: '/user/article',
    title: 'Добавить курс',
    steps:[
        {
            group: true,
            inputs:[
                {
                    type: 'file',
                    inputType: 'image',
                    accept: 'image/*',
                    name: 'image',
                    value: '',
                    validate: true,
                    send: true,
                    error: '',
                    placeholder: 'Изменить',
                    header:{
                        title: 'Фотография'
                    }
                },
                {
                    name: 'title',
                    hint: '',
                    placeholder: 'Имя',
                    type: 'input',
                    required: true,
                    inputType: 'input',
                    send: true,
                    value: '',
                    error: '',
                    header: {
                        title: 'Имя',
                    }
                },
                {
                    name: 'detail_text',
                    hint: '',
                    placeholder: 'Описание',
                    inputType: 'textArea',
                    classCss: 'form-item__input, form-item__input_textarea',
                    required: true,
                    send: true,
                    error: '',
                    value: '',
                    header: {
                        title: 'Описание',
                    }
                },
                {
                    type: 'select',
                    inputType: 'select_geoposition',
                    text: 'Укажите страну',
                    classCss: 'form-item',
                    name: 'courses_type_id',
                    value: {},
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
                    type: 'group',
                    header:{
                        title: 'Цена'
                    },
                    inputs: [
                        {
                            name: 'price',
                            hint: '',
                            placeholder: 'Цена',
                            type: 'number',
                            required: false,
                            inputType: 'input',
                            parentClass: 'col, col_6, col_mob-4',
                            send: true,
                            value: '',
                            error: '',
                            header: {
                                title: 'Цена',
                            }
                        },
                        {
                            type: 'checkbox',
                            inputType: 'checkbox',
                            text: 'Курс бесплатный',
                            classCss: 'form-item__field, d-flex, align_center, h-100',
                            name: 'free',
                            parentClass: 'col, col_6, col_mob-8',
                            value: false,
                            send: true,
                            checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',
                            checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',
                        },
                    ]
                },

                {
                    name: 'link',
                    hint: '',
                    placeholder: 'Лендинг, форма-заявки, чат-бот',
                    type: 'input',
                    required: true,
                    inputType: 'input',
                    send: true,
                    value: '',
                    error: '',
                    header: {
                        title: 'Ссылка',
                    }
                },
                {
                    type: 'checkbox',
                    inputType: 'checkbox',
                    text: 'Добавить курс в каталог',
                    classCss: 'form-item, mb-0',
                    name: 'addToCatalog',
                    value: false,
                    needAuth: true,
                    checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',
                    checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',
                    header: {
                        title: ''
                    }
                },
            ]
        },
        {
            group: false,
            inputs:[
                {
                    name: 'button',
                    placeholder: 'Опубликовать курс',
                    hint: '',
                    class: 'btn',
                    inputType: 'submit',
                    required: false,
                    send: false,
                },
            ]
        }
    ],

}