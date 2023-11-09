export default {

    appendDataToSchema(schema, input) {

        let error = true

        if(_.has(schema, 'steps') == true) {

            if( _.isArray(schema.steps) == true && _.size(schema.steps) > 0 && _.isObject(input) == true && _.size(input) > 0) {
                error = false
            }
            
        }

        if(error == false) {
            
            _.forEach(schema.steps, function(row) {

                if(_.has(row, 'inputs') == true) {

                    if(_.isArray(row.inputs) == true && _.size(row.inputs) > 0) {

                        _.forEach(row.inputs, function(item) {
                            if(_.has(input, item.name) == true) {
                                item.value = input[item.name]
                            }

                        })

                    }

                }

            })

        }

        return schema

    },

    appendUserToStore(data, jsonParse = true){

        let appData

        if(jsonParse) {
            appData = JSON.parse(data)
        } else {
            appData = data
        }


        let userData = appData.user

        let store = this.$store.state.data.app

        let StoreArticle = this.$store.state.schemas.addArticle

        let userStore = store.user.data

        if(_.has(appData, 'user') == true && appData.user != null){

            for(let key in appData.user){

                if(userStore[key] != undefined){

                    userStore[key] = userData[key]

                }

            }

            store.user.auth = true

        }

        if(_.has(appData, 'interests') == true && appData.interests != null){

            let inputsInteres = []

            let input = store.interests.steps[0].inputs

            // if(input.length > 0){
            //     input.length = 0
            // }

            for(let interes in appData.interests){
                let checkValue = false
                let item = appData.interests[interes]

                if(_.has(appData, 'user') == true && appData.user != null){
                    let user = appData.user

                    if(user.new == false){
                        if(_.has(user, 'userInterests') == true && user.userInterests != null){
                            for(let key in user.userInterests){
                                let ids = user.userInterests[key].name.split('_')
                                if(item['id'] == ids[ids.length-1]){
                                    checkValue = true
                                }
                            }
                        }
                    }
                }

                inputsInteres.push({

                    inputType: 'checkbox',

                    checkboxBefore: '/image/svg/sprite.svg#checkboxBefore',

                    checkboxAfter: '/image/svg/sprite.svg#checkboxAfter',

                    value: checkValue,

                    type: 'checkbox',

                    text: item.value,

                    id: item.id,

                    name: 'interes_id_' + item.id
                })

            }

            input.push(...inputsInteres)

            store.countNishes = _.size(appData.interests)

        }

        if(_.has(appData, 'notifications') == true && appData.notifications != null){
            this.$store.commit('updateNotifications', appData.notifications)
        }

        if(_.has(appData, 'comments') == true && appData.comments != null){
           this.$store.commit('updateComments', appData.comments)
        }

        // if(_.has(appData, 'article') == true && appData.article != null ){
        //     for(let key in appData.article){
        //
        //     }
        // }

        if(_.has(appData, 'language') == true && appData.language != null){
            for(let key in appData.language){
                store.language[key] = appData.language[key]
            }
        }

        if(_.has(appData, 'refurl') == true && appData.refurl != null){
            this.$store.commit('updateReferalLink', appData.refurl)
        }

        if(_.has(appData, 'articles') == true && appData.articles != null){

            store.articles = appData.articles

        }

        if(_.has(appData, 'user_detail') == true && appData.user_detail != null){
            for(let key in appData.user_detail){
                store.user_detail[key] = appData.user_detail[key]
            }
            if(_.has(appData, 'isSubscribe') == true && appData.isSubscribe != null){
                store.user_detail['isSubscribe'] = appData.isSubscribe
            }
        }

        if(_.has(appData, 'countries') == true && appData.countries != null){
            for(let key in appData.countries){
                store.countries[key] = appData.countries[key]
            }
        }

        if(_.has(appData, 'articlegroups') == true && appData.articlegroups != null){
            this.$store.commit('updateArticleGroups', appData.articlegroups);
        }
        if(_.has(appData, 'users') == true && appData.users != null){
            this.$store.commit('updateUsers', appData.users);
        }



    },

    appendSocialtoData(data){

        let keys = Object.keys(data)

        let store = this.$store.state.data.usersocial
        let error = true
        if (_.has(store, 'socials') == true){
            if(_.isArray(store.socials) == true){
                error = false
            }
        }
        if(error == false){
            _.forEach(store.socials, function(row) {
                keys.forEach(function(key) {
                    if(key.match(row.name.replace(/ /g, "_").toLowerCase())){
                        row.link = data[key]
                        row.show = true
                    }
                })
            })
        }


    },

    appendCityToData(data){

        cities = JSON.parse(data)

        store = this.$store.state.data.app.cities

        for(let key in cities){
            store[key] = cities[key]
        }

    },

    getAvatar(user){
        return (user && user.avatar? '/storage/' + user.avatar : '/image/avatar.png');
    },

    appendArticleIdToSchema(schema, data){


        if(_.has(schema, 'steps') == true){
            _.forEach(schema.steps, function(step) {
                if(_.has(step, 'inputs') == true) {
                    _.forEach(step.inputs, function(input){
                        if(input.name == 'article_group_ids'){
                            _.forEach(data, function(checkbox){
                                let id = checkbox.name.split('_')
                                if(input.value.includes(id[id.length-1])){
                                    checkbox.value = true
                                }

                            })
                        }
                    })

                }
            })
            }
        }

}