<main class="layout__main">
    <section class="layout__section section section_animation">
        <div class="section__main">
            <div class="tabs">
                <div class="tabs__header">
                    <div class="tabs__list">
                        <div class="tabs__item tabs__item_active">
                            <div class="tabs__title title">
                                <span class="title__text">Диалоги</span>
                            </div>
                        </div>
                        <div class="tabs__item">
                            <div class="tabs__title title">
                                <span class="title__text">Каналы</span>
                            </div>
                        </div>
                        <div class="tabs__item">
                            <div class="tabs__title title">
                                <span class="title__text">Боты</span>
                            </div>
                        </div>
                        <div class="tabs__item">
                            <div class="tabs__title title">
                                <span class="title__text">Вопрос дня</span>
                            </div>
                        </div>
                    </div>
                    <div class="col col_8" id="btnGroupChanell">
                        <div class="dialog__actions actions">
                            <div class="actions__list">
                                <div class="actions__item">
                                    <div class="actions__preview">
                                        <svg>
                                            <use xlink:href="/image/svg/sprite.svg#dots_second"></use>
                                        </svg>
                                    </div>
                                    <div class="actions__dropdown dropdown">
                                        <div class="dropdown__list">
                                            <div class="dropdown__item modal-init" data-modalname="modal__layout_groupchat-create">
                                                <a href="#" class="dropdown__link">Создать групповой чат</a>
                                            </div>
                                            <div class="dropdown__item modal-init"  data-modalname="modal__layout_group-chanell-create">
                                                <a href="#" class="dropdown__link">Создать канал</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tabs__body">
                    <div class="tabs__list">
                        <div class="tabs__item tabs__item_active">
                            <div class="tabs">
                                <div class="tabs__header">
                                    <div class="tabs__list tabs__list_second">
                                        @include('Chatify::layouts.userFolders')
                                    </div>
                                </div>
                                <div class="tabs__body">
                                    <div class="tabs__list" id="tabs">
                                        @include('Chatify::layouts.tab')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs__item">
                            <div class="tabs">
                                <div class="tabs__header">
                                    <div class="tabs__list tabs__list_second">
                                        @include('Chatify::layouts.userFolders')
                                    </div>
                                </div>
                                <div class="tabs__body">
                                    <div class="tabs__list" id="groupsChat">
                                        @include('Chatify::layouts.groupTab')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs__item">
                            <div class="tabs">
                                <div class="tabs__header">
                                    <div class="tabs__list tabs__list_second">
                                        @include('Chatify::layouts.userFolders')
                                    </div>
                                </div>
                                <div class="tabs__body">
                                    <div class="tabs__list" id="tabs">
                                        Страница в разработке
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs__item">
                            <div class="tabs">
                                <div class="tabs__header">
                                    <div class="tabs__list tabs__list_second">
                                        @include('Chatify::layouts.userFolders')
                                    </div>
                                </div>
                                <div class="tabs__body">
                                    <div class="tabs__list" id="tabs">
                                        Страница в разработке
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


