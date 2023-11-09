export default{

    method: 'post',
    action: '/article/comment',
    steps: [
        {
            inputs: [
                {
                    name: 'text',
                    hint: '',
                    placeholder: 'Введите текст комментария',
                    inputType: 'textArea',
                    type: 'textArea',
                    classCss: 'form-item__input, form-item__input_textarea',
                    value: '',
                    error: '',
                    send: true,
                    header: {
                        title: 'Текст комментария',
                    }
                },
                {
                    type: 'hidden',
                    name: 'comment_id',
                    classCss: 'form-item__input, form-item__input_textarea',
                    inputType: 'input',
                    value: 0,
                    send: true,
                },
                {
                    send: false,
                    placeholder: 'Добавить комментарий',
                    name: 'submit',
                    type: 'submit',
                    class: 'btn',
                    inputType: 'submit'
                }

            ]
        }
    ]

}