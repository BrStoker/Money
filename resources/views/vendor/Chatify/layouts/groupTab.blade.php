@foreach($folders as $folder)
<div class="tabs__item {{ $folder['folder_name'] == 'Все' ? 'tabs__item_active' : '' }}" data-tabId="{{$folder['id']}}">
    <div class="chats">
        <div class="row row_tertiary">
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none">
                                                        <mask id="path-1-inside-1_71_18533" fill="white">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3 1C1.34315 1 0 2.34315 0 4V10C0 11.6569 1.34315 13 3 13L3 14.9661C3 15.4268 3.49769 15.7156 3.89768 15.487L8.25 13H13C14.6569 13 16 11.6569 16 10V4C16 2.34315 14.6569 1 13 1H3Z"/>
                                                        </mask>
                                                        <path d="M3 13L4.6 13L4.6 11.4H3V13ZM3 14.9661L4.6 14.9661V14.9661L3 14.9661ZM3.89768 15.487L4.69151 16.8762L3.89768 15.487ZM8.25 13V11.4H7.8251L7.45618 11.6108L8.25 13ZM1.6 4C1.6 3.2268 2.2268 2.6 3 2.6V-0.6C0.45949 -0.6 -1.6 1.45949 -1.6 4H1.6ZM1.6 10V4H-1.6V10H1.6ZM3 11.4C2.2268 11.4 1.6 10.7732 1.6 10H-1.6C-1.6 12.5405 0.45949 14.6 3 14.6V11.4ZM4.6 14.9661L4.6 13L1.4 13L1.4 14.9661L4.6 14.9661ZM3.10386 14.0978C3.77052 13.7169 4.6 14.1983 4.6 14.9661L1.4 14.9661C1.4 16.6553 3.22486 17.7143 4.69151 16.8762L3.10386 14.0978ZM7.45618 11.6108L3.10386 14.0978L4.69151 16.8762L9.04382 14.3892L7.45618 11.6108ZM13 11.4H8.25V14.6H13V11.4ZM14.4 10C14.4 10.7732 13.7732 11.4 13 11.4V14.6C15.5405 14.6 17.6 12.5405 17.6 10H14.4ZM14.4 4V10H17.6V4H14.4ZM13 2.6C13.7732 2.6 14.4 3.2268 14.4 4H17.6C17.6 1.45949 15.5405 -0.6 13 -0.6V2.6ZM3 2.6H13V-0.6H3V2.6Z" fill="#08170E" fill-opacity="0.3" mask="url(#path-1-inside-1_71_18533)"/>
                                                        <path d="M6.94095 9.22341C6.92217 9.20785 6.904 9.19115 6.88652 9.17331L4.90513 7.15082C4.63156 6.87157 4.63156 6.41882 4.90513 6.13958C5.1787 5.86033 5.62225 5.86033 5.89583 6.13958L7.49941 7.77642L10.5041 4.70944C10.7776 4.43019 11.2212 4.43019 11.4948 4.70944C11.7683 4.98868 11.7683 5.44143 11.4948 5.72068L7.99743 9.29056C7.72386 9.56981 7.28031 9.56981 7.00673 9.29056L6.94095 9.22341Z" fill="#08170E" fill-opacity="0.3"/>
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
                                {!! $invites !!}

                                @foreach ($userGroups as $userGroup=>$key)

                                    <div class="preview__item {{$id == $key['id'] ? 'preview__item_active' : ''}}" data-chat_id="{{$key['id']}}">
                                        <div class="preview__header">
                                            <img src="{{$key['image']}}" alt="">
                                        </div>
                                        <div class="preview__main">
                                            <div class="preview__title title">
                                                <span class="title__text h4">{{$key['name']}}</span>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col_8 col_mob-12">
                @include('Chatify::layouts.messageCardChat')

            </div>
        </div>

    </div>
</div>
@endforeach