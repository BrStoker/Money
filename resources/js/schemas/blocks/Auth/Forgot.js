 export default {
        action: '/profile/forgot',
        method: 'POST',
        header: {
            title: 'Восстановление пароля',
            text: 'Введите ваш телефон, и мы отправим вам код подтверждения',
            textBack: 'Назад',
        },
        images: {
            arrowLeft: '/image/svg/sprite.svg#arrowLeft',
            close: '/image/svg/sprite.svg#close'
        },
        steps:[
            {
                type: 'input',
                class: 'col, col_12',
                classChild: 'form-item, form-item_after',
                inputs: [
                    {
                        name: 'recovery_phone',
                        placeholder: 'Телефон',
                        required: true,
                        type: 'tel',
                        class: 'form-item, form-item_after',
                        classChild: 'form-item__field',
                        inputType: 'input',
                        value: '',
                        send: true,
                        validate: true,
                        error: ''
                    }
                ]
            },
            {
                type: 'submit',
                class: 'col, col_12',
                // classChild: 'form-item',
                inputs:[
                    {
                        send: false,
                        placeholder: 'Получить код',
                        type: 'submit',
                        class: 'btn w-100',
                        inputType: 'submit'
                    }
                ]
            }
        ],
 }