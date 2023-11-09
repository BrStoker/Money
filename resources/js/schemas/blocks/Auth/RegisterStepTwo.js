export default {
    method: 'POST',
    action: '/register/confirm',
    images: {
        arrowLeft: '/image/svg/sprite.svg#arrowLeft',
    },
    text: {
        title: 'Подтвердите номер телефона',
        description: 'Впишите последние 4 цифры номера входящего звонка',
        back: 'Назад',
        textLink: 'Запросить новый код',
        textReg: 'Зарегистрироваться'
    },
    steps: [
        {
            type: 'input',
            inputs:[
                {
                    type: 'number',
                    inputType: 'input',
                    classParent: 'col, col_3',
                    class: 'form-item__input, form-item__input_second',
                    required: true,
                    hidden: false,
                    name: 'input_1',
                    value: '',
                    regExp: "[0-9]|.{0,1}"
                },
                {
                    type: 'number',
                    inputType: 'input',
                    classParent: 'col, col_3',
                    class: 'form-item__input, form-item__input_second',
                    required: true,
                    hidden: false,
                    name: 'input_2',
                    value: '',
                    regExp: "[0-9]|.{0,1}"
                },
                {
                    type: 'number',
                    inputType: 'input',
                    classParent: 'col, col_3',
                    class: 'form-item__input, form-item__input_second',
                    required: true,
                    hidden: false,
                    value: '',
                    name: 'input_3',
                    regExp: "[0-9]|.{0,1}"
                },
                {
                    type: 'number',
                    inputType: 'input',
                    classParent: 'col, col_3',
                    class: 'form-item__input, form-item__input_second',
                    required: true,
                    hidden: false,
                    value: '',
                    name: 'input_4',
                    regExp: "[0-9]|.{0,1}"
                },
            ]
        },
        {
            inputs: [
                {
                    send: false,
                    name: 'button',
                    placeholder: 'Зарегистрироваться',
                    required: false,
                    class: 'btn w-100',
                    type: 'submit',
                    inputType: 'submit',
                    value: 'Продолжить',
                    rules: []
                }
            ]
        }
    ],



}