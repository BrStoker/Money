export default {

    steps:[
        {
            class: 'col, col_12, col_mob-6',
            header:{
                title: 'От'
            },
            inputs: [
                {
                    name: 'min_number',
                    type: 'number',
                    placeholder: 'Минимальное значение',
                    inputType: 'input'
                }
            ]
        },
        {
            class: 'col, col_12, col_mob-6',
            header:{
                title: 'До'
            },
            inputs: [
                {
                    name: 'max_number',
                    type: 'number',
                    placeholder: 'Максимальное значение',
                    inputType: 'input'
                }
            ]
        },
        {
            class: 'col, col_12',
            inputs: [
                {
                    name: 'min_number',
                    type: 'button',
                    class: 'btn w-100',
                    placeholder: 'Сгенерировать',
                    inputType: 'button'
                }
            ]
        },
    ]
}