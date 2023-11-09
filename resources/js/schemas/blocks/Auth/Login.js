export default {

    method: 'post',
    action: '/login',
    images: {
        eye: "/image/svg/sprite.svg#eye"
    },
    steps: [
        {
            class: 'col, col_12',
            type: 'input',
            classChild: 'form-item',
            inputs:[
                {
                    name: 'email',
                    inputType: 'input',
                    type: 'text',
                    placeholder: 'Телефон или ID',
                    required: true,
                    classParent: 'form-item',
                    id: 'login__id',
                    class: 'col, col_12',
                    value: '',
                    send: true,
                    validate: true,
                    error: ''
                },
            ]
        },
        {
            class: 'col, col_12',
            type: 'input',
            classChild: 'form-item, form-item_after',
            inputs:[
                {
                    name: 'password',
                    inputType: 'input',
                    type: 'password',
                    placeholder: 'Пароль',
                    classParent: 'form-item',
                    id: 'login__password',
                    class: 'col, col_12',
                    required: true,
                    send: true,
                    validate: true,
                    image: '/image/svg/sprite.svg#eye',
                    value: '',
                    error: ''
                }
            ],
        },
        {
            class: 'col, col_6',
            type: 'checkbox',
            classChild: 'form-item',
            inputs:[
                {
                    name: 'rememberMe',
                    inputType: 'checkbox',
                    type: 'checkbox',
                    text: 'Запомнить меня',
                    classParent: '',
                    classCss: 'form-item__main',
                    checkboxAfter: "/image/svg/sprite.svg#checkboxAfter",
                    checkboxBefore: "/image/svg/sprite.svg#checkboxBefore",
                    required: false,
                    send: true,
                    value: '',
                    rules: [
                    ]
                },
            ],
        },
        {
            class: 'col, col_6',
            type: 'link',
            classChild: 'wysiwyg, text_m-end',
            inputs:[
                {
                    inputType: 'link',
                    url: '/auth/remember',
                    placeholder: 'Забыли пароль?',
                    classParent: 'form-item',
                    class: '',
                    required: false,
                    type: '',
                    onclick: 'showForgotForm',
                    value: '',
                    rules: [
                    ]
                },
            ],
        },
        {
            class: 'col, col_12',
            type: 'submit',
            inputs:[
                {
                    inputType: 'submit',
                    type: 'submit',
                    placeholder: 'Войти',
                    classParent: '',
                    class: 'btn w-100',
                    required: false,
                    validate: false,
                    value: '',
                    rules: [
                    ]
                },
            ],
        },
        {
            class: 'col, col_12',
            classChild: 'form-item, mb-0',
            type: 'supportLink',
            inputs:[
                {
                    name: 'button',
                    placeholder: 'Обратиться в техподдержку',
                    required: true,
                    validate: false,
                    type: '',
                    classParent: 'col, col_12',
                    inputType: 'supportLink',
                    value: 'Обратиться в техподдержку',
                    rules: []
                }
            ],
        },
    ]

}