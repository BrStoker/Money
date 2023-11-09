export default {

    method: 'POST',
    url: '/user/profile',
    steps:[
        {
            name: 'Основное',
            type: 'input',
            inputs: [
                {
                    type: 'file',
                    inputType: 'file',
                    accept: 'image/*',
                    name: 'image',
                    value: '',
                    validate: true,
                    send: true,
                    error: '',
                    placeholder: 'Изменить',
                    header:{
                        title: 'Фотография'
                    }
                },
                {
                    type: 'input',
                    inputType: 'input',
                    name: 'first_name',
                    value: '',
                    classCss: 'form-item__input',
                    classParent: 'form-item',
                    validate: true,
                    send: true,
                    error: '',
                    header: {
                        title: 'Имя'
                    },
                    rules:[
                        {
                            regExp: /^[a-zA-Zа-яА-Я]{2,}$/,
                            error: 'Имя должно содержать только буквы английского или русского алфавита и не может быть короче 2 символов'
                        },
                        {
                            regExp: 20,
                            error: 'Поле не может содержать более 20 символов'
                        }
                    ]

                },
                {
                    type: 'input',
                    inputType: 'input',
                    name: 'last_name',
                    value: '',
                    classCss: 'form-item__input',
                    classParent: 'form-item',
                    validate: true,
                    error: '',
                    send: true,
                    header: {
                        title: 'Фамилия'
                    },
                    rules:[
                        {
                            regExp: /^[a-zA-Zа-яА-Я]{2,}$/,
                            error: 'Фамилия должна содержать только буквы английского или русского алфавита и не может быть короче 2 символов'
                        },
                        {
                            regExp: 20,
                            error: 'Поле не может содержать более 20 символов'
                        }
                    ]

                },
                {
                    type: 'input',
                    inputType: 'input',
                    classParent: 'form-item',
                    name: 'fields[signature]',
                    classCss: 'form-item__input',
                    validate: false,
                    send: true,
                    value: '',
                    error: '',
                    header: {
                        title: 'Подпись'
                    }
                },
                {
                    type: 'textArea',
                    name: 'fields[description]',
                    class: 'form-item__main',
                    classCss: 'form-item__input, form-item__input_textarea',
                    inputType: 'textArea',
                    send: true,
                    value: '',
                    header: {
                        title: 'Описание',
                    }

                },
                {
                    type: 'input',
                    inputType: 'input',
                    name: 'fields[nickname]',
                    classParent: 'form-item',
                    classCss: 'form-item__input',
                    send: true,
                    validate: false,
                    error: '',
                    value: '',
                    header: {
                        title: 'Никнейм',
                    }
                },
                {
                    send: false,
                    placeholder: 'Изменить',
                    class: 'btn btn_tertiary',
                    type: 'button',
                    inputType: 'button',
                    value: '',
                    header: {
                        title: 'Интересы'
                    }
                },
                ],
        },
        {
            name: 'Социальные сети',
            type: 'group',
            inputs: [
                {
                    type: 'input',
                    inputType: 'input',
                    hint: '',
                    classCss: 'form-item__input',
                    classParent: 'form-item',
                    name: 'fields[telegram]',
                    send: true,
                    placeholder: 'Введите Ваши данные',
                    validate: true,
                    error: '',
                    value: '',
                    header: {
                        title: 'Telegram',
                        image: '/image/svg/sprite.svg#socials_01'
                    }
                },
                {
                    type: 'input',
                    inputType: 'input',
                    classParent: 'form-item',
                    hint: '',
                    classCss: 'form-item__input',
                    name: 'fields[vkontakte]',
                    placeholder: 'Введите Ваши данные',
                    send: true,
                    validate: true,
                    error: '',
                    value: '',
                    header: {
                        title: 'VK',
                        image: '/image/svg/sprite.svg#socials_03'
                    }
                },
                {
                    type: 'input',
                    inputType: 'input',
                    classParent: 'form-item',
                    hint: 'Запрещен на территории РФ',
                    classCss: 'form-item__input',
                    name: 'fields[instagram]',
                    placeholder: 'Введите Ваши данные',
                    send: true,
                    validate: true,
                    error: '',
                    value: '',
                    header: {
                        title: 'Instagram',
                        image: '/image/svg/sprite.svg#socials_04'
                    }
                },
                {
                    type: 'input',
                    inputType: 'input',
                    classParent: 'form-item',
                    hint: '',
                    classCss: 'form-item__input',
                    name: 'fields[youtube]',
                    placeholder: 'Введите Ваши данные',
                    send: true,
                    validate: true,
                    error: '',
                    value: '',
                    header: {
                        title: 'Youtube',
                        image: '/image/svg/sprite.svg#socials_05'
                    }
                },
                {
                    type: 'input',
                    inputType: 'input',
                    classParent: 'form-item',
                    hint: '',
                    classCss: 'form-item__input',
                    name: 'fields[yandex_dzen]',
                    placeholder: 'Введите Ваши данные',
                    send: true,
                    validate: true,
                    error: '',
                    value: '',
                    header: {
                        title: 'Яндекс Dzen',
                        image: '/image/svg/sprite.svg#socials_06'
                    }
                },
            ],
        },
        {
            name: 'Личные данные',
            class: 'col, col_12',
            inputs:[
                {
                    name: 'gender',
                    type: 'select',
                    class: 'col, col_12',
                    inputType: 'option',
                    send: true,
                    validate: false,
                    value: '',
                    error: '',
                    header: {
                        title: 'Пол',
                    },
                    options:[
                        {
                            label: 'Не указано',
                            value: '0',
                            selected: false
                        },
                        {
                            label: 'Мужской',
                            value: '1',
                            selected: false
                        },
                        {
                            label: 'Женский',
                            value: '2',
                            selected: false
                        },
                    ]
                },
                {
                    name: 'birthday',
                    type: 'date',
                    inputType: 'input',
                    validate: true,
                    send: true,
                    error: '',
                    value: '',
                    header: {
                        title: 'Дата рождения',
                    }
                },
                {
                    baseName: 'country',
                    type: 'select',
                    inputType: 'select_geoposition',
                    name: 'country_id',
                    class: 'form-item__field',
                    options: [],
                    validate: false,
                    hasId: true,
                    send: true,
                    value: '',
                    error: '',
                    header: {
                        title: 'Страна',
                    }
                },
                {
                    baseName: 'city',
                    name: 'city',
                    inputType: 'select_geoposition',
                    type: 'city_id',
                    class: 'form-item__field',
                    options: [],
                    validate: false,
                    send: true,
                    value: '',
                    error: '',
                    header: {
                        title: 'Город',
                    }
                },
                {
                    name: 'phone',
                    type: 'input',
                    disabled: true,
                    inputType: 'input',
                    classParent: 'form-item',
                    classCss: 'form-item__main',
                    validate: true,
                    send: true,
                    error: '',
                    header: {
                        title: 'Номер телефона',
                    },
                    rules:[
                        {
                            regExp: /^(?!$)\+?[0-9]{1,3}-?[0-9]{3}-?[0-9]{3}-?[0-9]{2}-?[0-9]{2}$/,
                            error: 'Телефон не может быть пустым и должен быть валидным'
                        }
                    ],
                    value: ''
                },
                {
                    name: 'email',
                    type: 'input',
                    inputType: 'input',
                    disabled: true,
                    classParent: 'form-item',
                    classCss: 'form-item__main',
                    validate: true,
                    send: true,
                    error: '',
                    header: {
                        title: 'E-mail',
                    },
                    rules:[
                        {
                            regExp: '/^.+@.+\..+$/',
                            error: 'Email должен быть валидным'
                        }
                    ],
                    value: ''
                },
                {
                    send: false,
                    name: 'link',
                    placeholder: 'Удалить страницу',
                    classParent: 'form-item',
                    classCss: 'form-item__main',
                    delete: true,
                    type: 'link',
                    inputType: 'link',
                    link: '#'
                },
            ],
        },
        {
            name: 'Изменить пароль',
            inputs:[
                {
                    placeholder: 'Старый пароль',
                    name: 'oldpassword',
                    classParent: 'form-item',
                    classCss: 'form-item__main',
                    type: 'password',
                    inputType: 'input',
                    send: true,
                    required: false,
                    image: '/image/svg/sprite.svg#eye',
                    header: {
                        title: 'Старый пароль',
                    },
                    rules:[
                        {
                            regExp: /^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/,
                            error: 'Пароль должен содержать буквы в разных регистрах и хотя бы одну цифру и быть не менее 8 символов'
                        }
                    ],
                    value: ''
                },
                {
                    placeholder: 'Новый пароль',
                    name: 'newpassword',
                    type: 'password',
                    inputType: 'input',
                    classParent: 'form-item',
                    classCss: 'form-item__main',
                    required: false,
                    send: true,
                    validate: true,
                    error: '',
                    header: {
                        title: 'Новый пароль',
                    },
                    image: '/image/svg/sprite.svg#eye',
                    rules:[
                        {
                            regExp: /^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/,
                            error: 'Пароль должен содержать буквы в разных регистрах и хотя бы одну цифру и быть не менее 8 символов'
                        }
                    ],
                    value: ''
                },
                {
                    placeholder: 'Повторите новый пароль',
                    name: 'repeat_newpassword',
                    classParent: 'form-item',
                    classCss: 'form-item__main',
                    type: 'password',
                    inputType: 'input',
                    required: false,
                    send: true,
                    validate: true,
                    error: '',
                    header: {
                        title: 'Повторите новый пароль',
                    },
                    image: '/image/svg/sprite.svg#eye',
                    rules:[
                        {
                            regExp: /^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/,
                            error: 'Пароль должен содержать буквы в разных регистрах и хотя бы одну цифру и быть не менее 8 символов'
                        }
                    ],
                    value: ''
                },
            ],
        },
        {
            inputs:[
                {
                    send: false,
                    placeholder: 'Сохранить изменения',
                    name: 'submit',
                    type: 'submit',
                    class: 'btn',
                    inputType: 'submit'
                }
            ],
        },
    ],

}