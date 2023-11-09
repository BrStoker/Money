<template lang="pug">
a.link(@click="OnClick" :href="url" :title="title" :class="className" :target="target" :style="styleCss" target="_blank")
  slot
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'

  export default {
    name: 'LinkElement',
    props:[ 'href', 'title', 'classCss', 'target', 'styleCss', 'onclick', 'element', 'article', 'name'],
    computed:
    {
      ...ComputedElements,
      url(){
        switch (this.name){
          case "telegram":
              return `https://t.me/share/url?url=${window.location.href}&text=${this.article.title}`
            break
          case "vkontakte":
            return `http://vk.com/share.php?url=${window.location.href}&title=${this.article.title}&description=${this.article.preview}&image=${window.location.origin + '/storage/' + this.article.image}&noparse=true`
            break
          case "watsapp":
            return `whatsapp://send?text=${window.location.href}`
            break
          default: return this.href
        }
      }
    },
    methods:{

      OnClick(e)
      {

        if(this.element != undefined)
        {

          if(typeof this.onclick == 'function')
          {
            this.onclick(e, this.element)
          }

        } else {

          if(typeof this.onclick == 'function')
          {
            this.onclick(e)
          }

        }
        
      }
    },
    mounted(){

    }
  }
</script>

<style lang="scss">
</style>