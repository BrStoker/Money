<div class="messages__item {{ $direction ? 'messages__item_primary' : 'messages__item_secondary' }}" data-messageid="{{$msgId}}">
    <div class="messages__header" data-replyMessageId="{{$reply['id']}}" onclick="focusMessage('{{$reply['id']}}')">
        <div class="messages__status status">
            <div class="status__media">

            </div>
            <div class="status__title title">
            </div>
            <span class="title__text">{{$dateTitle}}</span>
            <div class="actions__preview dropdown-init">
                <svg class="maxHeight">
                    <use xlink:href="/image/svg/sprite.svg#dots"></use>
                </svg>
                <div class="menu__dropdown layout__dropdown dropdown">
                    <div class="dropdown__list">
                        <div class="dropdown__item" data-msgid="${message.id}" onclick="replyMessage(event)">
                            <a href="#" class="dropdown__link">
                                <div class="dropdown__title title">
                                    <span class="title__text">Ответить</span>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown__item" data-msgid="${message.id}" onclick="getReaction(event)">
                            <a href="#" class="dropdown__link">
                                <div class="dropdown__title title">
                                    <span class="title__text">Поставить реакцию</span>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown__item modal-init" data-modalname="modal__layout_forward-message" data-msgid="${message.id}">
                            <a href="#" class="dropdown__link">
                                <div class="dropdown__title title">
                                    <span class="title__text">Переслать</span>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown__item" data-msgid="${message.id}" onclick="copyMessage(event)">
                            <a href="#" class="dropdown__link">
                                <div class="dropdown__title title">
                                    <span class="title__text">Копировать</span>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown__item modal-init" data-modalname="modal__layout_сomplaint-folder" data-msgid="${message.id}">
                            <a href="#" class="dropdown__link">
                                <div class="dropdown__title title">
                                    <span class="title__text">Пожаловаться</span>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown__item" data-msgid="${message.id}" onclick="deleteMessage(event)">
                            <a href="#" class="dropdown__link">
                                <div class="dropdown__title title">
                                    <span class="title__text" style="color: red">Удалить</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="messages__status status">

    </div>
    <div class="messages__main">
        <div class="wysiwyg">
            {!! $message !!}
        </div>
    </div>
    <div class="messages__footer">
        <div class="messages__date date">
            <span class="date__text">{{$timeTitle}}</span>
        </div>
    </div>
</div>