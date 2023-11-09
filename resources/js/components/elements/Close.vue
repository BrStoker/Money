<template lang="pug"> 
div(:class="getClass" @click="$attrs.onclick") &#215;
</template>

<script>

    import LinkElement from '@/js/components/elements/Link'

    export default {
        name: 'CloseElement',
        prop: ['active', 'asideState', 'onclick'],
        components: {
            LinkElement
        },
        computed: {
            getClass() {


                let classCss = 'aside__close'

                if(this.$attrs.active == true && this.$attrs.asideState == true) {
                    classCss += ' aside__close__state_active'
                }

                return classCss
            }
        }

    }
</script>
<style scoped lang="scss">

</style>