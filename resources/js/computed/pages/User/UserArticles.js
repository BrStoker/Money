export default{

    tabsItemClass(index, cssClass = []) {

        if(_.isArray(cssClass) == false) {
            cssClass = []
        }

        if(this.activeTab == index) {
            cssClass.push('tabs__item_active')
        }

        return cssClass.join(',')

    }

}