@if($get == 'find_user')
    <div class="preview__item" data-contact="{{$user['id']}}">
        <div class="preview__header">
            <img src="{{$user['image']}}" alt="">
        </div>
        <div class="preview__main">
            <div class="preview__title title">
                <span class="title__text h4">{{$user['first_name']}} {{$user['last_name']}}</span>
            </div>
            @if($user['lastMessage'] != null)
                <div class="preview__content">
                    <div class="preview__icons icons">
                        <div class="icons__list">
                            @if($user['lastMessage']['body'] != null)
                                <div class="icons__item">
                                    <svg>
                                        @if($user['lastMessage']['seen'] == 1)
                                            <use xlink:href="/image/svg/sprite.svg#checkRead"></use>
                                        @else
                                            <use xlink:href="/image/svg/sprite.svg#check"></use>
                                        @endif
                                    </svg>
                                </div>
                            @elseif($user['lastMessage']['audio'] != null)
                                <div class="icons__item">
                                    <svg>
                                        @if($user['lastMessage']['seen'] == 1)
                                            <use xlink:href="/image/svg/sprite.svg#mic"></use>
                                        @else
                                            <use xlink:href="/image/svg/sprite.svg#micRead"></use>
                                        @endif
                                    </svg>
                                </div>
                            @endif
                                <div class="preview__picture">
                                    @if($user['lastMessage'] != null)
                                        <img src="{{$user['lastMessage']['from_id']['image']}}" alt="">
                                    @endif
                                </div>
                                <div class="preview__subtitle subtitle">
                                    <span class="subtitle__text">
                                        @if($user['lastMessage']['body'] != null)
                                            @if($user['lastMessage']['seen'] == 0)
                                                <span class="chat__coincidence">{{ Str::limit($user['lastMessage']['body'], 15, '...') }}</span>
                                            @else
                                                {{ Str::limit($user['lastMessage']['body'], 15, '...') }}
                                            @endif
                                        @elseif($user['lastMessage']['attachment'] != null)
                                            @if($user['lastMessage']['seen'] == 0)
                                                <span class="chat__coincidence">Вложение</span>
                                            @else
                                                Вложение
                                            @endif
                                        @else
                                            @if($user['lastMessage']['seen'] == 0)
                                                <span class="chat__coincidence">Голосовое сообщение</span>
                                            @else
                                                Голосовое сообщение
                                            @endif
                                        @endif
                                    </span>
                                </div>

                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="preview__footer">
            @if($user['lastMessage'] != null)
                <div class="preview__date date">
                    <span class="date__text">{{ \Carbon\Carbon::parse($user['lastMessage']['created_at'])->format('H:i') }}</span>
                </div>
                @if($user['unreadMessages'] != 0)
                    <div class="preview__messages messages">
                        <span class="messages__text">{{$user['countMessages']}}</span>
                    </div>
                @endif
            @endif
        </div>
    </div>

@endif

@if($get == 'message')
    <div class="preview__item" data-msgid="{{$message['id']}}">
        <div class="preview__header">
            <img src="{{$message['from_id']['image']}}" alt="">
        </div>
        <div class="preview__main">
            <div class="preview__title title">
                <span class="title__text h6">
                    @if($message['body'] != null)
                        @if($message['seen'] == 0)
                            <span class="chat__coincidence">{{ Str::limit($message['body'], 40, '...') }}</span>
                        @else
                            {{ Str::limit($message['body'], 40, '...') }}
                        @endif
                    @elseif($message['attachment'] != null)
                        @if($message['seen'] == 0)
                            <span class="chat__coincidence">Вложение</span>
                        @else
                            Вложение
                        @endif
                    @else
                        @if($message['seen'] == 0)
                            <span class="chat__coincidence">Голосовое сообщение</span>
                        @else
                            Голосовое сообщение
                        @endif
                    @endif
                </span>
            </div>
        </div>
        <div class="preview__content">
            <div class="preview__icons icons">
                <div class="icons__list">
                    @if($message['body'] != null)
                        <div class="icons__item">
                            <svg>
                                @if($message['seen'] == 1)
                                    <use xlink:href="/image/svg/sprite.svg#checkRead"></use>
                                @else
                                    <use xlink:href="/image/svg/sprite.svg#check"></use>
                                @endif
                            </svg>
                        </div>
                    @elseif($message['audio'] != null)
                        <div class="icons__item">
                            <svg>
                                @if($message['seen'] == 1)
                                    <use xlink:href="/image/svg/sprite.svg#mic"></use>
                                @else
                                    <use xlink:href="/image/svg/sprite.svg#micRead"></use>
                                @endif
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif


@if($get == 'userForModal')

    <div class="series no-wrap" data-userid="{{$user['id']}}">
        <div class="series__group d-flex align_center mb-0">
            <div class="form-item mb-0">
                <div class="form-item__main">
                    <div class="form-item__field">
                        <div class="custom-check">
                            <label class="custom-check__label">
                                <input class="custom-check__input" type="checkbox" name="chekbox" data-id="{{$user['id']}}" {{ $checked ? 'checked="checked"' : '' }}>
                                <svg class="custom-check__ico custom-check__ico_before">
                                    <use xlink:href="/image/svg/sprite.svg#checkboxBefore"></use>
                                </svg>
                                <svg class="custom-check__ico custom-check__ico_after">
                                    <use xlink:href="/image/svg/sprite.svg#checkboxAfter"></use>
                                </svg>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="series__group series__group_quarty mb-0" onclick="checkUser({{$user['id']}})">
            <div class="media media_tertiary mb-0">
                <img src="{{$user['image']}}" alt="">
            </div>
        </div>
        <div class="series__group d-flex align_center mb-0" onclick="checkUser({{$user['id']}})">
            <div class="wysiwyg mb-0">
                <h6>{{$user['first_name']}} {{$user['last_name']}}</h6>
            </div>
        </div>
    </div>

@endif


{{----------------------- Saved Messages -------------------- --}}
@if($get == 'saved')
    <table class="messenger-list-item" data-contact="{{ Auth::user()->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
            <div class="saved-messages avatar av-m">
                <span class="far fa-bookmark"></span>
            </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ Auth::user()->id }}" data-type="user">Избранное <span>Вы</span></p>
                <span>Сохраненные сообщения</span>
            </td>
        </tr>
    </table>
@endif

{{----------------------- list users -------------------- --}}
@if($get == 'listUsers')

    <div class="preview__item {{$id == $user['id'] ? 'preview__item_active' : ''}}" data-contact="{{$user['id']}}">
        <div class="preview__header">
            <img src="{{$user['image']}}" alt="">
        </div>
        <div class="preview__main">
            <div class="preview__title title">
                <span class="title__text h4">{{$user['first_name']}} {{$user['last_name']}}</span>
            </div>
            @if($user['lastMessage'] != null)
                <div class="preview__content">
                    <div class="preview__icons icons">
                        <div class="icons__list">
                            @if($user['lastMessage']['body'] != null)
                                <div class="icons__item">
                                    <svg>
                                        <use xlink:href="/image/svg/sprite.svg#check"></use>
                                    </svg>
                                </div>
                            @elseif($user['lastMessage']['audio'] != null)
                                <div class="icons__item">
                                    <svg>
                                        <use xlink:href="/image/svg/sprite.svg#mic"></use>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($user['lastMessage']['image'])
                    <div class="preview__picture">
                        @if($user['lastMessage'] != null)
                              {!! $user['lastMessage']['image'] !!}
                        @endif
                    </div>
                    @endif
                    <div class="preview__subtitle subtitle">
                        <span class="subtitle__text">
                            @if($user['lastMessage']['body'] != null)
                                @if($user['countMessages'] != 0)
                                    <span class="chat__coincidence">{{ Str::limit(htmlspecialchars($user['lastMessage']['body']), 15, '...') }}</span>
                                @else
                                    {{ Str::limit(htmlspecialchars($user['lastMessage']['body']), 15, '...') }}
                                @endif
                            @elseif($user['lastMessage']['attachment'] != null)
                                @if($user['countMessages'] != 0)
                                    <span class="chat__coincidence">Вложение</span>
                                @else
                                    Вложение
                                @endif
                            @else
                                @if($user['countMessages'] != 0)
                                    <span class="chat__coincidence">Голосовое сообщение</span>
                                @else
                                    Голосовое сообщение
                                @endif
                            @endif
                        </span>
                    </div>
                </div>
            @endif
        </div>
        <div class="preview__footer">
            @if($user['lastMessage'])
                <div class="preview__date date">
                    <span class="date__text">{{ \Carbon\Carbon::parse($user['lastMessage']['created_at'])->format('H:i') }}</span>
                </div>
            @endif
            @if($user['countMessages'] != 0)
                <div class="preview__messages messages">
                    <span class="messages__text">{{$user['countMessages']}}</span>
                </div>
            @endif

        </div>

    </div>

@endif

{{----------------------- List Messages -------------------- --}}
@if($get == 'messageList')
    <div class="messages__group group">
        @isset($key)
        <div class="group__header">
            <div class="group__title title">
                <span class="title__text">{{$key}}</span>
            </div>
        </div>
        @endisset
        <div class="group__main">
            <div class="messages__list">
                @foreach($messages as $message)
                    <div class="messages__item {{$message['direction'] == 'sent' ? 'messages__item_primary': 'messages__item_secondary' }}" data-messageid="{{$message['id']}}">
                        <div class="messages__layout">
                            @if($message['forwarded'] == true)
                                <div class="messages__header">
                                    <div class="messages__preview preview">
                                        @if($message['forward_from'] != null)
                                       <div class="preview__attach attach">
                                           <div class="attach__media">
                                               <img data-src="{{$message['forward_from']['image']}}" src="{{$message['forward_from']['image']}}" alt="image description">
                                           </div>
                                           <div class="attach__main">
                                               <div class="attach__title title">
                                                   <span class="title__text">{{$message['forward_from']['first_name']}}</span>
                                               </div>
                                               <div class="attach__subtitle subtitle"></div>
                                           </div>
                                       </div>
                                       @endif
                                    </div>
                                </div>
                            @endif
                            @if($message['reply'] != null)
                                <div class="messages__header" data-replyMessageId="{{$message['reply']['id']}}" onclick="focusMessage('{{$message['reply']['id']}}')">
                                    <div class="messages__preview preview">
                                        <div class="preview__content">
                                            <div class="wysiwyg mb-0">
                                                <p>{!! $message['reply']['reply_message'] !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="messages__main">
                                <div class="wysiwyg">
                                    {!! $message['message'] !!}
                                </div>
                            </div>
                            @if(!$message['delete'])
                                <div class="messages__actions">
                                    <div class="actions__list">
                                        <div class="actions__item">
                                            <div class="actions__preview">
                                                <svg>
                                                    <use xlink:href="/image/svg/sprite.svg#dots_second"></use>
                                                </svg>
                                            </div>
                                            <div class="actions__dropdown dropdown">
                                                <div class="dropdown__list">
                                                    <div class="dropdown__item" data-msgid="{{$message['id']}}" onclick="replyMessage(event)">
                                                        <a href="#" class="dropdown__link">Ответить</a>
                                                    </div>
                                                    <div class="dropdown__item modal-init" data-modalname="modal__layout_forward-message" data-msgid="{{$message['id']}}">
                                                        <a href="#" class="dropdown__link">Переслать</a>
                                                    </div>
                                                    @if(!$message['isFile'])
                                                        <div class="dropdown__item" data-msgid="{{$message['id']}}" onclick="copyMessage(event)">
                                                            <a href="#" class="dropdown__link">Копировать</a>
                                                        </div>
                                                    @endif
                                                    <div class="dropdown__item modal-init" data-modalname="modal__layout_сomplaint-folder" data-msgid="{{$message['id']}}">
                                                        <a href="#" class="dropdown__link">Пожаловаться</a>
                                                    </div>
                                                    <div class="dropdown__item" data-msgid="{{$message['id']}}" onclick="deleteMessage(event)">
                                                        <a href="#" class="dropdown__link">Удалить</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="messages__footer footer">
                                <div class="footer__data data">
                                    <div class="data__list">
                                        <div class="data__item">
                                            <div class="data__title title">
                                                <span class="title__text">{{$message['date']}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="messages__reactions reactions">
                                <div class="reactions__list">
                                    @if($message['reaction'])
                                        @foreach($message['reaction'] as $reaction)
                                        <div class="reactions__item">
                                            <div class="reactions__media">
                                                    {!! $reaction !!}
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="messages__emotions emotions">
                            <div class="emotions__preview">
                                <div class="emotions__list">
                                    <div class="emotions__item">
                                        <div class="emotions__media" data-msgid="{{$message['id']}}" onclick="getReaction(event)">
                                            <svg>
                                                <use xlink:href="image/svg/sprite.svg#smile"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

{{-- -------------------- Search Item -------------------- --}}
@if($get == 'search_item')
<table class="messenger-list-item" data-contact="{{ $user->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td>
        <div class="avatar av-m"
        style="background-image: url('{{ $user->image }}');">
        </div>
        </td>
        {{-- center side --}}
        <td>
            <p data-id="{{ $user->id }}" data-type="user">
            {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
        </td>

    </tr>
</table>
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if($get == 'sharedPhoto')
<div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif

{{-- -------------------- User folder -------------------- --}}
@if($get == 'userFolders')
<div class="tabs__item {{ $folder['id'] == $id ? 'tabs__item_active' : '' }}" data-tabId="{{$folder['id']}}">
    <div class="tabs__title title">
        <span class="title__text">{{$folder['folder_name']}}</span>
    </div>
    @if($folder['countMessages'] != 0)
        <div class="tabs__count count">
            <span class="count__text">{{$folder['countMessages']}}</span>
        </div>
    @endif
    @if($folder['delete'] == false)
        <div class="tabs__actions dropdown-init actions">
            <div class="actions__preview">
                <svg>
                    <use xlink:href="/image/svg/sprite.svg#dotsSecond"></use>
                </svg>
            </div>
            <div class="actions__dropdown layout__dropdown layout__dropdown_second dropdown">
                <div class="dropdown__list">
                    <div class="dropdown__item modal-init" data-modalname="modal__layout_folder-edit" data-folderId="{{$folder['id']}}">
                            <span class="dropdown__link">
                                <div class="dropdown__title title">
                                    <div class="title__text">Редактировать</div>
                                </div>
                            </span>
                    </div>
                    <div class="dropdown__item modal-init" data-modalname="modal__layout_folder-delete" data-folderId="{{$folder['id']}}">
                            <span class="dropdown__link">
                                <div class="dropdown__title title">
                                    <div class="title__text" style="color: red">Удалить</div>
                                </div>
                            </span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endif

@if($get == 'profile_settings')
    <div class="form-item">
        <div class="form-item__main">
            <div class="toggle">
                <label class="toggle__label">
                    <input type="checkbox" class="toggle__input" {{ $setting['value'] == 1 ? 'checked' : '' }} name="{{$setting['name']}}" onchange="setSettings(event)">
                    <span class="toggle__text">{{$setting['title']}}</span>
                </label>
            </div>
        </div>
    </div>
@endif

@if($get == 'chat_settings')
    <div class="form-item">
        <div class="form-item__main">
            <div class="toggle">
                <label class="toggle__label">
                    <input type="checkbox" class="toggle__input" {{ $setting['value'] == 1 ? 'checked' : '' }} name="{{$setting['name']}}" onchange="setSettingsChat(event)">
                    <span class="toggle__text">{{$setting['title']}}</span>
                </label>
            </div>
        </div>
    </div>
@endif

@if($get == 'addFolder')
    <div class="tabs__item tabs__item_second modal-init" data-modalname="modal__layout_folder-create">
        <div class="tabs__media">
            <svg>
                <use xlink:href="/image/svg/sprite.svg#plus"></use>
            </svg>
        </div>
    </div>
@endif

@if($get == 'userTab')
    @foreach($folders as $folder)
        <div class="tabs__item {{ $folder['id'] == $id ? 'tabs__item_active' : '' }}" data-tabId="{{$folder['id']}}">
            <div class="chats">
                <div class="row">
                    <div class="col col_4 col_mob-12">
                        <div class="section__subsection subsection">
                            <div class="subsection__header">
                                <div class="formular">
                                    <div class="formular__main">
                                        <form action="#" class="form">
                                            <fieldset>
                                                <div class="form-item form-item_before mb-0">
                                                    <div class="form-item__main">
                                                        <div class="form-item__field">
                                                            <input type="text" id="search" class="form-item__input search" placeholder="Поиск" />
                                                        </div>
                                                        <div class="form-item__ico">
                                                            <svg>
                                                                <use xlink:href="/image/svg/sprite.svg#search"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="form-item__footer">
                                                        <div class="form-item__media form-item__media_second">
                                                            <svg>
                                                                <use xlink:href="/image/svg/sprite.svg#readMessage"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="subsection__main">
                                <div class="chats__preview preview">
                                    <div class="preview__list">

                                        @foreach($folder['users'] as $contact=>$key)
                                            <div class="preview__item {{$user_id == $key['id'] ? 'preview__item_active' : ''}}" data-contact="{{$key['id']}}">
                                                <div class="preview__header">
                                                    <img src="{{$key['image']}}" alt="">
                                                </div>
                                                <div class="preview__main">
                                                    <div class="preview__title title">
                                                        <span class="title__text h4">{{$key['first_name']}} {{$key['last_name']}}</span>
                                                    </div>
                                                    @if($key['lastMessage'] != null)
                                                        <div class="preview__content">
                                                            <div class="preview__icons icons">
                                                                <div class="icons__list">
                                                                    @if($key['lastMessage']['body'] != null)
                                                                        <div class="icons__item">
                                                                            <svg>
                                                                                <use xlink:href="/image/svg/sprite.svg#check"></use>
                                                                            </svg>
                                                                        </div>
                                                                    @elseif($key['lastMessage']['audio'] != null)
                                                                        <div class="icons__item">
                                                                            <svg>
                                                                                <use xlink:href="/image/svg/sprite.svg#mic"></use>
                                                                            </svg>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="preview__picture">
                                                                @if($key['lastMessage'] != null)
                                                                    {!! $key['lastMessage']['image'] !!}
                                                                @endif
                                                            </div>
                                                            <div class="preview__subtitle subtitle">
                                                        <span class="subtitle__text">
                                                            @if($key['lastMessage']['body'] != null)
                                                                @if($key['countMessages'] != 0)
                                                                    <span class="chat__coincidence">{{ Str::limit(htmlspecialchars($key['lastMessage']['body']), 15, '...') }}</span>
                                                                @else
                                                                    {{ Str::limit(htmlspecialchars($key['lastMessage']['body']), 15, '...') }}
                                                                @endif
                                                            @elseif($key['lastMessage']['attachment'] != null)
                                                                @if($key['countMessages'] != 0)
                                                                    <span class="chat__coincidence">Вложение</span>
                                                                @else
                                                                    Вложение
                                                                @endif
                                                            @else
                                                                @if($key['countMessages'] != 0)
                                                                    <span class="chat__coincidence">Голосовое сообщение</span>
                                                                @else
                                                                    Голосовое сообщение
                                                                @endif
                                                            @endif
                                                        </span>
                                                            </div>

                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="preview__footer">
                                                    @if($key['lastMessage'])
                                                        <div class="preview__date date">
                                                            <span class="date__text">{{ \Carbon\Carbon::parse($key['lastMessage']['created_at'])->format('H:i') }}</span>
                                                        </div>
                                                    @endif
                                                    @if($key['countMessages'] != 0)
                                                        <div class="preview__messages messages">
                                                            <span class="messages__text">{{$key['countMessages']}}</span>
                                                        </div>
                                                    @endif

                                                </div>

                                            </div>

                                        @endforeach


                                    </div>
                                    <div class="search-records hide"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col_8 col_mob-12">
                        @include('Chatify::layouts.messageCard')

                    </div>
                </div>

            </div>
        </div>
    @endforeach
@endif

@if($get == 'userMenu')
    <div class="menu__list">
        <div class="menu__item">
            <a href="/id{{$userId}}" class="menu__link">
                <div class="menu__media">
                    <svg class="menu__ico menu__ico_main">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-main-01"></use>
                    </svg>
                    <svg class="menu__ico menu__ico_second">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-second-01"></use>
                    </svg>
                </div>
                <div class="menu__title">
                    <span class="title__text">Профиль</span>
                </div>
            </a>
        </div>
        <div class="menu__item">
            <a href="/feed" class="menu__link">
                <div class="menu__media">
                    <svg class="menu__ico menu__ico_main">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-main-02"></use>
                    </svg>
                    <svg class="menu__ico menu__ico_second">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-second-02"></use>
                    </svg>
                </div>
                <div class="menu__title">
                    <span class="title__text">Лента</span>
                </div>
            </a>
        </div>
        <div class="menu__item">
            <a href="/courses" class="menu__link">
                <div class="menu__media">
                    <svg class="menu__ico menu__ico_main">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-main-03"></use>
                    </svg>
                    <svg class="menu__ico menu__ico_second">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-second-03"></use>
                    </svg>
                </div>
                <div class="menu__title">
                    <span class="title__text">Курсы</span>
                </div>
            </a>
        </div>
        <div class="menu__item menu__item_current">
            <a href="/chat" class="menu__link">
                <div class="menu__media">
                    <svg class="menu__ico menu__ico_main">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-main-04"></use>
                    </svg>
                    <svg class="menu__ico menu__ico_second">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-second-04"></use>
                    </svg>
                </div>
                <div class="menu__title">
                    <span class="title__text">Чат</span>
                </div>
                @if($countMessages > 0)
                    <div class="menu__preview">
                        <div class="menu__count count">
                            <span class="count__text">{{$countMessages}}</span>
                        </div>
                    </div>
                @endif

            </a>
        </div>
        <div class="menu__item">
            <a href="/people" class="menu__link">
                <div class="menu__media">
                    <svg class="menu__ico menu__ico_main">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-main-05"></use>
                    </svg>
                    <svg class="menu__ico menu__ico_second">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-second-05"></use>
                    </svg>
                </div>
                <div class="menu__title">
                    <span class="title__text">Люди</span>
                </div>
            </a>
        </div>
        <div class="menu__item">
            <a href="/income" class="menu__link">
                <div class="menu__media">
                    <svg class="menu__ico menu__ico_main">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-main-06"></use>
                    </svg>
                    <svg class="menu__ico menu__ico_second">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-second-06"></use>
                    </svg>
                </div>
                <div class="menu__title">
                    <span class="title__text">Доход</span>
                </div>
            </a>
        </div>
        <div class="menu__item">
            <a href="/partners" class="menu__link">
                <div class="menu__media">
                    <svg class="menu__ico menu__ico_main">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-main-07"></use>
                    </svg>
                    <svg class="menu__ico menu__ico_second">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-second-07"></use>
                    </svg>
                </div>
                <div class="menu__title">
                    <span class="title__text">Партнёрка</span>
                </div>
            </a>
        </div>
        <div class="menu__item">
            <a href="/traffic" class="menu__link">
                <div class="menu__media">
                    <svg class="menu__ico menu__ico_main">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-main-08"></use>
                    </svg>
                    <svg class="menu__ico menu__ico_second">
                        <use xlink:href="/image/svg/sprite.svg#menu__ico-second-08"></use>
                    </svg>
                </div>
                <div class="menu__title">
                    <span class="title__text">Трафик</span>
                </div>
            </a>
        </div>
    </div>
@endif

{{----------------------- list groups -------------------- --}}
@if($get == 'listGroups')

    <div class="preview__item {{$id == $group['id'] ? 'preview__item_active' : ''}}" data-chat_id="{{$group['id']}}">
        <div class="preview__header">
            <img src="{{$group['image']}}" alt="">
        </div>
        <div class="preview__main">
            <div class="preview__title title">
                <span class="title__text h4">{{$group['name']}}</span>
            </div>
            @if($group['lastMessage'] != null)
                <div class="preview__content">
                    <div class="preview__icons icons">
                        <div class="icons__list">
                            @if($group['lastMessage']['body'] != null)
                                <div class="icons__item">
                                    <svg>
                                        <use xlink:href="/image/svg/sprite.svg#check"></use>
                                    </svg>
                                </div>
                            @elseif($group['lastMessage']['audio'] != null)
                                <div class="icons__item">
                                    <svg>
                                        <use xlink:href="/image/svg/sprite.svg#mic"></use>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($group['lastMessage']['image'])
                        <div class="preview__picture">
                            @if($group['lastMessage'] != null)
                                {!! $group['lastMessage']['image'] !!}
                            @endif
                        </div>
                    @endif
                    <div class="preview__subtitle subtitle">
                        <span class="subtitle__text">
                            @if($group['lastMessage']['body'] != null)
                                @if($group['countMessages'] != 0)
                                    <span class="chat__coincidence">{{ Str::limit(htmlspecialchars($group['lastMessage']['body']), 15, '...') }}</span>
                                @else
                                    {{ Str::limit(htmlspecialchars($group['lastMessage']['body']), 15, '...') }}
                                @endif
                            @elseif($group['lastMessage']['attachment'] != null)
                                @if($group['countMessages'] != 0)
                                    <span class="chat__coincidence">Вложение</span>
                                @else
                                    Вложение
                                @endif
                            @else
                                @if($group['countMessages'] != 0)
                                    <span class="chat__coincidence">Голосовое сообщение</span>
                                @else
                                    Голосовое сообщение
                                @endif
                            @endif
                        </span>
                    </div>
                </div>
            @endif
        </div>
        <div class="preview__footer">
            @if($group['lastMessage'])
                <div class="preview__date date">
                    <span class="date__text">{{ \Carbon\Carbon::parse($group['lastMessage']['created_at'])->format('H:i') }}</span>
                </div>
            @endif
            @if($group['countMessages'] != 0)
                <div class="preview__messages messages">
                    <span class="messages__text">{{$group['countMessages']}}</span>
                </div>
            @endif

        </div>

    </div>

@endif

@if($get == 'invite')

    <div class="preview__item" data-invite="true">
        <div class="preview__header">
            <img src="/image/channel.png" alt="">
        </div>
        <div class="preview__main">
            <div class="preview__title title">
                <span class="title__text h4">Приглашения</span>
            </div>
            <div class="preview__content">
                <div class="preview__subtitle subtitle">
                    @foreach($invites as $key=>$invite)
                        <span class="subtitle__text">{{$invite['channel_name']}}</span>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="preview__footer">
            <div class="preview__date date">
                <span class="date__text">{{$lastInvite}}</span>
            </div>
            <div class="preview__messages messages">
                <span class="messages__text">{{$countInvites}}</span>
            </div>
        </div>
    </div>

@endif

@if($get == 'listInvites')

    <div class="chats__preview preview">
        <div class="preview__list">
            @foreach($invites as $invite)
                <div class="preview__item" data-chat_id="{{$invite['id']}}">
                    <div class="preview__header">
                        <div class="form-item__field">
                            <div class="custom-check">
                                <label class="custom-check__label">
                                    <input class="custom-check__input" type="checkbox" name="checkbox">
                                    <svg class="custom-check__ico custom-check__ico_before">
                                        <use xlink:href="/image/svg/sprite.svg#checkboxBefore"></use>
                                    </svg>
                                    <svg class="custom-check__ico custom-check__ico_after">
                                        <use xlink:href="/image/svg/sprite.svg#checkboxAfter"></use>
                                    </svg>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="preview__main d-flex">
                        <img src="{{$invite['image']}}" alt="">
                        <div class="preview__main">
                            <div class="preview__title title">
                                <span class="title__text h4">{{$invite['invite_name']}}</span>
                            </div>
                            <div class="preview__content">
                                <div class="preview__subtitle subtitle">
                                    <span class="subtitle__text">Приглашает </span> <a href="/id{{$invite['invited']['id']}}">{{$invite['invited']['first_name']}} {{$invite['invited']['last_name']}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="preview__footer d-flex">
                        <div class="form-item">
                            <div class="form-item__main">
                                <div class="form-item__field">
                                    <a class="btn btn_tertiary btn_tiny w-100" href="#" onclick="removeInvite({{$invite['id']}})">
                                        <span class="button__text">Удалить</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="form-item__main">
                                <div class="form-item__field">
                                    <a class="btn w-100" href="#" onclick="aproveInvite({{$invite['id']}})">
                                        <span class="button__text">Вступить</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            @endforeach

        </div>
    </div>

@endif

@if($get == 'headerInvite')
    <div class="d-flex w-100 align-center" id="headerInvite">
        <div class="col col_8 col_mob-12">
            <div class="series no-wrap">
                <div class="form-item mb-0">
                    <div class="form-item__main">
                        <div class="form-item__field">
                            <div class="custom-check">
                                <label class="custom-check__label">
                                    <input class="custom-check__input" type="checkbox" name="selected">
                                    <svg class="custom-check__ico custom-check__ico_before">
                                        <use xlink:href="/image/svg/sprite.svg#checkboxBefore"></use>
                                    </svg>
                                    <svg class="custom-check__ico custom-check__ico_after">
                                        <use xlink:href="/image/svg/sprite.svg#checkboxAfter"></use>
                                    </svg>
                                    <span class="custom-check__text">Выбрано (0)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col_4 col_mob-12">
            <div class="series no-wrap">
                <div class="form-item">
                    <div class="form-item__main">
                        <div class="form-item__field">
                            <a class="btn btn_tertiary btn_tiny w-100" href="#">
                                <span class="button__text">Удалить</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="form-item">
                    <div class="form-item__main">
                        <div class="form-item__field">
                            <a class="btn w-100" href="#">
                                <span class="button__text">Вступить</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif

{{----------------------- List Messages -------------------- --}}
@if($get == 'messageListChat')
    <div class="messages__group group">
        @isset($key)
            <div class="group__header">
                <div class="group__title title">
                    <span class="title__text">{{$key}}</span>
                </div>
            </div>
        @endisset
        <div class="group__main">
            <div class="messages__list">
                @foreach($messages as $message)
                    <div class="messages__item {{$message['direction'] == 'sent' ? 'messages__item_primary': 'messages__item_secondary' }}" data-messageid="{{$message['id']}}">
                        <div class="messages__layout">
                            @if($message['forwarded'] == true)
                                <div class="messages__header">
                                    <div class="messages__preview preview">
                                        @if($message['forward_from'] != null)
                                            <div class="preview__attach attach">
                                                <div class="attach__media">
                                                    <img data-src="{{$message['forward_from']['image']}}" src="{{$message['forward_from']['image']}}" alt="image description">
                                                </div>
                                                <div class="attach__main">
                                                    <div class="attach__title title">
                                                        <span class="title__text">{{$message['forward_from']['first_name']}}</span>
                                                    </div>
                                                    <div class="attach__subtitle subtitle"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if($message['direction'] == 'reseived')
                                    <div class="messages__header">
                                        <div class="messages__preview preview">
                                            <div class="preview__content">
                                                <div class="wysiwyg mb-0">
                                                    <p>{{$message['messageUserData']['first_name']}} {{$message['messageUserData']['last_name']}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endif
                            @if($message['reply'] != null)
                                <div class="messages__header" data-replyMessageId="{{$message['reply']['id']}}" onclick="focusMessageChat('{{$message['reply']['id']}}')">
                                    <div class="messages__preview preview">
                                        <div class="preview__content">
                                            <div class="wysiwyg mb-0">
                                                <p>{!! $message['reply']['reply_message'] !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="messages__main">
                                <div class="wysiwyg">
                                    {!! $message['message'] !!}
                                </div>
                            </div>
                            @if(!$message['delete'])
                                <div class="messages__actions">
                                    <div class="actions__list">
                                        <div class="actions__item">
                                            <div class="actions__preview">
                                                <svg>
                                                    <use xlink:href="/image/svg/sprite.svg#dots_second"></use>
                                                </svg>
                                            </div>
                                            <div class="actions__dropdown dropdown">
                                                <div class="dropdown__list">
                                                    <div class="dropdown__item" data-msgid="{{$message['id']}}" onclick="replyMessageChat(event)">
                                                        <a href="#" class="dropdown__link">Ответить</a>
                                                    </div>
                                                    <div class="dropdown__item modal-init" data-modalname="modal__layout_forward-message" data-msgid="{{$message['id']}}">
                                                        <a href="#" class="dropdown__link">Переслать</a>
                                                    </div>
                                                    @if(!$message['isFile'])
                                                        <div class="dropdown__item" data-msgid="{{$message['id']}}" onclick="copyMessageChat(event)">
                                                            <a href="#" class="dropdown__link">Копировать</a>
                                                        </div>
                                                    @endif
                                                    <div class="dropdown__item modal-init" data-modalname="modal__layout_сomplaint-folder" data-msgid="{{$message['id']}}">
                                                        <a href="#" class="dropdown__link">Пожаловаться</a>
                                                    </div>
                                                    <div class="dropdown__item" data-msgid="{{$message['id']}}" onclick="deleteMessageChat(event)">
                                                        <a href="#" class="dropdown__link">Удалить</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="messages__footer footer">
                                <div class="footer__data data">
                                    <div class="data__list">
                                        <div class="data__item">
                                            <div class="data__title title">
                                                <span class="title__text">{{$message['date']}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="messages__reactions reactions">
                                <div class="reactions__list">
                                    @if($message['reaction'])
                                        @foreach($message['reaction'] as $reaction)
                                            <div class="reactions__item">
                                                <div class="reactions__media">
                                                    {!! $reaction !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="messages__emotions emotions">
                            <div class="emotions__preview">
                                <div class="emotions__list">
                                    <div class="emotions__item">
                                        <div class="emotions__media" data-msgid="{{$message['id']}}" onclick="getReactionChat(event)">
                                            <svg>
                                                <use xlink:href="image/svg/sprite.svg#smile"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
