<template lang="pug">
DivElement(classCss="form-item__avatar, avatar")
  LabelElement(classCss="avatar__label")
    LinkElement(classCss="user-avatar" :href="link")
    input(class="avatar__input" :type="input.type" :name="input.name" @change="onInputChange" ref="fileInput")
    ImageElement(classCss="avatar__img" :src="getImage" @click="OnClick")
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
    name: 'ImageBlock',
    props: ['input', 'onchange'],
    data() {
      return {
        link: '/user/profile',
        imageUrl: this.input.value
      }
    },
    components:{
      DivElement, LabelElement, LinkElement, ImageElement, InputElement, SpanElement
    },
    computed: {
      getImage(){
        return this.input.value ? '/storage/' +  this.input.value : '/image/no-image.png'
      }
    },
    methods: 
    {
      ...HttpClass,
      OnClick()
      {
        this.$refs.fileInput.click()
      },
      onInputChange(event) {
        if (this.element !== undefined) {
          if (typeof this.onchange === 'function') {
            this.onchange(event, this.element);
          }
        } else {
          if (typeof this.onchange === 'function') {
            this.onchange(event);
          }
        }
      },
      editImage(e)
      {
        e.preventDefault()

        // let element = e.target,
        //     input = undefined
        // let file = e.target.files[0]
        // if (file) {
        //   const reader = new FileReader()
        //   console.log(e.target.result)
        //   reader.onload = (e) => {
        //     this.input.value = e.target.result
        //   }
        //
        //   reader.readAsDataURL(file)
        //
        // } else {
        //   this.input.value = null
        // }

        // this.input.value = '/image/' + file.name
        let fd = new FormData()
        fd.append('image', e.target.files[0])
        this.sendRequest({
          method: 'POST',
          url: '/article/image',
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
          this.input.value = result.data.image
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

    },
    created(){
      console.log('image', this.input)
    }
  }
</script>

<style lang="scss">

</style>