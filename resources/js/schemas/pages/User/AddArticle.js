export default {

    method: 'POST',
    url: '/user/article',
    title: 'Добавить статью',
    steps:[
        {
            group: true,
            inputs:[
                {
                    name: 'title',
                    hint: '',
                    placeholder: 'Введите название',
                    type: 'input',
                    required: true,
                    inputType: 'input',
                    send: true,
                    value: '',
                    error: '',
                    header: {
                        title: 'Заголовок',
                    }
                },
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
                        title: 'Изображение'
                    }
                },
                {
                    name: 'detail_text',
                    hint: '',
                    placeholder: 'Введите текст статьи',
                    inputType: 'textArea',
                    classCss: 'form-item__input, form-item__input_textarea',
                    required: true,
                    send: true,
                    error: '',
                    value: '',
                    header: {
                        title: 'Текст статьи',
                    }
                },
                {
                    name: 'description',
                    placeholder: 'Введите описание статьи',
                    inputType: 'textArea',
                    required: false,
                    type: 'textArea',
                    classCss: 'form-item__input, form-item__input_textarea',
                    send: true,
                    error: '',
                    value: '',
                    header:{
                        title: 'Описание  ',
                        hint: '(необязательно)',
                    }
                },
                {
                    name: 'fields[3]',
                    placeholder: 'Введите ключевые слова',
                    inputType: 'input',
                    classCss: 'form-item__input, form-item__input_textarea',
                    required: false,
                    send: true,
                    value: '',
                    error: '',
                    header: {
                        title: 'Ключевые слова через запятую  ',
                        hint: '(необязательно)',
                    }
                },
                {
                    name: 'article_group_ids',
                    hint: '',
                    placeholder: 'Выберите категории',
                    inputType: 'checkbox',
                    required: true,
                    send: false,
                    value: '',
                    header: {
                        title: 'Выберите категории',
                    }
                },
                {
                    send: false,
                    placeholder: 'Добавить категорию',
                    class: 'btn btn_tertiary',
                    type: 'button',
                    inputType: 'button',
                },
                {
                    name: 'fields[2]',
                    InputName: 'Показывать статью в общей ленте',
                    hint: '',
                    text: 'Показывать статью в общей ленте',
                    placeholder: '',
                    checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',
                    checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',
                    type: 'checkbox',
                    inputType: 'checkbox',
                    required: false,
                    checked: true,
                    send: true,
                    value: false
                },
            ]
        },
        {
            group: false,
            inputs:[
                {
                    name: 'button',
                    placeholder: 'Опубликовать статью',
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