<template lang="pug">
DivElement(classCss="modal__layout, modal__layout_tiny, modal__layout_folder-create" :class="{'modal__layout_active': storeApp.isChatAddFolderShown}")
    DivElement(classCss="modal__action, action" :onclick="closeForm")
        SvgElement(:image="schema.image.close")
    DivElement(classCss="modal__main")
        DivElement(classCss="wysiwyg")
            h3 {{schema.title}}
        DivElement(classCss="formular")
            DivElement(classCss="formular__main")
                FormElement(:onsubmit="addFolder")
                    fieldset
                        DivElement(classCss="form__group, group")
                            DivElement(classCss="group__header")
                                DivElement(classCss="wysiwyg")
                                    h6 {{folderName.title}}
                            DivElement(classCss="group__main")
                                RowElement
                                    DivElement(classCss="col, col_12")
                                        DivElement(classCss="form-item, mb-0")
                                            InputElement(:data="folderName")
                            DivElement(classCss="wysiwyg")
                                p
                                    strong {{storeContacts.title}}
                            DivElement(classCss="series, no-wrap" v-for="(user, index) in storeContacts.contacts" :key="index")
                                DivElement(classCss="series__group, d-flex, align_center, mb-0")
                                    DivElement(classCss="form-item, mb-0")
                                        InputElement(:data="checkbox")
                                DivElement(classCss="series__group, series__group_quarty, mb-0")
                                    DivElement(classCss="media, media_tertiary, mb-0")
                                        ImageElement(:src="getUserImage(user)")
                                DivElement(classCss="series__group, d-flex, align_center, mb-0")
                                    DivElement(classCss="wysiwyg, mb-0")
                                        h6 {{getFio(user)}}
                            InputElement(:data="buttonSubmit")



</template>

<script>

import DivElement from '@/js/components/elements/Div'
import SvgElement from '@/js/components/elements/Svg'
import FormElement from '@/js/components/elements/Form'
import RowElement from '@/js/components/elements/Row'
import InputElement from '@/js/components/elements/Input'
import ImageElement from '@/js/components/elements/Image'

export default {
    name: "ChatAddFolder",

    data(){

        let data = this.$store.state.data.app,
            storeAddFolder = this.$store.state.data.chatAddFolder
        return {
            avatar: '/image/avatar.png',
            schema: this.$store.state.schemas.chatAddFolder,
            storeContacts: storeAddFolder.contactList,
            storeApp: data,
            folderName: {
                title: 'Название папки',
                name: 'folder_name',
                type: 'input',
                inputType: 'input',
                placeholder: 'Название папки',
                value: ''
            },
            checkbox: {
                name: 'check_user',
                type: 'checkbox',
                inputType: 'checkbox',
                value: false
            },
            buttonSubmit: {
                class: 'btn w-100',
                type: 'submit',
                inputType: 'submit',
                placeholder: 'Подтвердить'
            }
        }
    },
    components: {
        DivElement,
        SvgElement,
        FormElement,
        RowElement,
        InputElement,
        ImageElement
    },
    methods:{
        closeForm(){
            this.$store.commit('showChatAddFolder')
        },
        addFolder(){

        },
        getFio(user){
            return [user.first_name, user.last_name].join(' ')
        },
        getUserImage(user){
            return user.image ? '/storage/' + user.image : this.avatar
        }
    }

}
</script>

<style scoped>

</style>
