<template lang="pug">
  transition(name="notification-fade")
    DivElement(v-if="show" classCss="notification")
      | {{ message }}
</template>

<script>

import DivElement from '@/js/components/elements/Div'

export default {
  props: {
    message: String,
    duration: {
      type: Number,
      default: 5000
    }
  },
  data() {
    return {
      show: false
    };
  },
  components:{
    DivElement
  },
  methods: {
    showNotification() {
      this.show = true;
      setTimeout(() => {
        this.show = false;
      }, this.duration);
    }
  },
  watch: {
    message: {
      immediate: true,
      handler(newValue) {
        if (newValue) {
          this.showNotification();
        }
      }
    }
  }
};
</script>

<style>
.notification{
  position: fixed;
  top: 140px;
  right: 10px;
  transform: translateY(-50%);
  padding: 1.25rem;
  background-color: rgb(10, 123, 55);
  color: #fff;
  border-radius: 0.75rem;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  z-index: 1000;
}
.notification-fade-enter-active, .notification-fade-leave-active{
  transition: transform 0.5s, opacity 0.5s;
}

.notification-fade-enter, .notification-fade-leave-to{
  opacity: 0;
  transform: translateX(0) scale(0.8);

}
</style>