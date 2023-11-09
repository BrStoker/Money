<template lang="pug">
ul.list(v-if="!type.length" :class="className" :style="styleCss")
  li.list__item(v-for="(value, key) in items" :key="key" :class="classNameItem" :style="styleItem") {{ value }}
ol.list.list__type_numeric(v-else-if="type == 'numeric'" :class="className" :style="styleCss")
  li.list__item.list__item__type_numeric(v-for="(value, key) in items" :key="key" :class="classNameItem" :style="styleItem") {{ value }}
</template>

<script>

  import ComputedElements from '@/js/computed/elements/Index'

  export default {
    name: 'ListElement',
    props:[ 'type', 'items', 'styleCss', 'styleItem', 'classCss', 'classItem' ],
    computed:
    { ...ComputedElements }
  }

</script>

<style lang="scss">

</style>