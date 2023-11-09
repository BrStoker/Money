export default{

    method: 'POST',
    action: '/article/comment/',
    steps: [
        {
            inputs: [
                {
                    type: 'textArea',
                    name: 'text',
                    classCss: 'form-item__input, form-item__input_textarea',
                    inputType: 'textArea',
                    value: '',
                    send: true
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
                    placeholder: 'Ответить',
                    name: 'submit',
                    type: 'submit',
                    class: 'btn',
                    inputType: 'submit'
                }

            ]
        }
    ]



}