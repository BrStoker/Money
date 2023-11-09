export default {


    steps: [
        {
            title: 'Ваша партнерская ссылка:',
            inputs: [
                {
                    type: 'input',
                    inputType: 'input',
                    name: 'partner_link',
                    placeholder: 'Ваша партнерская ссылка',
                    classCss: 'form-item__main',
                    class: '',
                    disabled: true,
                    value: ''
                },
                {
                    type: 'button',
                    inputType: 'button',
                    name: 'button_copy',
                    placeholder: 'Скопировать',
                    classCss: 'form-item__footer',
                    class: 'btn w-100',
                    value: ''
                }
            ]
        },
        {
            title: 'Структура'
        }
    ]

}