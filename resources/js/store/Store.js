
import AppStore from '@/js/store/App'
import AppSettings from '@/js/settings/App'
import AppSchemas from '@/js/schemas/App'

import HeaderStore from '@/js/store/blocks/Header'
import HeaderSchemas from '@/js/schemas/blocks/Header'

import AsideStore from '@/js/store/blocks/Aside'
import FooterStore from '@/js/store/blocks/Footer'

import SearchStore from '@/js/store/blocks/Search'
import SearchSchemas from '@/js/schemas/blocks/Search'

import FilterSchemas from '@/js/schemas/blocks/Filter'
import FilterStore from '@/js/store/blocks/Filter'

import FilterCourse from '@/js/schemas/blocks/FilterCourse'

import UserSocialStore from '@/js/store/pages/User/UserSocial'

import SupportBlockSchemas from '@/js/schemas/blocks/Support'

import EditProfileSchema from '@/js/schemas/pages/User/EditProfile'

import ForgotFormSchema from '@/js/schemas/blocks/Auth/Forgot'
import ConfirmFormSchema from '@/js/schemas/blocks/Auth/Confirm'
import InterestsRegSchema from '@/js/schemas/blocks/Interests'
import AddArticleSchema from '@/js/schemas/pages/User/AddArticle'
import AddCourseSchema from '@/js/schemas/pages/User/AddCourse'
import CategoryArticleAddSchema from '@/js/schemas/blocks/Articles/ArticleCategory'
import ShareSocialSchema from '@/js/schemas/blocks/User/ShareSocial'

import PartnersSchema from '@/js/schemas/pages/partners/Partners'


import AddCommentBlockSchema from '@/js/schemas/blocks/Coments/AddComment'

import NotificationAllSchema from '@/js/schemas/pages/Notification/NotificationAll'
import AnswerFieldNotificationComment from '@/js/schemas/pages/Notification/AnswerField'

import ComplainSchema from '@/js/schemas/blocks/modals/ComplainForm'

import UserTabsStore from '@/js/store/blocks/UserTabs'
import InstrumentsTabSchema from '@/js/schemas/pages/User/instruments/instrumentsTab'
import NumberGeneratorSchema from '@/js/schemas/pages/User/instruments/numberGenerator'


import ModalLayoutStore from '@/js/store/blocks/ModalLayout'


const state  = {

    settings: {
        ...{ app: AppSettings }
    },
    schemas: {
        ...{ app: AppSchemas },
        ...{ header: HeaderSchemas },
        ...{ search: SearchSchemas },
        ...{ filter: FilterSchemas },
        ...{ support: SupportBlockSchemas },
        ...{ editpofile: EditProfileSchema },
        ...{ forgot: ForgotFormSchema },
        ...{ confirm: ConfirmFormSchema },
        ...{ interestsReg: InterestsRegSchema },
        ...{ addArticle: AddArticleSchema },
        ...{ addCategoryArticle: CategoryArticleAddSchema },
        ...{ addComment: AddCommentBlockSchema },
        ...{ shareSocial: ShareSocialSchema},
        ...{ partners: PartnersSchema },
        ...{ notificationAll: NotificationAllSchema },
        ...{ answerField: AnswerFieldNotificationComment },
        ...{ complainForm: ComplainSchema },
        ...{ filterCourse: FilterCourse },
        ...{ addCourse: AddCourseSchema },
        ...{ instrumentsTab: InstrumentsTabSchema },
        ...{ NumberGeneratorSchema: NumberGeneratorSchema }
    },
    data: {
        ...{ app: AppStore.state },
        ...{ header: HeaderStore.state },
        ...{ aside: AsideStore.state },
        ...{ footer: FooterStore.state },
        ...{ search: SearchStore.state },
        ...{ filter: FilterStore.state },
        ...{ usersocial: UserSocialStore.state },
        ...{ userTabs: UserTabsStore }

    }

}

const mutations  = {
    ...AppStore.mutations,
    ...HeaderStore.mutations,
    ...FilterStore.mutations,
    ...ModalLayoutStore.mutations
}
const actions = {
    ...FilterStore.actions
}

export default {
    state: state,
    mutations: mutations
}
