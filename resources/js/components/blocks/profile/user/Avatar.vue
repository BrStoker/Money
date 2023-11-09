<template lang="pug">
DivElement(classCss="form-item__avatar, avatar")
  LabelElement(classCss="avatar__label")
    LinkElement(classCss="user-avatar" :href="link")
    input(class="avatar__input" :type="input.type" :name="input.name" @change="editImage" ref="fileInput")
    ImageElement(classCss="avatar__img" :src="getAvatar" v-if="viewEditButton()" @click="OnClick")
    SpanElement(classCss="avatar__title") {{input.placeholder}}

  //LinkElement(classCss="user-avatar" :href="link")
  //ImageElement(classCss="image__name_avatar" :src="getAvatar")
  //ImageElement(:onclick="editImage" classCss="avatar__input" src="/svg/pen.svg" v-if="viewEditButton()")
</template>

<script>


  import DivElement from '@/js/components/elements/Div'
  import LabelElement from '@/js/components/elements/Label'
  import LinkElement from '@/js/components/elements/Link'
  import ImageElement from '@/js/components/elements/Image'
  import InputElement from '@/js/components/elements/Custom/CustomInput'
  import SpanElement from '@/js/components/elements/Span'
  import HttpClass from '@/js/classes/Http'

  export default {
    name: 'UserAvatarBlock',
    props: ['user', 'input', 'onchange'],
    data() {
      return {
        link: '/user/profile',
        avatar: '/image/avatar.png'
      }
    },
    components:{
      DivElement, LabelElement, LinkElement, ImageElement, InputElement, SpanElement
    },
    computed: {
      getAvatar() {
        return (this.user && this.user.image ? '/storage/' + this.user.image : this.avatar );
      }
    },
    methods: 
    {
      ...HttpClass,
      OnClick()
      {
        this.$refs.fileInput.click()
      },
      editImage(e)
      {
        e.preventDefault()

        let element = e.target,
            input = undefined
        let file = e.target.files[0]
        // console.log('avatar->',file)
        let fd = new FormData()
        fd.append('image', e.target.files[0])
        this.sendRequest({
          method: 'POST',
          url: '/user/image',
          data: fd,
          headers: { 'Content-Type': 'multipart/form-data' },
          success: this.UploadSuccess,
          error: this.handlerErrorResponse
        })
        // if (element.nextSibling != null && element.tagName == 'INPUT' && element.type == 'file')
        // {
        //   element.nextSibling.remove()
        // }

        // if (file.type.match('image.*') && file.type.indexOf('image.*') === -1) {
        //   this.avatar = URL.createObjectURL(file)
        //   this.user.image = this.avatar
        // }
        // location.href = this.link

      },
      UploadSuccess(result){
        if(result.data.code == 0){
          this.$store.commit('storeImage', result.data.image)
        }
      },
      handlerErrorResponse(result){
        console.log(result)
      },
      viewEditButton()
      {
        return this.user
      }
    },
    mounted(){
      // console.log()
    }
  }
</script>

<style lang="scss">

</style>