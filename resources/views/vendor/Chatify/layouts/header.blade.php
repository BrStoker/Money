<header class="layout__header header">
    <div class="header__logo logo">
        <a href="/" class="logo__link">
            <img src="{{asset('/image/svg/logo.svg')}}"/>
        </a>
    </div>
    <div class="header__menu menu">
        <div class="menu__list">
            <div class="menu__item">
                <a href="/user/notification">
                    <div class="menu__preview">
                        <div class="menu__media">
                            <svg>
                                <use xlink:href="/image/svg/sprite.svg#notification"></use>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            <div class="menu__item dropdown-init">
                <div class="menu__preview">
                    <div class="menu__avatar">
                        <img src="{{ $user->image ? '/storage/'. $user->image : 'image/avatar.png' }}" alt="">
                    </div>
                    <div class="menu__action">
                        <svg class="btn__ico">
                            <use xlink:href="/image/svg/sprite.svg#arrowBottom"></use>
                        </svg>
                    </div>
                </div>
                <div class="menu__dropdown layout__dropdown dropdown">
                    <div class="dropdown__list">
                        <div class="dropdown__item">
                            <a href="/user/profile" class="dropdown__link">
                                <div class="dropdown__media">
                                    <svg class="btn__ico">
                                        <use xlink:href="/image/svg/sprite.svg#menu__ico01"></use>
                                    </svg>
                                </div>
                                <div class="dropdown__title title">
                                    <span class="title__text">Профиль</span>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown__item">
                            <a href="#" class="dropdown__link">
                                <div class="dropdown__media">
                                    <svg class="btn__ico">
                                        <use xlink:href="/image/svg/sprite.svg#menu__ico02"></use>
                                    </svg>
                                </div>
                                <div class="dropdown__title title">
                                    <span class="title__text">Посмотреть статистику</span>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown__item">
                            <a href="/logout" class="dropdown__link">
                                <div class="dropdown__media">
                                    <svg class="btn__ico">
                                        <use xlink:href="/image/svg/sprite.svg#menu__ico03"></use>
                                    </svg>
                                </div>
                                <div class="dropdown__title title">
                                    <span class="title__text">Выход</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>
