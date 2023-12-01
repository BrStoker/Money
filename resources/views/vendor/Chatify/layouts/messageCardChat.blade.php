<div class="section__subsection subsection p-0 hide">
    <div class="subsection__main">
        <div class="chats__dialog dialog hide">
            <div class="dialog__header">
                <div class="row">
                    <div class="col col_8" id="titleDiv" style="display: block;">
                        <div class="dialog__title title">
                            <span class="title__text h4"></span>
                        </div>
                        <div class="dialog__status status">
                            <span class="status__text"></span>
                        </div>
                    </div>
                    <div class="col col_8" id="searchDiv" style="display: none;">
                        <div class="form-item form-item_before mb-0">
                            <div class="form-item__main">
                                <div class="form-item__field">
                                    <input type="text" id="searchCorrespondence" class="form-item__input search" placeholder="Поиск" />
                                </div>
                                <div class="form-item__ico">
                                    <svg>
                                        <use xlink:href="/image/svg/sprite.svg#search"></use>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col_4">
                        <div class="dialog__actions actions">
                            <div class="actions__list">
                                <div class="actions__item">
                                    <div class="actions__preview">
                                        <svg onclick="searchMessage(event)">
                                            <use xlink:href="/image/svg/sprite.svg#search"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="actions__item">
                                    <div class="actions__preview">
                                        <svg>
                                            <use xlink:href="/image/svg/sprite.svg#dots_second"></use>
                                        </svg>
                                    </div>
                                    <div class="actions__dropdown dropdown">
                                        <div class="dropdown__list">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dialog__main">
                <div class="dialog__messages messages">

                </div>
            </div>
            <div class="dialog__footer">
                @include('Chatify::layouts.sendFormChat')
            </div>
        </div>
    </div>
</div>