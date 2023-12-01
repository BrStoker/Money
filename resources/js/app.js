
    window._ = require('lodash');
    window.$ = require('jquery');
    window.axios = require('axios');
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    window.choises = require('choices.js');
    window.choisesItems = {};
    window.anime = require('animejs').default;
    import Choices from 'choices.js';


    // import 'summernote'
    // import 'summernote/dist/summernote.css'
    // import 'bootstrap'
    // import 'bootstrap/dist/css/bootstrap.css'
    // import 'popper.js'




    import Vue from 'vue';
    window.Vue = Vue;

    import Vuex from 'vuex';
    window.Vuex = Vuex;
    Vue.use(Vuex);

    import Fragment from 'vue-fragment'
    Vue.use(Fragment.Plugin)


    Vue.component('index', require('@/js/components/pages/Index').default);
    Vue.component('feed', require('@/js/components/pages/Feed').default);
    Vue.component('login', require('@/js/components/pages/Auth/Login').default);
    Vue.component('courses', require('@/js/components/pages/Courses/Courses').default);
    Vue.component('people', require('@/js/components/pages/People').default);
    Vue.component('income', require('@/js/components/pages/Income').default);
    Vue.component('partners', require('@/js/components/pages/Partners/Partners').default);
    Vue.component('userprofile', require('@/js/components/pages/User/Profile').default);
    Vue.component('userdetail', require('@/js/components/pages/User/Detail').default);
    Vue.component('useredit', require('@/js/components/pages/User/Edit').default);
    Vue.component('articlefull', require('@/js/components/pages/Article').default);
    Vue.component('userarticle', require('@/js/components/pages/User/UserArticle').default);
    Vue.component('userarticleedit', require('@/js/components/pages/User/UserArticleEdit').default);
    Vue.component('notification', require('@/js/components/pages/Notification/Notification').default);
    Vue.component('traffic', require('@/js/components/pages/Traffic/Traffic').default);
    Vue.component('instruments', require('@/js/components/pages/Instruments').default);
    Vue.component('course', require('@/js/components/pages/Course').default);
    Vue.component('coursefull', require('@/js/components/pages/CourseFull').default);
    Vue.component('usercourse', require('@/js/components/pages/User/UserCourse').default);
    // Vue.component('search', require('@/js/pages/Search').default);

    import Store from '@/js/store/Store'

    const app = new Vue({
        el: '#app',
        store: new Vuex.Store(Store)
        
    })
