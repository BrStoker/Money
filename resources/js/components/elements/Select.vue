<template lang="pug">
  EmptyElement
    select(:name="name" :input="input" :multiple="multiple" :class="className" :style="styleCss" :data-user-type="user_type" :required="isRequired" v-model="input.value")
      slot

</template>

<script>

import ComputedElements from '@/js/computed/elements/Index'
import LabelElement from '@/js/components/elements/Label'
import EmptyElement from '@/js/components/elements/Empty'


export default {
  name: 'SelectElement',
  props: [ 'classCss', 'classWrapper', 'name', 'multiple', 'styleCss', 'user_type', 'required', 'input'],
  components: { LabelElement, EmptyElement},
  computed:
  {
    ...ComputedElements,
    ...
    {
      isRequired()
      {
        return (this.required != undefined && this.required == true ? true : false)
      },


    }

  }
}
</script>

<style lang="scss">

</style>