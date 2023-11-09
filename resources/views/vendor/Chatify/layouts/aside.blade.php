<aside class="layout__menu menu">
    <div class="menu__list">
        <div class="menu__item">
            <a href="/id{{$user->id}}" class="menu__link">
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
</aside>
