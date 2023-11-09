<template lang="pug">
.tost-wrapper(@click.stop="eventClick" :class="className") {{data}}
  .tost__button &#215;
</template>

<script>
  
  export default {
    name: 'TostElement',
    props: ['error', 'data'],
    data() {
      return {
        class: 'tost-wrapper',
        classError: 'tost-wrapper__type_error',
        classHidden: 'tost-wrapper__display_none'
      }
    },
    computed:{
      className()
      {
        return (this.error != undefined && parseInt(this.error) == 1 ? this.classError : '')
      }
    },
    methods:{
      eventClick(e) {

        let classHidden = 'tost-wrapper__display_none'

        let element = e.target

        if(element.classList.contains(this.class) == false) {

          element = element.closest('.' + this.class)

        }

        if(element != null && !element.classList.contains(this.classHidden)) {

          element.classList.add(this.classHidden)

          element.remove()

        }
        
      }

    }

  }

</script>

<style lang="scss">

</style>