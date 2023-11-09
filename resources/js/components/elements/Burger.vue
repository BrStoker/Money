<template lang="pug">
div(:class="getClass" @click="$attrs.onclick")
    ImageElement(src="/svg/burger.svg" classCss="aside__hamburger-image")
</template>

<script>

    import LinkElement from '@/js/components/elements/Link'
    import ImageElement from '@/js/components/elements/Image'

    export default {
        name: 'BurgerElement',
        prop: ['active', 'asideState', 'onclick'],
        components: {
            LinkElement, ImageElement
        },
        computed: {
            getClass() {
                let classCss = 'aside__hamburger'

                if(this.$attrs.active == true && this.$attrs.asideState == false) {
                    classCss += ' aside__hamburger__state_active'
                }

                return classCss
            }
        }
    }
</script>
<style scoped lang="scss">

</style>