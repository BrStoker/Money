@foreach($folders as $folder)
    <div class="tabs__item {{ $folder['folder_name'] == 'Все' ? 'tabs__item_active' : '' }}" data-tabId="{{$folder['id']}}">
        <div class="tabs__title title">
            <span class="title__text">{{$folder['folder_name']}}</span>
        </div>
        @if($folder['countMessages'] != 0)
            <div class="tabs__count count">
                <span class="count__text">{{$folder['countMessages']}}</span>
            </div>
        @endif
        @if($folder['delete'] != false)
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
@endforeach
<div class="tabs__item tabs__item_second modal-init" data-modalname="modal__layout_folder-create">
    <div class="tabs__media">
        <svg>
            <use xlink:href="/image/svg/sprite.svg#plus"></use>
        </svg>
    </div>
</div>