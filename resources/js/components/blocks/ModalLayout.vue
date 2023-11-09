<template lang="pug">
  DivElement(classCss="layout__modal, modal")
    LoginForm
    ForgotForm
    ConfirmDeleteArticle
    InterestRegForm
    AddArticleForm
    ComplainForm
    ConfirmPassword
</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LoginForm from '@/js/components/blocks/LoginForm'
  import ForgotForm from '@/js/components/blocks/ForgotForm'
  import ConfirmForm from '@/js/components/blocks/ConfirmForm'
  import InterestRegForm from '@/js/components/blocks/InterestsFormReg'
  import AddArticleForm from '@/js/components/blocks/CategoryAddForm'
  import ComplainForm from '@/js/components/blocks/modals/Complain'
  import ConfirmDeleteArticle from '@/js/components/blocks/modals/ConfirmDeleteArticle'
  import ConfirmPassword from '@/js/components/blocks/modals/ConfirmPassword'



  export default{
    data(){

      return{

      }
    },
    components:{
      DivElement,
      LoginForm,
      ForgotForm,
      ConfirmForm,
      InterestRegForm,
      AddArticleForm,
      ComplainForm,
      ConfirmDeleteArticle,
      ConfirmPassword

    }
  }


</script>

<style lang="scss" scoped>
</style>