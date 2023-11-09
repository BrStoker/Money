
export default {

    action: '#',
    method: 'POST',
    title: {
        regard: 'Поздравляю,',
        regardText: 'Вы успешно прошли регистрацию!',
        textPreview: 'В социальной сети “Сцена” есть искусственный интеллект, который будет подбирать для Вас самый целевой контент, давайте расскажем ему о Ваших интересах.',
        textChoise: 'Выберите от 3 до 10 интересов (в любой момент можно их изменить в настройке профиля)'
    },
    images:{
        close: '/image/svg/sprite.svg#close',
        radioBefore: '/image/svg/sprite.svg#radioBefore',
        radioAfter: '/image/svg/sprite.svg#radioAfter'
    },
    action: '#',
    method: 'POST',
    button_submit: {
        type: 'submit',
        text: 'Сохранить интересы',
        class: 'btn, w-100'
    },
    button: {
        type: 'radio',
        name: 'interests__radio',
        required: true,
        checked: false

    }


}