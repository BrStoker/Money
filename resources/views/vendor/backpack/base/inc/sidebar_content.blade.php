
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menu-item') }}'><i class='nav-icon la la-question'></i> <span>Меню</span></a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('page') }}'><i class='nav-icon la la-question'></i> <span>Страницы</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('complain') }}"><i class="nav-icon la la-question"></i> Жалобы</a></li>
<li class="nav-item"><a class='nav-link' href='{{ backpack_url('users') }}'><i class='nav-icon la la-question'></i> Пользователи</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('article-group') }}"><i class="nav-icon la la-question"></i> Категории статей</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('article') }}"><i class="nav-icon la la-question"></i> Статьи</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('article-comment') }}"><i class="nav-icon la la-question"></i>Комментарии статей</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('courses') }}"><i class="nav-icon la la-question"></i>Курсы</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('courses-comment') }}"><i class="nav-icon la la-question"></i>Коментарии к курсам</a></li>

<li class="nav-item nav-dropdown">
  <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-group"></i> Настройки</a>
  <ul class="nav-dropdown-items">

    <li class="nav-item nav-dropdown">
      <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-group"></i> Пользователи</a>
      <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user-group') }}"><i class="nav-icon la la-question"></i> Группы пользователей</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user-field-group') }}"><i class="nav-icon la la-question"></i> Группы свойств пользователей</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user-field') }}"><i class="nav-icon la la-question"></i> Свойства пользователей</a></li>
      </ul>
    </li>

    <li class="nav-item nav-dropdown">
      <a href="#" class="nav-link nav-dropdown-toggle"><i class="nav-icon la la-group"></i> Курсы</a>
      <ul class="nav-dropdown-items">
        <li class="nav-item"><a href="{{ backpack_url('courses-type') }}" class="nav-link"><i class="nav-icon la la-question"></i> Типы курсов</a></li>
        <li class="nav-item"><a href="{{ backpack_url('courses-subject') }}" class="nav-link"><i class="nav-icon la la-question"></i> Тематика курсов</a></li>
      </ul>

    </li>

    <li class="nav-item nav-dropdown">
      <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-group"></i> Свойства</a>
      <ul class="nav-dropdown-items">
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url('setting-group') }}"><i class="nav-icon la la-question"></i> Группы свойств</a></li>
      <li class='nav-item'><a class='nav-link' href='{{ backpack_url('settings') }}'><i class='nav-icon la-cog'></i> Свойства</a></li>
      </ul>
    </li>
    <li class="nav-item nav-dropdown">
      <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-group"></i> Статьи</a>
      <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('article-field-group') }}"><i class="nav-icon la la-question"></i> Группы полей статей</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('article-field') }}"><i class="nav-icon la la-question"></i> Поля статей</a></li>
      </ul>
    </li>

    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('countries') }}'><i class='nav-icon la la-question'></i> Страны</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('cities') }}'><i class='nav-icon la la-question'></i> Города</a></li>
  </ul>
</li>










