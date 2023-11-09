export default {
    method: 'POST',
    action: '/register',
    text: {
        title: 'Зарегистрироваться'
    },
    steps: [
        {
            type: 'input',
            classChild: 'form-item',
            inputs: [
                {
                    name: 'first_name',
                    placeholder: 'Имя',
                    required: true,
                    type: 'text',
                    value: '',
                    validate: true,
                    classParent: 'form-item',
                    inputType: 'input',
                    hidden: false,
                    error: ''
                },

            ]
        },
        {
            classChild: 'form-item',
            inputs:[
                {
                    name: 'last_name',
                    placeholder: 'Фамилия',
                    required: true,
                    type: 'text',
                    inputType: 'input',
                    classParent: 'form-item',
                    validate: true,
                    hidden: false,
                    error: '',
                    value: ''
                },
            ]
        },
        {
            classChild: 'form-item',
            inputs:[
                {
                    name: 'email',
                    placeholder: 'E-mail',
                    required: true,
                    type: 'email',
                    inputType: 'input',
                    classParent: 'form-item',
                    hidden: false,
                    validate: true,
                    error: '',
                    value: ''
                },
            ]
        },
        {
            classChild: 'form-item',
            inputs:[
                {
                    name: 'phone',
                    placeholder: 'Телефон',
                    required: true,
                    type: 'tel',
                    inputType: 'input',
                    classParent: 'form-item',
                    validate: true,
                    hidden: false,
                    error: '',
                    value: ''
                },
            ]
        },
        {
            classChild: 'form-item, form-item_after',
            inputs:[
                {
                    name: 'password',
                    placeholder: 'Пароль',
                    required: true,
                    type: 'password',
                    inputType: 'input',
                    classParent: 'form-item, form-item_after',
                    hidden: false,
                    validate: true,
                    error: '',
                    image: '/image/svg/sprite.svg#eye',
                    value: ''
                },
            ]
        },
        {
            classChild: 'form-item, form-item_after',
            inputs:[
                {
                    name: 'password_confirm',
                    placeholder: 'Повторите пароль',
                    required: true,
                    type: 'password',
                    hidden: false,
                    validate: true,
                    classParent: 'form-item, form-item_after',
                    error: '',
                    inputType: 'input',
                    image: '/image/svg/sprite.svg#eye',
                    value: ''
                }
            ]
        },
        {
            classChild: 'form-item',
            type: 'checkbox',
            inputs: [
                {
                    send: false,
                    name: 'checkbox',
                    text: 'Я согласен с условиями пользовательского соглашения',
                    required: true,
                    type: 'checkbox',
                    checkboxAfter: "/image/svg/sprite.svg#checkboxAfter",
                    checkboxBefore: "/image/svg/sprite.svg#checkboxBefore",
                    hidden: false,
                    classCss: 'form-item__main',
                    classParent: 'form-item__main',
                    error: false,
                    validate: true,
                    inputType: 'checkbox',
                    value: ''
                },
            ]
        },
        {
            type: 'submit',
            inputs: [
                {
                    send: false,
                    name: 'button',
                    placeholder: 'Продолжить',
                    required: false,
                    class: 'btn w-100',
                    type: 'confirm',
                    inputType: 'submit',
                    value: 'Продолжить',
                    rules: []
                }
            ]
        },
        {
            type: 'supportLink',
            inputs: [
                {
                    send: false,
                    name: 'button',
                    placeholder: 'Обратиться в техподдержку',
                    required: true,
                    type: '',
                    inputType: 'supportLink',
                    value: 'Обратиться в техподдержку',
                    rules: []
                }
            ]
        }
    ],




}