
export default {

    getPlatform(user_platforms, user_type = undefined)
    {

        if(typeof(user_platforms) != 'undefined') {

            let platforms = [ 'instagram',  'tik-tok', 'youtube', 'facebook', 'vkontakte' ],
                platform = false

            if(platforms.length > 0) {

                platforms.forEach(function (value) {

                    if(user_platforms.hasOwnProperty(value) == true) {

                        let item = (user_type != undefined ? user_platforms[value][user_type] : user_platforms[value])

                        if(item.link.length > 0 && parseInt(item.subscribers) > 0) {

                            platform = {
                                link: item.link,
                                subscribers: item.subscribers,
                                image: '/svg/' + value + '.svg'
                            }

                            return false

                        }

                    }


                })

            }

            if(platform) {
                return platform
            }

        }

    },

    shortenSubscribers(num) {
        
        if( typeof(num) == 'number') {

            let nums = {
                0 : "",
                1 : " K",
                2 : " M",
                3 : " T",
            };
        
            let thousands = Math.floor( (("" + num).length - 1) / 3 );
            
            let coef = 1000 ** thousands;
            
            let result = ( num / coef ).toFixed(1);

            let tmp = result.toString().split('.');

            if(tmp.length > 1 && parseInt(tmp.pop()) == 0) {

                result = tmp.shift()

            }

            return result + nums[ thousands ]

        }

      }

}