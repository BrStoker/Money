<template lang="pug">

  DivElement(classCss="layout, layout_ready-load" :class="classIsModalShown()")
    HeaderBlock
    WrapperBlock
      AsideBlock
      ContentBlock
        BreadcrumbsBlock(mainText="Люди", :link="link")
        DivElement(classCss="layout__main")
          section(class="layout__section section section_animation")
              DivElement(classCss="section__main")
                DivElement(classCss="row, row_m-reverse")
                  DivElement(classCss="col, col_8, col_mob-12")
                    DivElement(classCss="section__subsection, subsection")
                      SearchForm(@update_user="update_user")
                      DivElement(classCss="subsection__main")
                        UserBlock(v-for="(user, userKey) in $store.state.data.app.users" :key="user.id" :data="user" :userType="users.user_type" )
                      DivElement(classCss="end_content")
                  FilterForm(@update_user="update_user")
        FooterBlock
    ModalLayout

</template>

<script>

  import DivElement from '@/js/components/elements/Div'
  import LinkElement from '@/js/components/elements/Link'
  import SectionElement from '@/js/components/elements/Section'
  import MainElement from '@/js/components/elements/Main'

  import HeaderBlock from '@/js/components/blocks/Header'
  import WrapperBlock from '@/js/components/blocks/Wrapper'
  import AsideBlock from '@/js/components/blocks/Aside'
  import FooterBlock from '@/js/components/blocks/Footer'
  import ContentBlock from '@/js/components/blocks/Content'
  import BreadcrumbsBlock from '@/js/components/blocks/Breadcrumbs'
  import UserBlock from '@/js/components/blocks/User'
  import SearchForm from '@/js/components/blocks/SearchForm'
  import FilterForm from '@/js/components/blocks/Filter'
  
  import ModalLayout from '@/js/components/blocks/ModalLayout'

  import ComputedIndex from '@/js/computed/pages/Index'

  import AppMethods from '@/js/methods/App'
  import Http from '@/js/classes/Http'

  export default {
    name: 'PeoplePage',
    props: ['data'],
    data() {
      return {
        UserData: this.$store.state.data.app.user,
        nishes: this.$store.state.data.app.nishes,
        users: this.$store.state.data.app.users,
        options: this.$store.state.data.app.filterOptions,
        link: '/people',
        page: 1,
        limit: 10,
        isUserScrolling: false,
        filtering: false,
        filterSchema: this.$store.state.schemas.filter
      }
    },
    components: {
      DivElement, LinkElement, SectionElement, MainElement,
      HeaderBlock, WrapperBlock, AsideBlock, BreadcrumbsBlock, SearchForm, FilterForm, ContentBlock, UserBlock, FooterBlock,
      ModalLayout

    },
    computed:{

    },
    created(){
      this.appendUserToStore(this.data)

    },
    methods: {
      ...ComputedIndex,
      ...AppMethods,
      ...Http,

			update_user(data){
				this.users = data;
			},
      handleScroll() {

        const options = {
          root: document.querySelector(".layout__overflow"),
          rootMargin: "0px",
          threshold: 1.0,
        };
        var callback = (entries, observer) => {
          entries.forEach((entry)=>{

            if(entry.isIntersecting){

                this.loadUsers()

            }
          })
        };
        var observer = new IntersectionObserver(callback, options);

        var target = document.querySelector(".end_content")

        observer.observe(target)

      },
    loadUsers(){
      this.filterUserList()
      if(this.filtering == false){
        // console.log(this.$store.state.data.app.filter)
        if(this.$store.state.data.app.filter == false){
          var sendData = new FormData
          sendData.append('page', this.page + 1)
          sendData.append('limit', this.limit)
          sendData.append('action', 'filter')

          this.sendRequest({
            method: 'POST',
            url: '/people/search',
            data: sendData,
            success: this.usersSuccess,
            error: this.handlerErrorResponse
          })
        }

      }
    },
      usersSuccess(result){
        if(result.data.code == 0){
          this.page = this.page + 1
          this.$store.commit('pushUsersToStore', result.data.users)
        }
      },
      handlerErrorResponse(result){
        console.error(result)
      },
      filterUserList(){
        if(this.$store.state.data.app.filter){
          let fd = this.SchemasToFormData(this.filterSchema)

          if (fd.keys().next().done){
            fd.append('action', 'filter')
          }

          if(fd.keys().next().done){
            this.sendRequest({
              method: 'POST',
              url: "/people/search",
              data: fd,
              success: this.filteringSuccess,
              error: this.handlerErrorResponse
            })
          }else{
            this.filtering = false
          }
        }
      },
      filteringSuccess(result){
        if(result.data.code == 0){
          this.$store.commit('updateUsers', result.data.users)
          this.filtering = true
        }else{
          this.filtering = false
        }
        this.$store.commit('clearFilterOptions')

      }
    },

    mounted(){
      window.addEventListener('scroll', this.handleScroll());
    },
  }

</script>

<style lang="scss">
</style>