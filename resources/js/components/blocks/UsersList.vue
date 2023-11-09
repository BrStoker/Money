<template lang="pug">
  div(class="subsection__main")
    template(v-for="(user, key) in usersList")
      div(class="series series_second align_m-center")
        div(class="series__group series__group_second")
          div(class="media mb_m-0")
            ImageElement(:src="'/image/content/users/' + user.image")
        div(class="series__group")
</template>

<script>

import ImageElement from '@/js/components/elements/Image'

export default{

    data()
    {
      return{
        usersList: [
          {
            name: 'Борис Оборин',
            description: 'Наставник экспертов',
            image: 'users__img01.jpg',
            rating: [
              {
                image: '/image/svg/sprite.svg#tooltips_01',
                text: '4,7K'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_05',
                text: '15'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_06',
                text: '236'
              },
            ],
          },
          {
            name: 'Макс Петров',
            description: 'Наставник экспертов',
            image: 'users__img02.jpg',
            rating: [
              {
                image: '/image/svg/sprite.svg#tooltips_01',
                text: '4,7K'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_05',
                text: '15'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_06',
                text: '236'
              },
            ],
          },
          {
            name: 'Ариана Д.',
            description: 'Наставник экспертов',
            image: 'users__img03.jpg',
            rating: [
              {
                image: '/image/svg/sprite.svg#tooltips_01',
                text: '4,7K'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_05',
                text: '15'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_06',
                text: '236'
              },
            ],
          },
          {
            name: 'Игорь Иванов',
            description: 'Наставник экспертов',
            image: 'users__img04.jpg',
            rating: [
              {
                image: '/image/svg/sprite.svg#tooltips_01',
                text: '4,7K'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_05',
                text: '15'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_06',
                text: '236'
              },
            ],
          },
          {
            name: 'Алёна П.',
            description: 'Наставник экспертов',
            image: 'users__img05.jpg',
            rating: [
              {
                image: '/image/svg/sprite.svg#tooltips_01',
                text: '4,7K'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_05',
                text: '15'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_06',
                text: '236'
              },
            ],
          },
          {
            name: 'Кристина Л.',
            description: 'Наставник экспертов',
            image: 'users__img06.jpg',
            rating: [
              {
                image: '/image/svg/sprite.svg#tooltips_01',
                text: '4,7K'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_05',
                text: '15'
              },
              {
                image: '/image/svg/sprite.svg#tooltips_06',
                text: '236'
              },
            ],
          },

        ],


      }
    },
    components: [
      ImageElement
    ],

  }

</script>

<style lang="scss">
</style>