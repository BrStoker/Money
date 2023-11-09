export default{

        title: 'Пожаловаться',
        close: '/image/svg/sprite.svg#close',
        steps: [
                {
                        title: 'Выберите причину жалобы',
                        inputs: [
                                {
                                        name: 'reason',
                                        inputType: 'option',
                                        class: 'col, col_12',
                                        options: [
                                                {
                                                        value: '1',
                                                        label: 'Спам'
                                                },
                                                {
                                                        value: '2',
                                                        label: 'Угрозы'
                                                },
                                                {
                                                        value: '3',
                                                        label: 'Фейковый аккаунт'
                                                },
                                        ],

                                }
                        ]
                },
                {
                        inputs:[
                                {
                                        name: '',
                                        inputType: 'submit',
                                        placeholder: 'Пожаловаться',
                                        type: 'submit',
                                        parentClass: 'form-item',
                                        class: 'btn w-100'
                                },
                                {
                                        name: '',
                                        inputType: 'button',
                                        placeholder: 'Отменить',
                                        parentClass: 'form-item',
                                        type: 'button',
                                        class: 'btn btn_tertiary w-100',
                                        onclick: ()=> {console.log(closeForm())}
                                },
                        ]
                }
        ]
        

}