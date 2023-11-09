<template lang="pug">
DivElement(classCss="box")
  DivElement(classCss="box__list")
    RowElement
      DivElement(classCss="col, col_4, col_mob-12" v-for="(article, index) in $store.state.data.app.articles" :key="index")
        DivElement(classCss="box__item")
          DivElement(classCss="box__actions, actions" v-if="showDropdownMenu(article.user_id)")
            DivElement(classCss="actions__list")
              DivElement(classCss="actions__item" v-for="(button, subIndex) in buttons" :key="subIndex")
                DivElement(classCss="actions__preview")
                  SvgElement(:image="button.image")
                DivElement(classCss="actions__dropdown, dropdown" v-if="button.dropdown")
                  DivElement(classCss="dropdown__list" v-if="showDropdownMenu(article.user_id)")
                    DivElement(classCss="dropdown__item" v-for="(item, actionIndex) in button.dropdown" :key="actionIndex" :onclick="item.onclick")
                      LinkElement(classCss="dropdown__link" :href="item.link + article.id") {{item.text}}
          DivElement(classCss="box__media")
            LinkElement(classCss="box__link" :href="'/' + article.slug")
              ImageElement(:src="getImage(article.image)")
          DivElement(classCss="box__layout")
            DivElement(classCss="box__group")
              Tooltips(:article="article")
              DivElement(classCss="wysiwyg")
                h4
                  LinkElement(:href="'/' + article.slug") {{article.title}}
              TooltipFooter(:article="article")

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import RowElement from '@/js/components/elements/Row'
  import LinkElement from '@/js/components/elements/Link'
  import SvgElement from '@/js/components/elements/Svg'
  import SpanElement from '@/js/components/elements/Span'
  import ImageElement from '@/js/components/elements/Image'
  import Tooltips from '@/js/components/blocks/TooltipsUserArticleNew'
  import TooltipFooter from '@/js/components/blocks/TooltipsArticleFooter'
  export default {
    name: 'UserArticleBlock',

    data() {

      return {
        User: this.$store.state.data.app.user.data,
        articles: this.$store.state.data.app.articles,
        image: '/image/no-image.png',
        buttons:[
          {
            image: '/image/svg/sprite.svg#marker',
          },
          {
            image: '/image/svg/sprite.svg#dots',
            dropdown: [
                {
                  text: 'Редактировать',
                  link: '/article/edit/',
                  onclick: '',
                },
                {
                  text: 'Статистика',
                  link: '#',
                  onclick: '',
                },
                {
                  text: 'Продвигать',
                  link: '#',
                  onclick: '',
                },
                {
                  text: 'Закрепить',
                  link: '#',
                  onclick: '',
                },
                {
                  text: 'Удалить',
                  link: '/article/delete/',
                  onclick: this.showConfirm,
                },

            ],

          },
        ],
      }
    },
    components:{
      DivElement, RowElement, LinkElement, SvgElement, SpanElement, ImageElement,
      Tooltips, TooltipFooter

    },
    computed: {

    },
    methods: {
      showDropdownMenu(articleAuthor){
        return articleAuthor == this.User.id
      },
      getImage(image){
        if(image != null){
          return '/storage/' + image
        }else{
          return this.image
        }
      },
      showConfirm(e){
        e.preventDefault()

        let parts = e.target.href.split('/')

        let lastNumber = parseInt(parts[parts.length - 1])

        let modal = document.querySelector('.modal__layout_confirm-delete-article')

        modal.dataset.article_id = lastNumber

        this.$store.commit('closeConfirmForm')

      }

    },
    mounted(){
    }
  }
</script>

<style lang="scss">

</style>
