export default {

    title: 'Категории',
    method: 'POST',
    url: '/category/add',
    steps:[
        {
            class: 'col, col_12',
            group: true,
            inputs:[
                {
                    id: 1,
                    class: 'form-item',
                    inputType: 'input',
                    name: 'category_title',
                    placeholder: 'Название категории',
                    send: true,
                    image: '/image/svg/sprite.svg#closeSecond',
                    value: ''
                }
            ],
        },
        {
            class: 'col, col_12',
            group: false,
            inputs:[
                {
                    class: 'form-item, mb-0',
                    type: 'span',
                    placeholder: 'Добавить категорию',
                    send: false,
                },
            ],
        },

    ]

}