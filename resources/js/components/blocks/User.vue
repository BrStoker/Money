<template lang="pug">
    DivElement(classCss="series, series_second, align_m-center")
      DivElement(classCss="series__group, series__group_second")
        DivElement(classCss="media, mb_m-0")
          ImageElement(:src="getAvatar")
      DivElement(classCss="series__group")
        DivElement(classCss="wysiwyg, mb-0")
          h3
            LinkElement(:href="getLink")
              strong {{dataUser.first_name}} {{dataUser.last_name}}
          p {{signature}}
        Tooltips(:user="data")
      DivElement(classCss="series__group, ml_m-auto")
        DivElement(classCss="buttons, mb-0")
          DivElement(classCss="buttons__list, direction_m-column, align_m-end")
            template(v-for="(button, index) in buttons")
              DivElement(classCss="buttons__item")
                LinkElement(:classCss="button.class" v-if="index == 0" :onclick="button.onclick")
                  span(class="btn__text") {{buttonText}}
                LinkElement(:href="button.link" :classCss="button.class" v-else :onclick="button.onclick")
                  span(class="btn__text") {{button.text}}
</template>

<script>

    // import ScoresBlock from '@/js/components/blocks/Scores'
    import RowElement from '@/js/components/elements/Row'
    import DivElement from '@/js/components/elements/Div'
    import LinkElement from '@/js/components/elements/Link'
    import ImageElement from '@/js/components/elements/Image'
    import SpanElement from '@/js/components/elements/Span'
    import Tooltips from '@/js/components/blocks/Tooltips'
    import MethodsUser from '@/js/methods/User'

    import HttpClass from '@/js/classes/Http'


    export default {
        name: 'UsersBlock',
        props:[ 'data', 'userType', 'filter' ],
        data() {

          return {
            dataUser: this.data,
            isSubscribe: this.data.isSubscribe,
            user: this.$store.state.data.app.user,
            avatar: '/image/avatar.png',
            link: this.getLink,
            signature: this.data.signature,
            buttons: [
              {
                class: 'btn, btn_tiny',
                text: '',
                href: '#',
                value: '',
                onclick: this.$store.state.data.app.user.auth ? this.isSubscribe ? ()=>this.unSubscribe() : ()=>this.subscribe() : ()=>this.showLoginForm()
              },
              {
                class: 'btn, btn_tiny, btn_tertiary',
                text: 'Написать сообщение',
                href: '#',
                value: '',
                onclick: this.$store.state.data.app.user.auth ? ()=>this.writeMessage() : ()=>this.showLoginForm()
              },
            ],

          }

        },
        computed: {
            getAvatar()
            {
              return (this.dataUser && this.dataUser.image? '/storage/' + this.dataUser.image : this.avatar );
            },
            getLink()
            {
              return '/id' + this.dataUser.id
            },
          buttonText() {
            return this.isSubscribe ? 'Отписаться' : 'Подписаться';
          }
        },
        components: {
            // ScoresBlock,
          Tooltips, SpanElement,
            RowElement, DivElement, LinkElement, ImageElement
        },
        methods: {
          ...MethodsUser,
          ...HttpClass,
          subscribe(){

            let fd = new FormData()
            fd.append('currentUser', this.$store.state.data.app.user.data.id)
            fd.append('userId', this.dataUser.id)
            fd.append('subscribe', true)
            this.sendRequest({
              method: 'POST',
              url: "/subscribe",
              data: fd,
              success: this.confirmSuccess,
              error: this.handleErrorResponse
            })
          },
          confirmSuccess(result){
            if (result.data.code == 0){
              this.isSubscribe = result.data.subscribe
            }
          },
          handleErrorResponse(result){
            console.log(result)
          },
          unSubscribe(){
            let fd = new FormData()
            fd.append('currentUser', this.$store.state.data.app.user.data.id)
            fd.append('userId', this.dataUser.id)
            fd.append('subbscribe', false)
            this.sendRequest({
              method: 'POST',
              url: "/subscribe",
              data: fd,
              success: this.confirmSuccess,
              error: this.handleErrorResponse
            })
          },
          writeMessage(){
            let fd = new FormData()
            fd.append('user_id', this.dataUser.id)
            this.sendRequest({
              method: 'POST',
              url: '/write-message',
              data: fd,
              success: this.succesWrite,
              error: this.handleErrorResponse
            })
          },
          succesWrite(result){
            if(result.data.success){
              window.location.href = result.data.redirectTo
            }
          },
          showLoginForm(){
            this.$store.commit('closeModalLogin')
          }

        },

      created(){
        this.isSubscribe = this.dataUser.isSubscribe
      },
      mounted(){

      }
    }
</script>

<style lang="scss">
</style>