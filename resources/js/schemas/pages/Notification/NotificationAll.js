export default{

    method: 'POST',
    url: '/user/notification',
    steps:[
        {
            title: 'Комментарии',
            show: false,
            name: 'comment'
        },
        {
            title: 'Подписки',
            show: false,
            name: 'subscribe'
        },
        {
            title: 'Лайки',
            show: false,
            name: 'score'
        }
    ]



}