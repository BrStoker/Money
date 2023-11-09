<div class="layout__modal modal">
    {{-- ---------------------- Folder create ---------------------- --}}
    <div class="modal__layout modal__layout_tiny modal__layout_folder-create">
        <!-- modal action -->
        <div class="modal__action action">
            <svg>
                <use xlink:href="/image/svg/sprite.svg#close"></use>
            </svg>
        </div>
        <!-- modal main -->
        <div class="modal__main">
            <div class="wysiwyg">
                <h3>Добавить папку</h3>
            </div>
            <div class="formular">
                <div class="formular__main">
                    <form action="post" class="form">
                        <fieldset>
                            <div class="form__group group">
                                <div class="group__header">
                                    <div class="wysiwyg">
                                        <h6>Название папки</h6>
                                    </div>
                                </div>
                                <div class="group__main">
                                    <div class="row">
                                        <div class="col col_12">
                                            <div class="form-item mb-0">
                                                <div class="form-item__main">
                                                    <div class="form-item__field">
                                                        <input class="form-item__input" id="folder_name" name="folder_name" type="text" placeholder="Название папки">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="wysiwyg">
                <p>
                    <strong>Выберите контакты для добавления</strong>
                </p>

            </div>
            <div id="usersCreate" style="height: 325px; overflow: auto;"></div>


            <div class="form-item">
                <div class="form-item__main">
                    <div class="form-item__field">
                        <button type="submit" class="btn w-100" id="btn_addFolder" disabled="disabled" onclick="createUserFolder(event)">
                            <span class="btn__text">Подтвердить</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ---------------------- profile ---------------------- --}}
    <div class="modal__layout modal__layout_tiny modal__layout_profile" data-contact="0">
        <!-- modal action -->
        <div class="modal__action action">
            <svg>
                <use xlink:href="/image/svg/sprite.svg#close"></use>
            </svg>
        </div>
        <!-- modal main -->
        <div class="modal__main">
            <div class="modal__group">
                <div class="wysiwyg">
                    <h3>О пользователе</h3>
                </div>
                <div class="series align_m-center">
                    <div class="series__group series__group_second">
                        <div class="media mb_m-0" id="avatarBlock">

                        </div>
                    </div>
                    <div class="series__group">
                        <div class="wysiwyg  mb-0" id="username">

                        </div>
                        <div class="tooltips tooltips_second mb-0">
                            <div class="tooltips__list">
                                <div class="tooltips__item">
                                    <div class="tooltips__media">
                                        <svg>
                                            <use xlink:href="/image/svg/sprite.svg#tooltips_01"></use>
                                        </svg>
                                    </div>
                                    <div class="tooltips__title title">
                                        <span class="title__text" id="views"></span>
                                    </div>
                                </div>
                                <div class="tooltips__item">
                                    <div class="tooltips__media">
                                        <svg>
                                            <use xlink:href="/image/svg/sprite.svg#tooltips_05"></use>
                                        </svg>
                                    </div>
                                    <div class="tooltips__title title">
                                        <span class="title__text" id="score"></span>
                                    </div>
                                </div>
                                <div class="tooltips__item">
                                    <div class="tooltips__media">
                                        <svg>
                                            <use xlink:href="/image/svg/sprite.svg#tooltips_06"></use>
                                        </svg>
                                    </div>
                                    <div class="tooltips__title title">
                                        <span class="title__text" id="favorite"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal__group">
                <div class="wysiwyg" id="description"></div>
            </div>
            <div class="modal__group">
                <div class="socials">
                    <div class="socials__list w-100" id="socials"></div>
                </div>
            </div>
            <div class="modal__group" id="checkboxes"></div>
            <div class="modal__group" id="buttons"></div>
        </div>
    </div>
    {{-- ---------------------- delete chat ---------------------- --}}
    <div class="modal__layout modal__layout_tiny modal__layout_delete_chat" data-contact="0">
        <!-- modal action -->
        <div class="modal__action action">
            <svg>
                <use xlink:href="/image/svg/sprite.svg#close"></use>
            </svg>
        </div>
        <!-- modal main -->
        <div class="modal__main">
            <div class="wysiwyg">
                <h3>Удалить чат</h3>
            </div>
            <div class="form-item__field" id="checkbox_text">
                <div class="custom-check">
                    <label class="custom-check__label">
                        <input type="checkbox" class="custom-check__input" name="delete_from">
                        <svg class="custom-check__ico custom-check__ico_before">
                            <use xlink:href="/image/svg/sprite.svg#checkboxBefore"></use>
                        </svg>
                        <svg class="custom-check__ico custom-check__ico_after">
                            <use xlink:href="/image/svg/sprite.svg#checkboxAfter"></use>
                        </svg>
                    </label>
                </div>
                <span class="button__text" style="margin-left: 10px" id="delete_text">Важно</span>
            </div>
            <div class="section__main">
                <div class="buttons" id="buttons">
                    <div class="btn w-100" style="margin: 20px 0; width: auto;" id="delete_chat">
                        <span class="button__text">Подтвердить удаление</span>
                    </div>
                    <div class="btn btn_tertiary" id="closeModal">
                        <span class="button__text">Отменить</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- ---------------------- edit folder ---------------------- --}}
    <div class="modal__layout modal__layout_tiny modal__layout_folder-edit" data-id="0">
        <!-- modal action -->
        <div class="modal__action action">
            <svg>
                <use xlink:href="/image/svg/sprite.svg#close"></use>
            </svg>
        </div>
        <!-- modal main -->
        <div class="modal__main" style="overflow: auto">
            <div class="wysiwyg">
                <h3>Редактировать папку</h3>
            </div>
            <div class="formular">
                <div class="formular__main">
                    <form action="post" class="form" onsubmit="changeFolderName(event)">
                        <fieldset>
                            <div class="form__group group form__group_second">
                                <div class="group__header">
                                    <div class="wysiwyg">
                                        <h6>Название папки</h6>
                                    </div>
                                </div>
                                <div class="group__main">
                                    <div class="row">
                                        <div class="col col_12">
                                            <div class="form-item mb-0">
                                                <div class="form-item__main">
                                                    <div class="form-item__field">
                                                        <input class="form-item__input" name="folder_name" type="text" placeholder="Название папки" id="change_folder_name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form__group group form__group_second">
                                <div class="group__header">
                                    <div class="wysiwyg">
                                        <h6>Номер папки в списке</h6>
                                    </div>
                                </div>
                                <div class="group__main">
                                    <div class="row">
                                        <div class="col col_12">
                                            <div class="form-item mb-0">
                                                <div class="form-item__main">
                                                    <div class="form-item__field">
                                                        <select id="countFolders" name="sort"></select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="wysiwyg">
                <p>
                    <strong>Список контактов в папке</strong>
                </p>
            </div>
            <div id="userList" style="height: 190px; overflow: auto;"></div>

            <div class="form-item">
                <div class="form-item__main">
                    <div class="form-item__field">
                        <button type="submit" class="btn w-100" id="btn_change_folder" onclick="changeFolderName(event)">
                            <span class="btn__text">Подтвердить</span>
                        </button>
                    </div>
                </div>
            </div><!-- //form-item -->
        </div>
    </div>
    {{-- ---------------------- delete folder ---------------------- --}}
    <div class="modal__layout modal__layout_tiny modal__layout_folder-delete" data-id="0">
        <!-- modal action -->
        <div class="modal__action action">
            <svg>
                <use xlink:href="/image/svg/sprite.svg#close"></use>
            </svg>
        </div>
        <!-- modal main -->
        <div class="modal__main">
            <div class="wysiwyg">
                <h3 id="folder_name">Удалить папку «»</h3>
            </div>
            <div class="formular">
                <div class="formular__main">
                    <form action="post" class="form">
                        <fieldset>
                            <div class="form__group group form__group_second" id="users_block">
                                <div class="group__header">
                                    <div class="wysiwyg">
                                        <h6>Выберите, в какую папку перенести контакты</h6>
                                    </div>
                                </div>
                                <div class="group__main">
                                    <div class="row">
                                        <div class="col col_12">
                                            <div class="form-item mb-0">
                                                <div class="form-item__main">
                                                    <div class="form-item__field">
                                                        <select id="user_folders" class="select">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="form-item">
                <div class="form-item__main">
                    <div class="form-item__field">
                        <button type="submit" class="btn w-100" onclick="deleteFolder(event)">
                            <span class="btn__text">Подтвердить удаление</span>
                        </button>
                    </div>
                </div>
            </div><!-- //form-item -->
        </div>
    </div>
    {{-- ---------------------- move user to folder ---------------------- --}}
    <div class="modal__layout modal__layout_tiny modal__layout_move-user-to-folder" data-id="0">
        <!-- modal action -->
        <div class="modal__action action">
            <svg>
                <use xlink:href="/image/svg/sprite.svg#close"></use>
            </svg>
        </div>
        <!-- modal main -->
        <div class="modal__main">
            <div class="wysiwyg">
                <h3 id="folder_name">Перемещение контакта</h3>
            </div>
            <div class="formular">
                <div class="formular__main">
                    <form action="post" class="form" onsubmit="moveUser(event)">
                        <fieldset>
                            <div class="form__group group form__group_second">
                                <div class="group__header">
                                    <div class="wysiwyg">
                                        <h6>Выберите, в какую папку перенести контакт</h6>
                                    </div>
                                </div>
                                <div class="group__main">
                                    <div class="row">
                                        <div class="col col_12">
                                            <div class="form-item mb-0">
                                                <div class="form-item__main">
                                                    <div class="form-item__field">
                                                        <select id="user_folders" class="js-choice">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="form-item">
                <div class="form-item__main">
                    <div class="form-item__field">
                        <button type="submit" class="btn w-100" onclick="moveUser(event)">
                            <span class="btn__text">Перенести</span>
                        </button>
                    </div>
                </div>
            </div><!-- //form-item -->
        </div>
    </div>
    {{-- ---------------------- Complaint folder ---------------------- --}}
    <div class="modal__layout modal__layout_tiny modal__layout_сomplaint-folder" data-contact="0">
        <!-- modal action -->
        <div class="modal__action action">
            <svg>
                <use xlink:href="/image/svg/sprite.svg#close"></use>
            </svg>
        </div>
        <!-- modal main -->
        <div class="modal__main">
            <div class="wysiwyg">
                <h3 id="folder_name">Пожаловаться</h3>
            </div>
            <div class="formular">
                <div class="formular__main">
                    <form action="post" class="form">
                        <fieldset>
                            <div class="form__group group form__group_second">
                                <div class="group__header">
                                    <div class="wysiwyg">
                                        <h6>Выберите причину жалобы</h6>
                                    </div>
                                </div>
                                <div class="group__main">
                                    <div class="row">
                                        <div class="col col_12">
                                            <div class="form-item mb-0">
                                                <div class="form-item__main">
                                                    <div class="form-item__field">
                                                        <select id="reasons">
                                                            <option value="1">Спам</option>
                                                            <option value="2">Угрозы</option>
                                                            <option value="3">Фейковый аккаунт</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col12" style="margin-top: 20px">
                                            <div class="form-item__field" id="checkbox_text">
                                                <div class="custom-check">
                                                    <label class="custom-check__label">
                                                        <input type="checkbox" class="custom-check__input" name="delete_conversation" id="delete_conversation">
                                                        <svg class="custom-check__ico custom-check__ico_before">
                                                            <use xlink:href="/image/svg/sprite.svg#checkboxBefore"></use>
                                                        </svg>
                                                        <svg class="custom-check__ico custom-check__ico_after">
                                                            <use xlink:href="/image/svg/sprite.svg#checkboxAfter"></use>
                                                        </svg>
                                                    </label>
                                                </div>
                                                <span class="button__text" style="margin-left: 10px" id="delete_text">Удалить переписку и заблокировать пользователя</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="form-item">
                <div class="form-item__main">
                    <div class="form-item__field">
                        <button type="submit" class="btn w-100" onclick="blockUser(event)">
                            <span class="btn__text">Пожаловаться</span>
                        </button>
                    </div>
                </div>
            </div><!-- //form-item -->
            <div class="form-item">
                <div class="form-item__main">
                    <div class="form-item__field">
                        <div class="btn btn_tertiary w-100" id="closeModal">
                            <span class="button__text">Отменить</span>
                        </div>
                    </div>
                </div>
            </div><!-- //form-item -->
        </div>
    </div>
    {{-- ---------------------- Forward message ---------------------- --}}
    <div class="modal__layout modal__layout_tiny modal__layout_forward-message" data-msgid="0">
        <!-- modal action -->
        <div class="modal__action action">
            <svg>
                <use xlink:href="/image/svg/sprite.svg#close"></use>
            </svg>
        </div>
        <!-- modal main -->
        <div class="modal__main">
            <div class="wysiwyg">
                <h3 id="folder_name">Выберите получателя</h3>
            </div>
            <div class="formular">
                <div class="formular__main">
                    <form action="post" class="form">
                        <fieldset>
                            <div class="form__group group form__group_second">
                                <div class="group__header">
                                    <div class="form-item__main">
                                        <div class="form-item__field">
                                            <input type="text" id="search" class="form-item__input" placeholder="Поиск" oninput="searchUserInModal(event)"/>
                                        </div>
                                        <div class="form-item__ico">
                                            <svg>
                                                <use xlink:href="/image/svg/sprite.svg#search"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="group__main" style="margin: 1.25rem;">
                                    <div class="row">
                                        <div class="wysiwyg" id="userList" style="height: 335px; overflow: auto;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="form-item">
                <div class="form-item__main">
                    <div class="form-item__field">
                        <button type="submit" class="btn w-100" onclick="forwardMessage(event)">
                            <span class="btn__text">Переслать</span>
                        </button>
                    </div>
                </div>
                <div class="form-item__main">
                    <div class="form-item__field">
                        <div class="btn btn_tertiary w-100" id="closeModal">
                            <span class="button__text">Отменить</span>
                        </div>
                    </div>
                </div>
            </div><!-- //form-item -->
        </div>
    </div>
    {{-- ---------------------- Notification ---------------------- --}}
    <div id="notification" class="notification">
        Это всплывающее уведомление
    </div>
</div>