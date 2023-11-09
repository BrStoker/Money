<template lang="pug">
DivElement(:classCss="data.class")

  VueEditor(v-model="data.value" ref="editor" :name="data.name" class="vueEditor" :class="{error: data.error}" :value="data.value")

  DivElement(v-if="data.error" classCss="error") {{data.error}}

</template>

<script>

import { VueEditor } from "vue2-editor";
import DivElement from '@/js/components/elements/Div'
export default {
  props: ['data', 'onchange'],
  data() {
    return {
      contentData: this.data
    }
  },
  components: {
    VueEditor,
    DivElement
  },
  methods:{

  },
  mounted(){

  }
}
</script>

<style>
.vueEditor{
  width: 100%;
  margin-bottom: 12px;
  border-radius: 0.5rem;
  border: 0.12rem solid rgba(8, 23, 14, 0.2);

}
.vueEditor:hover{
  border-color: rgb(10, 123, 55);
}

.vueEditor .ql-editor {
  min-height: 150px;
}
.vueEditor .ql-editor:focus{
  border-color: rgb(10, 123, 55);
}
.vueEditor .ql-container{
  height: unset;
}
</style>