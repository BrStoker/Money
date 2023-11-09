
export default {

    state: {
        init: false,
        load: false,
        isModalShown: false,
        isLoginShown: false,
        isForgotShown: false,
        isConfirmShown: false,
        isInterestShown: false,
        isCategoryAddShown: false,
        isComplainShown: false,
        isConfirmShown: false,
        isConfirmDeleteUserShown: false,
        isConfirmPasswordShown: false,
        filter: false,
        filterOptions: {},
        notificationMessage: '',
        user: {
            auth: false,
            data: {
                first_name: '',
                last_name: '',
                birthday: '',
                email: '',
                city_id: '',
                country_id: '',
                'fields[instagram]': '',
                'fields[interest]': [],
                'fields[signature]': '',
                'fields[telegram]': '',
                'fields[description]': '',
                'fields[nickname]': '',
                'fields[vkontakte]': '',
                'fields[youtube]': '',
                'fields[yandex_dzen]': '',
                gender: '',
                id: '',
                image: '',
                last_online_at: '',
                new: '',
                phone:  '',
                counts: [],
                score: '',
                second_name: '',
                signature: '',
                socials: [],
                subscribe: '',
                description: '',
                userInterests: [],
                view: '',
                favorite: [],
                referals: []
            },
        },
        interests: {
            steps:[
                {
                    inputs:[

                    ]
                }
            ]
        },
        countNishes: 0,
        articles: [],
        user_detail: {
            first_name: '',
            last_name: '',
            birthday: '',
            city_id: '',
            country_id: '',
            'fields[instagram]': '',
            'fields[interest]': [],
            'fields[signature]': '',
            'fields[telegram]': '',
            gender: '',
            id: '',
            image: '',
            last_online_at: '',
            new: '',
            phone:  '',
            score: '',
            second_name: '',
            signature: '',
            socials: [],
            subscribe: '',
            description: '',
            userInterests: [],
            view: '',
            favorite: []
        },
        countries: [],
        cities: [],
        articleGroups: [],
        comments: [],
        countComm: 0,
        notifications: {},
        users: []
    },
    mutations: {
        updateReferalLink: (state, value) =>{
            if(_.size(value) > 0){
                state.schemas.partners.steps[0].inputs[0].value = value
            }
        },
        updateNotifications: (state, value)=>{
            if(_.isObject(value) == true && _.size(value) > 0){
                _.forIn(value, (subValue, key)=>{
                    state.data.app.notifications = value
                })
            }
        },
        updateComments: (state, value) =>{
            if(_.isObject(value) == true && _.size(value) > 0){
                _.forIn(value, (subValue, key)=>{
                    state.data.app.comments.push(subValue)
                })
            }
        },
        updateArticleGroups: (state, value) => {

            if(_.isObject(value) == true && _.size(value) > 0) {
                _.forIn(value, (subValue, key) => {
                    state.data.app.articleGroups.push(subValue)
                })
            }
        },
        updateUsers(state, value){

            if(_.isObject(value) == true && _.size(value) > 0){
                _.forIn(value, (subValue, key) => {
                    state.data.app.users.push(subValue)
                })
            }
        },
        updateFilterOptions(state, value){
            if(_.isObject(value) == true && _.size(value) > 0){
                _.forIn(value, (subValue, key)=>{
                    state.data.app.filterOptions[value.name] = value.value
                })
            }
        },
        clearFilterOptions(state){
            state.data.app.filterOptions = {}
        },
        setCities(store,value){
            store.data.app.cities=value;
        },
        clearCities(state){
            state.data.app.cities = []
        },
        storeImage(state, image){
            state.data.app.user.data.image = image
        },
        storeArticleImage(state, image, schema){
            state.schemas.addArticle.steps[0].inputs[1].value = image
        },
        updateUsers(state, data){
            state.data.app.users = data
        },
        updateAnswerComment(state, data){
            _.forEach(state.schemas.addComment.steps, step=>{
                _.forEach(step.inputs, input=>{
                    _.forEach(data, (item, key)=>{
                        if(input.name == item.name){
                            Vue.set(input, 'value', item.value) //
                        }
                    })
                })
            })
        },
        updateNotificationMessage(state, message){

            if(state.data.app.notificationMessage.length != 0){

                state.data.app.notificationMessage = ''

            }

            state.data.app.notificationMessage = message

        },
        addFavoriteToStore(state, data){

            state.data.app.user_detail.favorite = data

        },
        pushUsersToStore(state, data){

            if(_.isObject(data) == true && _.size(data) > 0){

                state.data.app.users = state.data.app.users.concat(data)

            }

        },
        pushArticlesToStore(state, data){

            if(_.isObject(data) == true && _.size(data) > 0){

                _.forEach(data, item=>{

                    state.data.app.articles.push(item)

                })

            }

        }
    }

}