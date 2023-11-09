<div class="dialog__formular">
    <form id="message-form" class="dialog__form form mb-0 send__message" method="POST" action="{{ route('send.message') }}" enctype="multipart/form-data">
        @csrf
        <div class="form__header">
            <div class="form__actions actions">
                <div class="actions__list">
                    <div class="actions__item">
                        <label class="actions__label">
                            <div class="actions__media">
                                <svg>
                                    <use xlink:href="/image/svg/sprite.svg#chatFile"></use>
                                </svg>
                            </div>
                            <div class="actions__input">
                                <input disabled='disabled' type="file" hidden class="upload-attachment" name="file" accept=".{{implode(', .',config('chatify.attachments.allowed_images'))}}, .{{implode(', .',config('chatify.attachments.allowed_files'))}}" />
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form__main">
            <div class="form__content">
                <textarea name="message" class="form__textarea form-item__input form-item__input_textarea" placeholder="Введите сообщение..."></textarea>
            </div>
        </div>
        <div class="form__footer">
            <div class="form__actions actions">
                <div class="actions__list">
                    <div class="actions__item">
                        <div class="actions__media">
                            <svg class="emoji-button send-button"><use xlink:href="/image/svg/sprite.svg#smile"></use></svg>
                        </div>
                    </div>
                    <div class="actions__item">
                        <div class="actions__media">
                            <svg id="record-button" class="send-button record_button">
                                <use xlink:href="/image/svg/sprite.svg#microfon"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="actions__item">
                        <div class="actions__media">
                            <svg class="send-button" onclick="sendMessage()"><use xlink:href="/image/svg/sprite.svg#sendIco"></use></svg>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
