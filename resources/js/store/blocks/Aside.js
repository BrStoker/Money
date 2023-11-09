export default{

    state: {

        menu: [
            {
                name: 'Профиль',
                ico_main: '/image/svg/sprite.svg#menu__ico-main-01',
                ico_second: '/image/svg/sprite.svg#menu__ico-second-01',
                link: '/id',
                active: false,
                needAuth: true,
                countMessage: false
            },
            {
                name: 'Лента',
                ico_main: '/image/svg/sprite.svg#menu__ico-main-02',
                ico_second: '/image/svg/sprite.svg#menu__ico-second-02',
                link: '/feed',
                active: true,
                needAuth: false,
                countMessage: false
            },
            {
                name: 'Курсы',
                ico_main: '/image/svg/sprite.svg#menu__ico-main-03',
                ico_second: '/image/svg/sprite.svg#menu__ico-second-03',
                link: '/courses',
                active: false,
                needAuth: false,
                countMessage: false
            },
            {
                name: 'Чат',
                ico_main: '/image/svg/sprite.svg#menu__ico-main-04',
                ico_second: '/image/svg/sprite.svg#menu__ico-second-04',
                link: '/chat',
                active: false,
                needAuth: true,
                countMessage: true
            },
            {
                name: 'Люди',
                ico_main: '/image/svg/sprite.svg#menu__ico-main-05',
                ico_second: '/image/svg/sprite.svg#menu__ico-second-05',
                link: '/people',
                active: false,
                needAuth: false,
                countMessage: false
            },
            {
                name: 'Доход',
                ico_main: '/image/svg/sprite.svg#menu__ico-main-06',
                ico_second: '/image/svg/sprite.svg#menu__ico-second-06',
                link: '/income',
                active: false,
                needAuth: true,
                countMessage: false
            },
            {
                name: 'Партнёрка',
                ico_main: '/image/svg/sprite.svg#menu__ico-main-07',
                ico_second: '/image/svg/sprite.svg#menu__ico-second-07',
                link: '/partners',
                active: false,
                needAuth: true,
                countMessage: false
            },
            {
                name: 'Трафик',
                ico_main: '/image/svg/sprite.svg#menu__ico-main-08',
                ico_second: '/image/svg/sprite.svg#menu__ico-second-08',
                link: '/traffic',
                active: false,
                needAuth: true,
                countMessage: false
            },
        ]
    },

}