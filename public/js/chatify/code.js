/**
 *-------------------------------------------------------------
 * Global variables
 *-------------------------------------------------------------
 */
// import Form from "./Form";

var messenger,
    typingTimeout,
    typingNow = 0,
    temporaryMsgId = 0,
    defaultAvatarInSettings = null,
    messengerColor,
    dark_mode,
    messages_page = 1,
    recorder,
    isRecording = false,
    isPlaying = false,
    tempChoices,
    modalFilled = false;

const messagesContainer = $(".dialog__messages"),
    messengerTitleDefault = $(".messenger-headTitle").text(),
    messageInputContainer = $(".messenger-sendCard"),
    messageInput = $(".send__message .form-item__input"),
    auth_id = $("meta[name=url]").attr("data-user"),
    url = $("meta[name=url]").attr("content"),
    messengerTheme = $("meta[name=messenger-theme]").attr("content"),
    defaultMessengerColor = $("meta[name=messenger-color]").attr("content"),
    csrfToken = $('meta[name="csrf-token"]').attr("content");
    notification = document.getElementById('notification');

const getMessengerId = () => $("meta[name=id]").attr("content");
const setMessengerId = (id) => $("meta[name=id]").attr("content", id);

/**
 *-------------------------------------------------------------
 * Pusher initialization
 *-------------------------------------------------------------
 */
// Pusher.logToConsole = chatify.pusher.debug;
const pusher = new Pusher(chatify.pusher.key, {
  encrypted: chatify.pusher.options.encrypted,
  cluster: chatify.pusher.options.cluster,
  authEndpoint: chatify.pusherAuthEndpoint,
  auth: {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  },
});
/**
 *-------------------------------------------------------------
 * Re-usable methods
 *-------------------------------------------------------------
 */
const escapeHtml = (unsafe) => {
  return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
};
function actionOnScroll(selector, callback, topScroll = false) {
  $(selector).on("scroll", function () {
    let element = $(this).get(0);
    const condition = topScroll
        ? element.scrollTop == 0
        : element.scrollTop + element.clientHeight >= element.scrollHeight;
    if (condition) {
      callback();
    }
  });
}
function routerPush(title, url) {
  $("meta[name=url]").attr("content", url);
  return window.history.pushState({}, title || document.title, url);
}
function updateSelectedContact(user_id) {
  $(document).find(".messenger-list-item").removeClass("m-list-active");
  $(document)
      .find(
          ".messenger-list-item[data-contact=" + (user_id || getMessengerId()) + "]"
      )
      .addClass("m-list-active");
}
/**
 *-------------------------------------------------------------
 * Global Templates
 *-------------------------------------------------------------
 */
// Loading svg
function loadingSVG(size = "25px", className = "", style = "") {
  return `
<svg style="${style}" class="loadingSVG ${className}" xmlns="http://www.w3.org/2000/svg" width="${size}" height="${size}" viewBox="0 0 40 40" stroke="#ffffff">
<g fill="none" fill-rule="evenodd">
<g transform="translate(2 2)" stroke-width="3">
<circle stroke-opacity=".1" cx="18" cy="18" r="18"></circle>
<path d="M36 18c0-9.94-8.06-18-18-18" transform="rotate(349.311 18 18)">
<animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur=".8s" repeatCount="indefinite"></animateTransform>
</path>
</g>
</g>
</svg>
`;
}
function loadingWithContainer(className) {
  return `<div class="${className}" style="text-align:center;padding:15px">${loadingSVG(
      "25px",
      "",
      "margin:auto"
  )}</div>`;
}

// loading placeholder for users list item
function listItemLoading(items) {
  let template = "";
  for (let i = 0; i < items; i++) {
    template += `
<div class="loadingPlaceholder">
<div class="loadingPlaceholder-wrapper">
<div class="loadingPlaceholder-body">
<table class="loadingPlaceholder-header">
<tr>
<td style="width: 45px;"><div class="loadingPlaceholder-avatar"></div></td>
<td>
<div class="loadingPlaceholder-name"></div>
<div class="loadingPlaceholder-date"></div>
</td>
</tr>
</table>
</div>
</div>
</div>
`;
  }
  return template;
}

// loading placeholder for avatars
function avatarLoading(items) {
  let template = "";
  for (let i = 0; i < items; i++) {
    template += `
<div class="loadingPlaceholder">
<div class="loadingPlaceholder-wrapper">
<div class="loadingPlaceholder-body">
<table class="loadingPlaceholder-header">
<tr>
<td style="width: 45px;">
<div class="loadingPlaceholder-avatar" style="margin: 2px;"></div>
</td>
</tr>
</table>
</div>
</div>
</div>
`;
  }
  return template;
}

// While sending a message, show this temporary message card.
function sendTempMessageCard(message, id) {

  let dateObj = formatDateToObject(new Date());

  return `
 <div class="messages__item messages__item_primary" data-id="${id}">
      <div class="messages__header">
        <div class="messages__status status">
          <div class="status__media">

          </div>
          <div class="status__title title">
          </div>
          <span class="title__text">${dateObj.date}</span>
        </div>
      </div>

      <div class="messages__status status">

      </div>
      <div class="messages__main">
        <div class="wysiwyg">
          <p>${message}
          </p>
        </div>
      </div>
      <div class="messages__footer">
        <div class="messages__date date">
          <span class="date__text">${dateObj.time}</span>
        </div>
      </div>
    </div>
`;
}
// upload image preview card.
function attachmentTemplate(fileType, fileName, imgURL = null) {
  if (fileType != "image") {
    return (
        `<div class="dialog__preview preview">
            <div class="preview__media">
                <div class="preview__media">
                    <svg>
                        <use xlink:href="/image/svg/sprite.svg#attach"></use>
                    </svg>
                </div>
            </div>
            <div class="preview__title title">
                <span class="title__text">` + fileName + `</span>
            </div>
            <div class="preview__action">
                <svg onclick="removeReply()">
                    <use xlink:href="img/sprite.svg#close"></use>
                </svg>
            </div>
        </div>`
    );
  } else {
    return (
        `<div class="dialog__preview preview">
            <div class="preview__media">
                <img data-src="` + imgURL + `"
                     src="` + imgURL + `"
                     alt="image description"/>
            </div>
            <div class="preview__title title">
                <span class="title__text">` + fileName + `</span>
            </div>
            <div class="preview__action">
                <svg onclick="removeReply()">
                    <use xlink:href="/image/svg/sprite.svg#close"></use>
                </svg>
            </div>
        </div>
`
    );
  }
}

// Active Status Circle
function activeStatusCircle() {
  return `<span class="activeStatus"></span>`;
}

/**
 *-------------------------------------------------------------
 * Css Media Queries [For responsive design]
 *-------------------------------------------------------------
 */
$(window).resize(function () {
  cssMediaQueries();
});
function cssMediaQueries() {
  if (window.matchMedia("(min-width: 980px)").matches) {
    $(".messenger-listView").removeAttr("style");
  }
  if (window.matchMedia("(max-width: 980px)").matches) {
    $("body")
        .find(".messenger-list-item")
        .find("tr[data-action]")
        .attr("data-action", "1");
    $("body").find(".favorite-list-item").find("div").attr("data-action", "1");
  } else {
    $("body")
        .find(".messenger-list-item")
        .find("tr[data-action]")
        .attr("data-action", "0");
    $("body").find(".favorite-list-item").find("div").attr("data-action", "0");
  }
}

/**
 *-------------------------------------------------------------
 * App Modal
 *-------------------------------------------------------------
 */
let app_modal = function ({
                            show = true,
                            name,
                            data = 0,
                            buttons = true,
                            header = null,
                            body = null,
                          }) {
  const modal = $(".app-modal[data-name=" + name + "]");
  // header
  header ? modal.find(".app-modal-header").html(header) : "";

  // body
  body ? modal.find(".app-modal-body").html(body) : "";

  // buttons
  buttons == true
      ? modal.find(".app-modal-footer").show()
      : modal.find(".app-modal-footer").hide();

  // show / hide
  if (show == true) {
    modal.show();
    $(".app-modal-card[data-name=" + name + "]").addClass("app-show-modal");
    $(".app-modal-card[data-name=" + name + "]").attr("data-modal", data);
  } else {
    modal.hide();
    $(".app-modal-card[data-name=" + name + "]").removeClass("app-show-modal");
    $(".app-modal-card[data-name=" + name + "]").attr("data-modal", data);
  }
};

/**
 *-------------------------------------------------------------
 * Slide to bottom on [action] - e.g. [message received, sent, loaded]
 *-------------------------------------------------------------
 */
function scrollToBottom(container) {
  $(container)
      .stop()
      .animate({
        scrollTop: $(container)[0].scrollHeight,
      });
}

/**
 *-------------------------------------------------------------
 * click and drag to scroll - function
 *-------------------------------------------------------------
 */
// function hScroller(scroller) {
//   const slider = document.querySelector(scroller);
//   let isDown = false;
//   let startX;
//   let scrollLeft;
//
//   slider.addEventListener("mousedown", (e) => {
//     isDown = true;
//     startX = e.pageX - slider.offsetLeft;
//     scrollLeft = slider.scrollLeft;
//   });
//   slider.addEventListener("mouseleave", () => {
//     isDown = false;
//   });
//   slider.addEventListener("mouseup", () => {
//     isDown = false;
//   });
//   slider.addEventListener("mousemove", (e) => {
//     if (!isDown) return;
//     e.preventDefault();
//     const x = e.pageX - slider.offsetLeft;
//     const walk = (x - startX) * 1;
//     slider.scrollLeft = scrollLeft - walk;
//   });
// }

/**
 *-------------------------------------------------------------
 * Disable/enable message form fields, messaging container...
 * on load info or if needed elsewhere.
 *
 * Default : true
 *-------------------------------------------------------------
 */
function disableOnLoad(disable = true) {
  if (disable) {
    // hide star button
    $(".add-to-favorite").hide();
    // hide send card
    $(".messenger-sendCard").hide();
    // add loading opacity to messages container
    messagesContainer.css("opacity", ".5");
    // disable message form fields
    messageInput.attr("readonly", "readonly");
    $(".send__message button").attr("disabled", "disabled");
    $(".upload-attachment").attr("disabled", "disabled");
  } else {
    // show star button
    if (getMessengerId() != auth_id) {
      $(".add-to-favorite").show();
    }
    // show send card
    $(".messenger-sendCard").show();
    // remove loading opacity to messages container
    messagesContainer.css("opacity", "1");
    // enable message form fields
    messageInput.removeAttr("readonly");
    $(".send__message button").removeAttr("disabled");
    $(".upload-attachment").removeAttr("disabled");
  }
}

/**
 *-------------------------------------------------------------
 * Error message card
 *-------------------------------------------------------------
 */
function errorMessageCard(id, error) {

  // tempID
  console.log(error)

  messagesContainer
      .find(".messages__item[data-id=" + id + "]")
      .addClass("mc-error");
  messagesContainer
      .find(".messages__item[data-id=" + id + "]")
      .find("svg.loadingSVG")
      .remove();
  messagesContainer
      .find(".messages__item[data-id=" + id + "] p")
      .prepend('<span class="fas fa-exclamation-triangle"></span>');
}

/**
 *-------------------------------------------------------------
 * Fetch id data (user/group) and update the view
 *-------------------------------------------------------------
 */
function IDinfo(id) {
  // clear temporary message id
  temporaryMsgId = 0;
  // clear typing now
  typingNow = 0;
  // show loading bar
  NProgress.start();
  // disable message form
  disableOnLoad();
  if (messenger != 0) {
    // get shared photos
    getSharedPhotos(id);
    // Get info
    $.ajax({
      url: url + "/idInfo",
      method: "POST",
      data: { _token: csrfToken, id },
      dataType: "JSON",
      success: (data) => {
        if (!data?.fetch) {
          NProgress.done();
          NProgress.remove();
          return;
        }
        // avatar photo
        $(".messenger-infoView")
            .find(".avatar")
            .css("background-image", 'url("' + data.user_avatar + '")');
        $(".header-avatar").css(
            "background-image",
            'url("' + data.user_avatar + '")'
        );
        // Show shared and actions
        $(".messenger-infoView-btns .delete-conversation").show();
        $(".messenger-infoView-shared").show();
        // fetch messages
        // fetchMessages(id, true);
        // focus on messaging input
        messageInput.focus();
        // update info in view
        $(".messenger-infoView .info-name").text(data.fetch.name);
        $(".m-header-messaging .user-name").text(data.fetch.name);
        // Star status
        data.favorite > 0
            ? $(".add-to-favorite").addClass("favorite")
            : $(".add-to-favorite").removeClass("favorite");
        // form reset and focus
        $(".send__message").trigger("reset");
        cancelAttachment();
        messageInput.focus();
      },
      error: () => {
        console.error("Couldn't fetch user data!");
        // remove loading bar
        NProgress.done();
        NProgress.remove();
      },
    });
  } else {
    // remove loading bar
    NProgress.done();
    NProgress.remove();
  }
}

/**
 *-------------------------------------------------------------
 * Send message function
 *-------------------------------------------------------------
 */
function sendMessage() {
  temporaryMsgId += 1;

  let tabs = document.querySelector('#tabs');
  let tab = tabs.querySelector('.tabs__item_active')
  let form = tab.querySelector(".send__message");
  let container = tab.querySelector(".dialog__messages")
  let footer = tab.querySelector('.dialog__footer')
  let hasReply = !!footer.querySelector('#replyMessage')

  let tempID = `temp_${temporaryMsgId}`;
  let hasFile = !!form.querySelector(".upload-attachment").value;

  // let hasReply = !!container.querySelector("#replyMessage")

  const inputValue = form.querySelector('.form-item__input').value//$.trim(messageInput.val());

  if (inputValue.length > 0 || hasFile) {
    const formData = new FormData(form);
    if(hasReply){
      let replyMessage = footer.querySelector('#replyMessage')
      if(replyMessage && replyMessage.hasAttribute('data-message_id')){
        let messageId = replyMessage.dataset.message_id
        formData.append('reply_id', messageId)
      }
    }
    formData.append("id", getMessengerId());
    formData.append("temporaryMsgId", tempID);
    formData.append("_token", csrfToken);
    $.ajax({
      url: $(".send__message").attr("action"),
      method: "POST",
      data: formData,
      dataType: "json",
      processData: false,
      contentType: false,
      beforeSend: () => {
        if (hasFile) {

          let tempMess = sendTempMessageCard(inputValue + "\n" + loadingSVG("28px"), tempID)
          container.innerHTML += tempMess

        } else {


          let tempMess = sendTempMessageCard(inputValue, tempID)
          container.innerHTML += tempMess
        }
        // scroll to bottom
        scrollToBottom(container);
        // messageInput.css({ height: "42px" });
        // form reset and focus
        $(tab).find(".send__message").trigger("reset");
        cancelAttachment();
        messageInput.focus();
      },
      success: (data) => {
        if (data.error > 0) {
          // message card error status
          errorMessageCard(tempID, data.error);

        } else {

          // update contact item
          // updateContactItem(getMessengerId());
          // temporary message card
          const tempMsgCardElement = $(container).find(
              `.messages__item[data-id=${data.tempID}]`
          );
          // add the message card coming from the server before the temp-card
          tempMsgCardElement.before(data.message);
          // then, remove the temporary message card
          tempMsgCardElement.remove();
          removeReply()

          // let tabs = document.querySelector('#tabs');
          // let tab = tabs.querySelector('.tabs__item_active')
          let tabId = tab.dataset.tabid
          refreshTab(tabId, getMessengerId())

          scrollToBottom(container)

          // send contact item updates
          sendContactItemUpdates(true);
        }
      },
      error: () => {
        // message card error status
        errorMessageCard(tempID);
        // error log
        console.error(
            "Failed sending the message! Please, check your server response."
        );
      },
    });
  }
  return false;
}

/**
 *-------------------------------------------------------------
 * Fetch messages from database
 *-------------------------------------------------------------
 */
let messagesPage = 1;
let noMoreMessages = false;
let messagesLoading = false;
function setMessagesLoading(loading = false) {
  if (!loading) {
    messagesContainer.find(".messages").find(".loading-messages").remove();
    NProgress.done();
    NProgress.remove();
  } else {
    messagesContainer
        .find(".messages__item")
        .prepend(loadingWithContainer("loading-messages"));
  }
  messagesLoading = loading;
}
function fetchMessages(id, newFetch = false) {
  if (newFetch) {
    messagesPage = 1;
    noMoreMessages = false;
  }
  if (messenger != 0 && !noMoreMessages && !messagesLoading) {
    const messagesElement = messagesContainer.find(".messages__item");
    setMessagesLoading(true);
    $.ajax({
      url: url + "/fetchMessages",
      method: "POST",
      data: {
        _token: csrfToken,
        id: id,
        page: messagesPage,
      },
      dataType: "JSON",
      success: (data) => {
        setMessagesLoading(false);
        if (messagesPage == 1) {
          messagesElement.html(data.messages);
          scrollToBottom(messagesContainer);
        } else {
          const lastMsg = messagesElement.find(
              messagesElement.find(".message-card")[0]
          );
          const curOffset =
              lastMsg.offset().top - messagesContainer.scrollTop();
          messagesElement.prepend(data.messages);
          messagesContainer.scrollTop(lastMsg.offset().top - curOffset);
        }
        // trigger seen event
        // makeSeen(true);
        // Pagination lock & messages page
        noMoreMessages = messagesPage >= data?.last_page;
        if (!noMoreMessages) messagesPage += 1;
        // Enable message form if messenger not = 0; means if data is valid
        if (messenger != 0) {
          disableOnLoad(false);
        }
      },
      error: (error) => {
        setMessagesLoading(false);
        console.error(error);
      },
    });
  }
}

/**
 *-------------------------------------------------------------
 * Cancel file attached in the message.
 *-------------------------------------------------------------
 */
function cancelAttachment() {
  $(".messenger-sendCard").find(".attachment-preview").val("");
  // $(".upload-attachment").replaceWith(
  //   $(".upload-attachment").val("").clone(true)
  // );
}

/**
 *-------------------------------------------------------------
 * Cancel updating avatar in settings
 *-------------------------------------------------------------
 */
function cancelUpdatingAvatar() {
  $(".upload-avatar-preview").css("background-image", defaultAvatarInSettings);
  $(".upload-avatar").replaceWith($(".upload-avatar").val("").clone(true));
}

/**
 *-------------------------------------------------------------
 * Pusher channels and event listening..
 *-------------------------------------------------------------
 */

// subscribe to the channel
const channelName = "private-chatify";
var channel = pusher.subscribe(`${channelName}.${auth_id}`);
var clientSendChannel;
var clientListenChannel;

function initClientChannel() {
  if (getMessengerId()) {
    clientSendChannel = pusher.subscribe(`${channelName}.${getMessengerId()}`);
    clientListenChannel = pusher.subscribe(`${channelName}.${auth_id}`);
  }
}
initClientChannel();

// Listen to messages, and append if data received
channel.bind("messaging", function (data) {
  if (data.from_id == getMessengerId() && data.to_id == auth_id) {
    $(".messages").find(".message-hint").remove();
    messagesContainer.find(".messages").append(data.message);
    scrollToBottom(messagesContainer);
    // makeSeen(true);
    // remove unseen counter for the user from the contacts list
    $(".messenger-list-item[data-contact=" + getMessengerId() + "]")
        .find("tr>td>b")
        .remove();
  }

  playNotificationSound(
      "new_message",
      !(data.from_id == getMessengerId() && data.to_id == auth_id)
  );
});

// listen to typing indicator
clientListenChannel.bind("client-typing", function (data) {
  if (data.from_id == getMessengerId() && data.to_id == auth_id) {
    data.typing == true
        ? messagesContainer.find(".typing-indicator").show()
        : messagesContainer.find(".typing-indicator").hide();
  }
  // scroll to bottom
  scrollToBottom(messagesContainer);
});

// listen to seen event
clientListenChannel.bind("client-seen", function (data) {
  if (data.from_id == getMessengerId() && data.to_id == auth_id) {
    if (data.seen == true) {
      $(".message-time")
          .find(".fa-check")
          .before('<span class="fas fa-check-double seen"></span> ');
      $(".message-time").find(".fa-check").remove();
    }
  }
});

// listen to contact item updates event
clientListenChannel.bind("client-contactItem", function (data) {
  if (data.to == auth_id) {
    if (data.update) {
      updateContactItem(data.from);
    } else {
      console.error("Can not update contact item!");
    }
  }
});

// listen on message delete event
clientListenChannel.bind("client-messageDelete", function (data) {
  $("body").find(`.message-card[data-id=${data.id}]`).remove();
});
// listen on delete conversation event
clientListenChannel.bind("client-deleteConversation", function (data) {
  if (data.from == getMessengerId() && data.to == auth_id) {
    $("body").find(`.messages`).html("");
    $(".messages").find(".message-hint").show();
  }
});
// -------------------------------------
// presence channel [User Active Status]
var activeStatusChannel = pusher.subscribe("presence-activeStatus");

// Joined
activeStatusChannel.bind("pusher:member_added", function (member) {
  setActiveStatus(1);
  $(".messenger-list-item[data-contact=" + member.id + "]")
      .find(".activeStatus")
      .remove();
  $(".messenger-list-item[data-contact=" + member.id + "]")
      .find(".avatar")
      .before(activeStatusCircle());
});

// Leaved
activeStatusChannel.bind("pusher:member_removed", function (member) {
  setActiveStatus(0);
  $(".messenger-list-item[data-contact=" + member.id + "]")
      .find(".activeStatus")
      .remove();
});

function handleVisibilityChange() {
  if (!document.hidden) {
    // makeSeen(true);
  }
}

document.addEventListener("visibilitychange", handleVisibilityChange, false);

/**
 *-------------------------------------------------------------
 * Trigger typing event
 *-------------------------------------------------------------
 */
function isTyping(status) {
  return clientSendChannel.trigger("client-typing", {
    from_id: auth_id, // Me
    to_id: getMessengerId(), // Messenger
    typing: status,
  });
}

/**
 *-------------------------------------------------------------
 * Trigger seen event
 *-------------------------------------------------------------
 */
function makeSeen(status) {
  // if (document?.hidden) {
  //   return;
  // }


  // remove unseen counter for the user from the contacts list
  // $(".messenger-list-item[data-contact=" + getMessengerId() + "]")
  //   .find("tr>td>b")
  //   .remove();
  // // seen
  // $.ajax({
  //   url: url + "/makeSeen",
  //   method: "POST",
  //   data: { _token: csrfToken, id: getMessengerId() },
  //   dataType: "JSON",
  // });
  // return clientSendChannel.trigger("client-seen", {
  //   from_id: auth_id, // Me
  //   to_id: getMessengerId(), // Messenger
  //   seen: status,
  // });
}

/**
 *-------------------------------------------------------------
 * Trigger contact item updates
 *-------------------------------------------------------------
 */
function sendContactItemUpdates(status) {
  return clientSendChannel.trigger("client-contactItem", {
    from: auth_id, // Me
    to: getMessengerId(), // Messenger
    update: status,
  });
}

/**
 *-------------------------------------------------------------
 * Trigger message delete
 *-------------------------------------------------------------
 */
function sendMessageDeleteEvent(messageId) {
  return clientSendChannel.trigger("client-messageDelete", {
    id: messageId,
  });
}
/**
 *-------------------------------------------------------------
 * Trigger delete conversation
 *-------------------------------------------------------------
 */
function sendDeleteConversationEvent() {
  return clientSendChannel.trigger("client-deleteConversation", {
    from: auth_id,
    to: getMessengerId(),
  });
}

/**
 *-------------------------------------------------------------
 * Check internet connection using pusher states
 *-------------------------------------------------------------
 */
function checkInternet(state, selector) {
  let net_errs = 0;
  const messengerTitle = $(".messenger-headTitle");
  switch (state) {
    case "connected":
      if (net_errs < 1) {
        messengerTitle.text(messengerTitleDefault);
        selector.addClass("successBG-rgba");
        selector.find("span").hide();
        selector.slideDown("fast", function () {
          selector.find(".ic-connected").show();
        });
        setTimeout(function () {
          $(".internet-connection").slideUp("fast");
        }, 3000);
      }
      break;
    case "connecting":
      messengerTitle.text($(".ic-connecting").text());
      selector.removeClass("successBG-rgba");
      selector.find("span").hide();
      selector.slideDown("fast", function () {
        selector.find(".ic-connecting").show();
      });
      net_errs = 1;
      break;
      // Not connected
    default:
      messengerTitle.text($(".ic-noInternet").text());
      selector.removeClass("successBG-rgba");
      selector.find("span").hide();
      selector.slideDown("fast", function () {
        selector.find(".ic-noInternet").show();
      });
      net_errs = 1;
      break;
  }
}

/**
 *-------------------------------------------------------------
 * Get contacts
 *-------------------------------------------------------------
 */
let contactsPage = 1;
let contactsLoading = false;
let noMoreContacts = false;
function setContactsLoading(loading = false) {
  if (!loading) {
    $(".listOfContacts").find(".loading-contacts").remove();
  } else {
    $(".listOfContacts").append(
        `<div class="loading-contacts">${listItemLoading(4)}</div>`
    );
  }
  contactsLoading = loading;
}
function getContacts() {
  if (!contactsLoading && !noMoreContacts) {
    setContactsLoading(true);
    $.ajax({
      url: url + "/getContacts",
      method: "GET",
      data: { _token: csrfToken, page: contactsPage },
      dataType: "JSON",
      success: (data) => {
        setContactsLoading(false);
        if (contactsPage < 2) {
          $(".listOfContacts").html(data.contacts);
        } else {
          $(".listOfContacts").append(data.contacts);
        }
        updateSelectedContact();
        // update data-action required with [responsive design]
        cssMediaQueries();
        // Pagination lock & messages page
        noMoreContacts = contactsPage >= data?.last_page;
        if (!noMoreContacts) contactsPage += 1;
      },
      error: (error) => {
        setContactsLoading(false);
        console.error(error);
      },
    });
  }
}

/**
 *-------------------------------------------------------------
 * Update contact item
 *-------------------------------------------------------------
 */
function updateContactItem(user_id) {
  if (user_id != auth_id) {
    let tabs = document.querySelectorAll('.tabs__item_active')

    tabs.forEach(tab => {

      if(tab.hasAttribute('data-tabid')){
        folder_id = tab.dataset.tabid
      }

    })



    $.ajax({
      url: "/updateContacts",
      method: "POST",
      data: {
        _token: csrfToken,
        user_id,
        folder_id
      },
      dataType: "JSON",
      success: (data) => {

        if(data.status == true){

          let userMenu = document.querySelector('.layout__menu.menu')
          userMenu.innerHTML = ''
          userMenu.innerHTML = data.userMenu

          let userFoldersContainer = document.querySelector('.tabs__list_second')
          userFoldersContainer.innerHTML = '';
          userFoldersContainer.innerHTML = data.folders

          let userTabs = document.querySelector('#tabs')
          userTabs.innerHTML = ''
          userTabs.innerHTML = data.tabs
          var activeUser = userTabs.querySelector('.preview__item_active')
          if(activeUser){
            activeUser.click()
          }

        }
      },
      error: (error) => {
        console.error(error);
      },
    });
  }
}

/**
 *-------------------------------------------------------------
 * Star
 *-------------------------------------------------------------
 */

function star(user_id) {
  if (getMessengerId() != auth_id) {
    $.ajax({
      url: url + "/star",
      method: "POST",
      data: { _token: csrfToken, user_id: user_id },
      dataType: "JSON",
      success: (data) => {
        data.status > 0
            ? $(".add-to-favorite").addClass("favorite")
            : $(".add-to-favorite").removeClass("favorite");
      },
      error: () => {
        console.error("Server error, check your response");
      },
    });
  }
}

/**
 *-------------------------------------------------------------
 * Get favorite list
 *-------------------------------------------------------------
 */
function getFavoritesList() {
  $(".messenger-favorites").html(avatarLoading(4));
  $.ajax({
    url: url + "/favorites",
    method: "POST",
    data: { _token: csrfToken },
    dataType: "JSON",
    success: (data) => {
      if (data.count > 0) {
        $(".favorites-section").show();
        $(".messenger-favorites").html(data.favorites);
      } else {
        $(".favorites-section").hide();
      }
      // update data-action required with [responsive design]
      cssMediaQueries();
    },
    error: () => {
      console.error("Server error, check your response");
    },
  });
}

/**
 *-------------------------------------------------------------
 * Get shared photos
 *-------------------------------------------------------------
 */
function getSharedPhotos(user_id) {
  $.ajax({
    url: url + "/shared",
    method: "POST",
    data: { _token: csrfToken, user_id: user_id },
    dataType: "JSON",
    success: (data) => {
      $(".shared-photos-list").html(data.shared);
    },
    error: () => {
      console.error("Server error, check your response");
    },
  });
}

/**
 *-------------------------------------------------------------
 * Search in messenger
 *-------------------------------------------------------------
 */
let searchPage = 1;
let noMoreDataSearch = false;
let searchLoading = false;
let searchTempVal = "";
function setSearchLoading(loading = false) {
  if (!loading) {
    $(".search-records").find(".loading-search").remove();
  } else {
    $(".search-records").append(
        `<div class="loading-search">${listItemLoading(4)}</div>`
    );
  }
  searchLoading = loading;
}
function messengerSearch(input) {

  if (input != searchTempVal) {
    searchPage = 1;
    noMoreDataSearch = false;
    searchLoading = false;
  }
  searchTempVal = input;
  if (!searchLoading && !noMoreDataSearch) {
    if (searchPage < 2) {
      $(".search-records").html("");
    }
    setSearchLoading(true);
    $.ajax({
      url: url + "/search",
      method: "GET",
      data: { _token: csrfToken, input: input, page: searchPage, userId: getMessengerId() },
      dataType: "JSON",
      success: (data) => {

        setSearchLoading(false);
        if (searchPage < 2) {
          $(".preview__list").html(data.records);
        } else {
          $(".preview__list").append(data.records);
        }
        // update data-action required with [responsive design]
        cssMediaQueries();
        // Pagination lock & messages page
        noMoreDataSearch = searchPage >= data?.last_page;
        if (!noMoreDataSearch) searchPage += 1;
      },
      error: (error) => {
        setSearchLoading(false);
        console.error(error);
      },
    });
  }
}

function searchUser(input){
  if (input != searchTempVal) {
    searchPage = 1;
    noMoreDataSearch = false;
    searchLoading = false;
  }
  searchTempVal = input;
  if (!searchLoading && !noMoreDataSearch){
    if (searchPage < 2) {
      $(".search-records").html("");
    }
    setSearchLoading(true);
    $.ajax({
      url: "/search-user",
      method: "POST",
      data: { _token: csrfToken, input: input, page: searchPage },
      dataType: "JSON",
      success: (data) => {

        setSearchLoading(false);
        if (searchPage < 2) {
          $(".preview__list").html(data.records);
        } else {
          $(".preview__list").append(data.records);
        }
        // update data-action required with [responsive design]
        cssMediaQueries();
        // Pagination lock & messages page
        noMoreDataSearch = searchPage >= data?.last_page;
        if (!noMoreDataSearch) searchPage += 1;
      },
      error: (error) => {
        setSearchLoading(false);
        console.error(error);
      },
    });
  }
}

const searchInputs = document.querySelectorAll('.search')

searchInputs.forEach(input => {
  input.addEventListener('input', (event) => {
    const inputValue = event.target.value;
    filterUsers(event.target)
  });
})

function filterUsers(input){

  let searchingUser = true;
  let searchTerm = input.value
  let tab = input.closest('.tabs__item_active')
  let searchResults = tab.querySelectorAll('.chats__dialog')
  let tabId = tab.dataset.tabid
  console.log(input.id)
  if(input.id == 'searchCorrespondence'){
    searchingUser = false
  }

  if(searchingUser){
    if(searchTerm.length > 0){
      searchUser(searchTerm)
    }else{
      reloadUsers(tabId)
    }
  }else{
    if(searchTerm.length > 0){
      messengerSearch(searchTerm)
    }else{
      reloadUsers(tabId)
    }
  }

}



// searchInputs.addEventListener('input', filterUsers)



function reloadUsers(folderId){

  let activeUser = 0

  if(getMessengerId() != 0){
    let currentUser = getMessengerId()
  }else{

  }
  $.ajax({
    url: "/reload-users",
    method: "POST",
    data: { _token: csrfToken, folderId: folderId, user_id: activeUser},
    dataType: "JSON",
    success: (data) => {

      let tabs = document.querySelectorAll(`.tabs__item_active[data-tabid="${folderId}"]`)

      tabs.forEach(tab => {

        let userList = tab.querySelector('.preview__list');

        if(userList){
          userList.innerHTML = '';
          userList.innerHTML = data.contacts;
        }



      })



    },
    error: (error) => {}

  })



}



/**
 *-------------------------------------------------------------
 * Delete Conversation
 *-------------------------------------------------------------
 */
function deleteConversation(id) {
  $.ajax({
    url: url + "/deleteConversation",
    method: "POST",
    data: { _token: csrfToken, id: id },
    dataType: "JSON",
    beforeSend: () => {
      // hide delete modal
      app_modal({
        show: false,
        name: "delete",
      });
      // Show waiting alert modal
      app_modal({
        show: true,
        name: "alert",
        buttons: false,
        body: loadingSVG("32px", null, "margin:auto"),
      });
    },
    success: (data) => {
      // delete contact from the list
      $(".listOfContacts")
          .find(".messenger-list-item[data-contact=" + id + "]")
          .remove();
      // refresh info
      // IDinfo(id);

      if (!data.deleted)
        return alert("Error occurred, messages can not be deleted!");

      // Hide waiting alert modal
      app_modal({
        show: false,
        name: "alert",
        buttons: true,
        body: "",
      });

      sendDeleteConversationEvent();

      // update contact list item
      sendContactItemUpdates(true);
    },
    error: () => {
      console.error("Server error, check your response");
    },
  });
}

/**
 *-------------------------------------------------------------
 * Delete Message By ID
 *-------------------------------------------------------------
 */
// function deleteMessage(id) {
//   $.ajax({
//     url: url + "/deleteMessage",
//     method: "POST",
//     data: { _token: csrfToken, id: id },
//     dataType: "JSON",
//     beforeSend: () => {
//       // hide delete modal
//       app_modal({
//         show: false,
//         name: "delete",
//       });
//       // Show waiting alert modal
//       app_modal({
//         show: true,
//         name: "alert",
//         buttons: false,
//         body: loadingSVG("32px", null, "margin:auto"),
//       });
//     },
//     success: (data) => {
//       $(".messages").find(`.message-card[data-id=${id}]`).remove();
//       if (!data.deleted)
//         console.error("Error occurred, message can not be deleted!");
//
//       sendMessageDeleteEvent(id);
//
//       // Hide waiting alert modal
//       app_modal({
//         show: false,
//         name: "alert",
//         buttons: true,
//         body: "",
//       });
//     },
//     error: () => {
//       console.error("Server error, check your response");
//     },
//   });
// }

/**
 *-------------------------------------------------------------
 * Update Settings
 *-------------------------------------------------------------
 */
function updateSettings() {
  const formData = new FormData($("#update-settings")[0]);
  if (messengerColor) {
    formData.append("messengerColor", messengerColor);
  }
  if (dark_mode) {
    formData.append("dark_mode", dark_mode);
  }
  $.ajax({
    url: url + "/updateSettings",
    method: "POST",
    data: formData,
    dataType: "JSON",
    processData: false,
    contentType: false,
    beforeSend: () => {
      // close settings modal
      app_modal({
        show: false,
        name: "settings",
      });
      // Show waiting alert modal
      app_modal({
        show: true,
        name: "alert",
        buttons: false,
        body: loadingSVG("32px", null, "margin:auto"),
      });
    },
    success: (data) => {
      if (data.error) {
        // Show error message in alert modal
        app_modal({
          show: true,
          name: "alert",
          buttons: true,
          body: data.msg,
        });
      } else {
        // Hide alert modal
        app_modal({
          show: false,
          name: "alert",
          buttons: true,
          body: "",
        });

        // reload the page
        location.reload(true);
      }
    },
    error: () => {
      console.error("Server error, check your response");
    },
  });
}

/**
 *-------------------------------------------------------------
 * Set Active status
 *-------------------------------------------------------------
 */
function setActiveStatus(status) {
  $.ajax({
    url: url + "/setActiveStatus",
    method: "POST",
    data: { _token: csrfToken, status: status },
    dataType: "JSON",
    success: (data) => {
      // Nothing to do
    },
    error: () => {
      console.error("Server error, check your response");
    },
  });
}

/**
 *-------------------------------------------------------------
 * On DOM ready
 *-------------------------------------------------------------
 */
$(document).ready(function () {
  // get contacts list
  getContacts();

  // get contacts list
  getFavoritesList();

  // Clear typing timeout
  clearTimeout(typingTimeout);

  // NProgress configurations
  NProgress.configure({ showSpinner: false, minimum: 0.7, speed: 500 });

  // make message input autosize.
  autosize($(".m-send"));

  // check if pusher has access to the channel [Internet status]
  pusher.connection.bind("state_change", function (states) {
    let selector = $(".internet-connection");
    checkInternet(states.current, selector);
    // listening for pusher:subscription_succeeded
    channel.bind("pusher:subscription_succeeded", function () {
      // On connection state change [Updating] and get [info & msgs]
      if (getMessengerId() != 0) {
        if (
            $(".messenger-list-item")
                .find("tr[data-action]")
                .attr("data-action") == "1"
        ) {
          $(".messenger-listView").hide();
        }
        // IDinfo(getMessengerId());
      }
    });
  });

  // tabs on click, show/hide...
  $(".messenger-listView-tabs a").on("click", function () {
    var dataView = $(this).attr("data-view");
    $(".messenger-listView-tabs a").removeClass("active-tab");
    $(this).addClass("active-tab");
    $(".messenger-tab").hide();
    $(".messenger-tab[data-view=" + dataView + "]").show();
  });

  // set item active on click
  $("body").on("click", ".messenger-list-item", function () {
    $(".messenger-list-item").removeClass("m-list-active");
    $(this).addClass("m-list-active");
    const userID = $(this).attr("data-contact");
    routerPush(document.title, `${url}/${userID}`);
    updateSelectedContact(userID);
  });

  // show info side button
  $(".messenger-infoView nav a , .show-infoSide").on("click", function () {
    $(".messenger-infoView").toggle();
  });

  // make favorites card dragable on click to slide.
  // hScroller(".messenger-favorites");

  // click action for list item [user/group]
  $("body").on("click", ".preview__item", function () {
    if ($(this).find("tr[data-action]").attr("data-action") == "1") {
      $(".messenger-listView").hide();
    }
    if(this.hasAttribute('data-contact')){
      let parentItem = this.closest('.preview__list');

      let items = parentItem.querySelectorAll('.preview__item')
      items.forEach((item)=>{
        item.classList.remove('preview__item_active')
      })
      // console.log(this)
      this.classList.add('preview__item_active')
      const dataId = $(this).attr("data-contact");
      let parent = this.closest('.tabs__item')
      parent.classList.remove('hide')
      modalHide()
      updateMessageCard(dataId, parent);
      setMessengerId(dataId);
      let tabs = document.querySelector('#tabs');
      // console.log(tabs)
      let tab = tabs.querySelector('.tabs__item_active')
      let tabId = tab.dataset.tabid
      readMessages(tabId)
      disableOnLoad(false)
    }else if(this.hasAttribute('data-msgid')){
      let currentId = getMessengerId()
      if(currentId){
        let currentTab = document.querySelector(`.chats__dialog[data-id="${currentId}"]`)
        let message = currentTab.querySelector(`[data-messageid="${this.dataset.msgid}"]`)
        if(message){
          message.scrollIntoView({ behavior: 'smooth'})
        }
      }
    }

      // let chatDialogs = document.querySelectorAll('.section__subsection')
      // chatDialogs.forEach(dialog => {
      //   if(dialog.classList.contains('hide') == false){
      //     let message = dialog.querySelector(`[data-messageid="${this.dataset.msgid}"]`)
      //     if(message){
      //       message.scrollIntoView({ behavior: 'smooth', block: 'center' })
      //     }
      //   }
      // })
    // }

  });

  // click action for favorite button
  $("body").on("click", ".favorite-list-item", function () {
    if ($(this).find("div").attr("data-action") == "1") {
      $(".messenger-listView").hide();
    }
    const uid = $(this).find("div.avatar").attr("data-id");
    setMessengerId(uid);
    IDinfo(uid);
    updateSelectedContact(uid);
    routerPush(document.title, `${url}/${uid}`);
  });

  // list view buttons
  $(".listView-x").on("click", function () {
    $(".messenger-listView").hide();
  });
  $(".show-listView").on("click", function () {
    routerPush(document.title, `${url}/`);
    $(".messenger-listView").show();
  });

  // click action for [add to favorite] button.
  $(".add-to-favorite").on("click", function () {
    star(getMessengerId());
  });

  // calling Css Media Queries
  cssMediaQueries();




  // message form on submit.
  $(".send__message").on("submit", (e) => {
    e.preventDefault();
    sendMessage();
  });

  // message input on keyup [Enter to send, Enter+Shift for new line]
  $(".send__message .m-send").on("keyup", (e) => {
    // if enter key pressed.
    if (e.which == 13 || e.keyCode == 13) {
      // if shift + enter key pressed, do nothing (new line).
      // if only enter key pressed, send message.
      if (!e.shiftKey) {
        triggered = isTyping(false);
        sendMessage();
      }
    }
  });

  // On [upload attachment] input change, show a preview of the image/file.
  $("body").on("change", ".upload-attachment", (e) => {
    let file = e.target.files[0];
    if (!attachmentValidate(file)) return false;
    let reader = new FileReader();
    let sendCard = e.target.closest(".dialog__formular");
    reader.readAsDataURL(file);
    reader.addEventListener("loadstart", (e) => {
      $(".message-form").before(loadingSVG());
    });
    reader.addEventListener("load", (e) => {
      $(".dialog__formular").find(".loadingSVG").remove();
      if (!file.type.match("image.*")) {
        console.log('filetype = image', sendCard)
        // if the file not image
        sendCard.find(".dialog__formular").remove(); // older one
        sendCard.insertAdjacentHTML("beforebegin", attachmentTemplate("file", file.name));
        // sendCard.prepend(attachmentTemplate("file", file.name));
      } else {
        // if the file is an image
        // console.log('filetype = image', sendCard)
        let attach = sendCard.querySelector(".dialog__formular"); // older one
        if(attach){
          attach.remove()
        }
        // attachmentTemplate("image", file.name, e.target.result)
        sendCard.insertAdjacentHTML("beforebegin", attachmentTemplate("image", file.name, e.target.result));
      }
    });
  });

  function attachmentValidate(file) {
    const fileElement = $(".upload-attachment");
    const { name: fileName, size: fileSize } = file;
    const fileExtension = fileName.split(".").pop();
    if (
        !chatify.allAllowedExtensions.includes(
            fileExtension.toString().toLowerCase()
        )
    ) {
      alert("file type not allowed");
      fileElement.val("");
      return false;
    }
    // Validate file size.
    if (fileSize > chatify.maxUploadSize) {
      alert("File is too large!");
      return false;
    }
    return true;
  }

  // Attachment preview cancel button.
  $("body").on("click", ".attachment-preview .cancel", () => {
    cancelAttachment();
  });

  // typing indicator on [input] keyDown
  $(".send__message .m-send").on("keydown", () => {
    if (typingNow < 1) {
      isTyping(true);
      typingNow = 1;
    }
    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(function () {
      isTyping(false);
      typingNow = 0;
    }, 1000);
  });

  // Image modal
  $("body").on("click", ".chat-image", function () {
    let src = $(this).css("background-image").split(/"/)[1];
    $("#imageModalBox").show();
    $("#imageModalBoxSrc").attr("src", src);
  });
  $(".imageModal-close").on("click", function () {
    $("#imageModalBox").hide();
  });

  // Search input on focus
  $(".messenger-search").on("focus", function () {
    $(".messenger-tab").hide();
    $('.messenger-tab[data-view="search"]').show();
  });
  $(".messenger-search").on("blur", function () {
    setTimeout(function () {
      $(".messenger-tab").hide();
      $('.messenger-tab[data-view="users"]').show();
    }, 200);
  });
  // Search action on keyup
  const debouncedSearch = debounce(function () {
    const value = $(".messenger-search").val();
    messengerSearch(value);
  }, 500);
  $(".messenger-search").on("keyup", function (e) {
    const value = $(this).val();
    if ($.trim(value).length > 0) {
      $(".messenger-search").trigger("focus");
      debouncedSearch();
    } else {
      $(".messenger-tab").hide();
      $('.messenger-listView-tabs a[data-view="users"]').trigger("click");
    }
  });

  // Delete Conversation button
  $(".messenger-infoView-btns .delete-conversation").on("click", function () {
    app_modal({
      name: "delete",
    });
  });
  // Delete Message Button
  $("body").on("click", ".message-card .actions .delete-btn", function () {
    app_modal({
      name: "delete",
      data: $(this).data("id"),
    });
  });
  // Delete modal [on delete button click]
  $(".app-modal[data-name=delete]")
      .find(".app-modal-footer .delete")
      .on("click", function () {
        const id = $("body")
            .find(".app-modal[data-name=delete]")
            .find(".app-modal-card")
            .attr("data-modal");
        if (id == 0) {
          deleteConversation(getMessengerId());
        } else {
          deleteMessage(id);
        }
        app_modal({
          show: false,
          name: "delete",
        });
      });
  // delete modal [cancel button]
  $(".app-modal[data-name=delete]")
      .find(".app-modal-footer .cancel")
      .on("click", function () {
        app_modal({
          show: false,
          name: "delete",
        });
      });

  // Settings button action to show settings modal
  $("body").on("click", ".settings-btn", function (e) {
    e.preventDefault();
    app_modal({
      show: true,
      name: "settings",
    });
  });

  // on submit settings' form
  $("#update-settings").on("submit", (e) => {
    e.preventDefault();
    updateSettings();
  });
  // Settings modal [cancel button]
  $(".app-modal[data-name=settings]")
      .find(".app-modal-footer .cancel")
      .on("click", function () {
        app_modal({
          show: false,
          name: "settings",
        });
        cancelUpdatingAvatar();
      });
  // upload avatar on change
  $("body").on("change", ".upload-avatar", (e) => {
    // store the original avatar
    if (defaultAvatarInSettings == null) {
      defaultAvatarInSettings = $(".upload-avatar-preview").css(
          "background-image"
      );
    }
    let file = e.target.files[0];
    if (!attachmentValidate(file)) return false;
    let reader = new FileReader();
    reader.readAsDataURL(file);
    reader.addEventListener("loadstart", (e) => {
      $(".upload-avatar-preview").append(
          loadingSVG("42px", "upload-avatar-loading")
      );
    });
    reader.addEventListener("load", (e) => {
      $(".upload-avatar-preview").find(".loadingSVG").remove();
      if (!file.type.match("image.*")) {
        // if the file is not an image
        console.error("File you selected is not an image!");
      } else {
        // if the file is an image
        $(".upload-avatar-preview").css(
            "background-image",
            'url("' + e.target.result + '")'
        );
      }
    });
  });
  // change messenger color button
  $("body").on("click", ".update-messengerColor .color-btn", function () {
    messengerColor = $(this).attr("data-color");
    $(".update-messengerColor .color-btn").removeClass("m-color-active");
    $(this).addClass("m-color-active");
  });
  // Switch to Dark/Light mode
  $("body").on("click", ".dark-mode-switch", function () {
    if ($(this).attr("data-mode") == "0") {
      $(this).attr("data-mode", "1");
      $(this).removeClass("far");
      $(this).addClass("fas");
      dark_mode = "dark";
    } else {
      $(this).attr("data-mode", "0");
      $(this).removeClass("fas");
      $(this).addClass("far");
      dark_mode = "light";
    }
  });

  //Messages pagination
  actionOnScroll(
      ".m-body.messages-container",
      function () {
        fetchMessages(getMessengerId());
      },
      true
  );
  //Contacts pagination
  actionOnScroll(".messenger-tab.users-tab", function () {
    getContacts();
  });
  //Search pagination
  actionOnScroll(".messenger-tab.search-tab", function () {
    messengerSearch($(".messenger-search").val());
  });
});

/**
 *-------------------------------------------------------------
 * Observer on DOM changes
 *-------------------------------------------------------------
 */
let previousMessengerId = getMessengerId();
const observer = new MutationObserver(function (mutations) {
  if (getMessengerId() !== previousMessengerId) {
    previousMessengerId = getMessengerId();
    initClientChannel();
  }
});
const config = { subtree: true, childList: true };

// start listening to changes
observer.observe(document, config);

// stop listening to changes
// observer.disconnect();

/**
 *-------------------------------------------------------------
 * Resize messaging area when resize the viewport.
 * on mobile devices when the keyboard is shown, the viewport
 * height is changed, so we need to resize the messaging area
 * to fit the new height.
 *-------------------------------------------------------------
 */
var resizeTimeout;
window.visualViewport.addEventListener("resize", (e) => {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(function () {
    const h = e.target.height;
    if (h) {
      $(".messenger-messagingView").css({ height: h + "px" });
    }
  }, 100);
});

/**
 *-------------------------------------------------------------
 * Emoji Picker
 *-------------------------------------------------------------
 */
const emojiButtons = document.querySelectorAll(".emoji-button");

emojiButtons.forEach((emojiButton)=>{
  const emojiPicker = new EmojiButton({
    autoHide: false,
    showCategoryButtons: false,
    showSearch: false,
    showPreview: false,
    showRecents: false,
    emojiVersion: '12.1',
    emojiSize: '1.3em',
    position: 'top-start',
    theme: 'light',
    emojisPerRow: 6
  });

  emojiButton.addEventListener("click", (e) => {
    e.preventDefault();
    emojiPicker.togglePicker(emojiButton);
    let picker = document.querySelector('.wrapper');
    picker.style.zIndex = '999';
  });


  emojiPicker.on("emoji", (emoji) => {
    let tabs = document.querySelector('#tabs');
    let tab = tabs.querySelector('.tabs__item_active')
    let form = tab.querySelector(".send__message");

    const el = form.querySelector('.form-item__input');
    const startPos = el.selectionStart;
    const endPos = el.selectionEnd;
    const value = el.value;
    const newValue =
        value.substring(0, startPos) +
        emoji +
        value.substring(endPos, value.length);
    el.value = newValue;
    el.selectionStart = el.selectionEnd = startPos + emoji.length;
    $(el).focus();
  });

})


/**
 *-------------------------------------------------------------
 * Notification sounds
 *-------------------------------------------------------------
 */
function playNotificationSound(soundName, condition = false) {
  if ((document.hidden || condition) && chatify.sounds.enabled) {
    const sound = new Audio(
        `/${chatify.sounds.public_path}/${chatify.sounds[soundName]}`
    );
    sound.play();
  }
}
/**
 *-------------------------------------------------------------
 * Update and format dates to time ago.
 *-------------------------------------------------------------
 */
function updateElementsDateToTimeAgo() {
  $(".message-time").each(function () {
    const time = $(this).attr("data-time");
    $(this).find(".time").text(dateStringToTimeAgo(time));
  });
  $(".contact-item-time").each(function () {
    const time = $(this).attr("data-time");
    $(this).text(dateStringToTimeAgo(time));
  });
}
setInterval(() => {
  updateElementsDateToTimeAgo();
}, 60000);





function updateMessageCard(id, parent){

  let tab = document.querySelector('.tabs__item_active')

  let card = parent.querySelector(".chats__dialog");

  card.dataset.id = id;
  let footer = card.querySelector('.dialog__footer')
  let attachment = footer.querySelector('.attachment-preview')
  if(attachment){
    attachment.classList.add('hide')
  }

  const dataToSend = {
    id: id,
    _token: csrfToken
  };

  fetch('/getuser', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(dataToSend)
  })
      .then(response => response.json())
      .then(data => {
        if(data.user){

          let searchDiv = card.querySelector('#searchDiv')
          if(searchDiv.style.display == 'block'){
            searchDiv.style.display = 'none'
          }
          let userBlock = card.querySelector('#titleDiv')
          if(userBlock.style.display == 'none'){
            userBlock.style.display = 'block'
          }

          let user = data.user;
          let userName = card.querySelector(".title__text");
          userName.innerHTML = [user.first_name, user.last_name].join(' ')
          let lastSeen = card.querySelector(".status__text");
          if(user.last_online_at != null){
            lastSeen.innerHTML = dateStringToTimeAgo(user.last_online_at);
          }


          let messages = data.messages;
          let messageGroup = card.querySelector(".dialog__messages");
          messageGroup.innerHTML = '';
          messageGroup.innerHTML = messages

          let tabs = document.querySelector('#tabs');
          let tab = tabs.querySelector('.tabs__item_active')
          let tabId = tab.dataset.tabid
          refreshTab(tabId, getMessengerId())


          card.classList.remove('hide');
          parentCard = card.closest('.section__subsection')
          parentCard.classList.remove('hide')
          const audioElements = document.querySelectorAll('#audioElement');

          audioElements.forEach(element => {
            element.addEventListener('play', function() {
              element.style.border = '2px solid green';
            });

            element.addEventListener('pause', function() {
              element.style.border = 'none';
            });

          })
        }
      })
      .catch(error => {
        console.error('Error sending data:', error);
      });

}

function putMessage(message, parent){

  let messageGroup = parent.querySelector(".messages__group");

  let dateObj = formatDateToObject(message.created_at);

  let messageHtml = ''
  let dataMessage = ''
  let messageId = message.id
  let reactions = ''
  for (let reaction in message.reactions){
    reactions += message.reactions[reaction].reaction;
  }

  if(_.has(message, 'attachment') == true && message.attachment !=null){
    dataMessage = `<img src="/storage/attachments/${JSON.parse(message.attachment).new_name}">`
  }else if(_.has(message, 'audio') == true && message.audio != null){
    dataMessage = `<audio id="audioElement" controls><source src="/storage/audio/${message.audio}" type="audio/webm">Ваш браузер не поддерживает аудиофайлы WebM.</audio>`
  }else{
    dataMessage = message.message
  }
  if(message.direction == 'sent'){

    messageHtml =
        `<div class="messages__item messages__item_primary"  data-messageid="${message.id}">
      <div class="messages__header">
        <div class="messages__status status">
          <span class="title__text">${dateObj.date}</span>
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
          <p>${dataMessage}</p>
        </div>
      </div>
      <div class="messages__footer">
        <div class="messages__date date">
          <span class="date__text">${dateObj.time}</span>
        </div>
        <div class="emoji-reaction">${reactions}</div>
      </div>
    </div>`;

  }else{

    messageHtml =
        `<div class="messages__item messages__item_secondary">
      <div class="messages__header">
        <div class="messages__status status"> 
          <span class="title__text">${dateObj.date}</span>
          <div class="actions__preview dropdown-init">
          <svg class="maxHeight">
          <use xlink:href="/image/svg/sprite.svg#dots"></use>
          </svg>
          <div class="menu__dropdown layout__dropdown dropdown">
          <div class="dropdown__list">
          <div class="dropdown__item" data-msgid="${message.id}" onclick="replyMessage(event)">
          <a href="#" class="dropdown__link">
          <div class="dropdown__title title">
          <span class="title__text">Комментировать</span>
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
          <div class="dropdown__item" data-msgid="${message.id}">
          <a href="#" class="dropdown__link">
          <div class="dropdown__title title">
          <span class="title__text">Переслать</span>
          </div>
          </a>
          </div>
          <div class="dropdown__item" data-msgid="${message.id}">
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
        <div class="wysiwyg" data-messageid="${message.id}">
            <div id="waveform"></div>
          <p>${dataMessage}
          </p>
        </div>
      </div>
      <div class="messages__footer">
        <div class="messages__date date">
          <span class="date__text">${dateObj.time}</span>
        </div>
      </div>
    </div>`;

  }

  messageGroup.innerHTML += messageHtml


}

/**
 *-------------------------------------------------------------
 * Voice Messaging
 *-------------------------------------------------------------
 */

const audio = new Audio()
audio.controls = true
// audio.src = '/public/audio/' + message.audio
const wavesurfer = WaveSurfer.create({
  container: document.body,
  waveColor: 'rgb(11, 50 , 55)',
  progressColor: 'rgb(11 , 118 , 54)',
  media: audio,
})

let audio_buttons = document.querySelectorAll('.record_button');
audio_buttons.forEach((audio_button)=>{
  audio_button.addEventListener('click', function () {
    if (!isRecording) {
      startRecording(audio_button);
    } else {
      stopRecording(audio_button);
    }
  });
})

function startRecording(audio_button) {
  navigator.mediaDevices.getUserMedia({ audio: true })
      .then(function (stream) {
        var options = {
          type: 'audio',
          mimeType: 'audio/wav',
        };
        recorder = RecordRTC(stream, options);
        recorder.startRecording();

        isRecording = true;
        audio_button.innerHTML = '<span class="fas fa-microphone-slash"></span>';
      })
      .catch(function (error) {
        console.error('Ошибка при получении доступа к микрофону:', error);
      });
}

function stopRecording(audio_button) {
  recorder.stopRecording(function () {
    var blob = recorder.getBlob();

    isRecording = false;
    audio_button.innerHTML = '<span class="fas fa-microphone"></span>';

    wavesurfer.load(URL.createObjectURL(blob));

    saveRecordingToServer(blob);
  });
}

function startPlayback() {
  wavesurfer.play();
  isPlaying = true;
  document.getElementById('play-button').innerHTML = '<span class="fas fa-pause"></span>';
}

function stopPlayback() {
  wavesurfer.pause();
  isPlaying = false;
  document.getElementById('play-button').innerHTML = '<span class="fas fa-play"></span>';
}


function saveRecordingToServer(blob) {
  temporaryMsgId += 1;
  let tempID = `temp_${temporaryMsgId}`;
  var formData = new FormData();
  formData.append('audio', blob, 'recording.wav');
  formData.append('_token', csrfToken)
  formData.append('user_id', getMessengerId())
  formData.append('tempID', tempID)


  $.ajax({
    url: '/save-audio',
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    beforeSend: ()=> {
      let tabs = document.querySelector('#tabs');
      let tab = tabs.querySelector('.tabs__item_active')
      let container = tab.querySelector(".dialog__messages")
      let tempMess = sendTempMessageCard(loadingSVG("28px"), tempID)
      container.innerHTML += tempMess

    },
    success: function (response) {

      if(response.success == true){
        const tempMsgCardElement = messagesContainer.find(
            `.messages__item[data-id=${response.tempID}]`
        );
        tempMsgCardElement.before(response.message);
        tempMsgCardElement.remove();

      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении аудиофайла на сервере:', error);
    }
  });
}

/**
 *-------------------------------------------------------------
 * ReadMessages
 *-------------------------------------------------------------
 */

let btn = document.querySelectorAll('.form-item__media_second')

btn.forEach(input => {
  input.addEventListener('click', function(){

    input.innerHTML = `
    <svg>
      <use xlink:href="/image/svg/sprite.svg#readMessage"></use>
    </svg>
  `;
    let tab = input.closest('.tabs__item_active')
    let userList = tab.querySelector('.preview__list')
    let user = userList.querySelector('.preview__item_active')
    let userId = 0
    if(user != null){
      userId = user.dataset.contact
    }
    let fd = new FormData();
    fd.append('_token', csrfToken);
    fd.append('tabId', tab.dataset.tabid)
    fd.append('userId', userId)

    $.ajax({
      url: '/set-read-message',
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){

          updateMessagesStatus(response, input)

        }
      },
      error: function (error) {
        console.error('Ошибка при сохранении аудиофайла на сервере:', error);
      }
    });




  })
})



function updateMessagesStatus(response, input){

  let tab = input.closest('.tabs__item_active')

  const userList = tab.querySelector('.preview__list')

  userList.innerHTML = '';
  userList.innerHTML = response.contacts

  let userFoldersTabs = tab.closest('.tabs')

  let userFolders = userFoldersTabs.querySelector('.tabs__list_second')

  userFolders.innerHTML = ''
  userFolders.innerHTML = response.folders

  let layoutMiddle = document.querySelector('.layout__middle')

  let menu = layoutMiddle.querySelector('.layout__menu.menu')
  menu.innerHTML = ''
  menu.innerHTML = response.userMenu

}

/**
 *-------------------------------------------------------------
 * Tabs
 *-------------------------------------------------------------
 */


if ($(".tabs").length) {
  tabsInit();
}

function tabsInit() {
  let position,
      tabsActive = ("tabs__item_active");
  $('.tabs__header').on("click", ".tabs__item", function () {
    position = $(this).index();
    $(this).addClass(tabsActive).siblings().removeClass(tabsActive);
    $(this).closest(".tabs").find(">.tabs__body").find(">.tabs__list").find(">.tabs__item").eq(position).addClass(tabsActive).siblings().removeClass(tabsActive);
    modalHide()
    if(this.dataset.tabid){
      refreshTab(this.dataset.tabid)
    }
  });

}

function refreshTab(tabId, user_id = 0){

  let sendData = new FormData()
  sendData.append('tabId', tabId)
  sendData.append('user_id', user_id)
  sendData.append('_token', csrfToken)
  $.ajax({
    url: '/refresh-tab',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){

        userData = response.contacts

        let tabs = document.querySelector('#tabs');

        let tab = tabs.querySelector(`.tabs__item[data-tabid="${tabId}"]`)
        let userBlock = tab.querySelector('.preview__list')
        userBlock.innerHTML = ''
        userBlock.innerHTML = userData

        let foldersContainer = document.querySelector('.tabs__list_second')
        foldersContainer.innerHTML = ''
        foldersContainer.innerHTML = response.folders

        let userMenu = document.querySelector('.layout__menu.menu')
        userMenu.innerHTML = ''
        userMenu.innerHTML = response.userMenu

      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }
  });

}

/**
 *-------------------------------------------------------------
 * MODAL INIT
 *-------------------------------------------------------------
 */

if ($(".section__subsection_modal").length) {
  subsectionModal()
}
function subsectionModal() {
  $(".layout__section").on('click', '.subsection__modal-init', function () {
    $(this).closest(".layout__section").find(".section__subsection_modal").addClass("modal_active");
  });
  $(".section__subsection").on('click', '.modal__remove', function () {
    $(this).closest(".section__subsection_modal").removeClass("modal_active");
  });
}

modalInit();
function modalInit() {
  let modalName;
  layout = $('.layout')
  // modal show
  $(document).on('click', '.modal-init', function () {
    // layout.addClass("layout_modal-active").find(".modal__layout").removeClass("modal__layout_active");

    var modalName = $(this).data("modalname");

    console.log(modalName)

    let modal = document.querySelector("." + modalName + "")
    // console.log(modal)
    // $(document).mouseup(function (e) {
    //   if ($(".modal__main.active").length) {
    //     var div = $(".modal__main");
    //     if (!div.is(e.target) && div.has(e.target).length === 0) {
    //       modalHide();
    //     }
    //   }
    // });

    if(this.hasAttribute('data-msgid')){
      if(modal.hasAttribute('data-msgid')){
        fillModal(modal, $(this).data('msgid'), getClassForModal, modalName)
      }else if(modal.hasAttribute('data-contact')){
        fillModal(modal, getMessengerId(), getClassForModal, modalName)
      }
    }else{

      if(modal.hasAttribute('data-contact')){

        fillModal(modal, getMessengerId(), getClassForModal, modalName)

      }else if(modal.hasAttribute('data-id')){

        fillModal(modal, $(this).data("folderid"), getClassForModal, modalName)

      }else{

        fillModal(modal, getMessengerId(), getClassForModal, modalName)

      }

    }


  });
  // modal hide
  // $(document).mouseup(function (e) {
  //   if ($(".modal__main.active").length) {
  //     var div = $(".modal__main");
  //     if (!div.is(e.target) && div.has(e.target).length === 0) {
  //       modalHide();
  //     }
  //   }
  // });
  // modal hide
  // $(document).on('click', '.modal__action', function () {
  //   modalHide();
  // });
  // modal hide
  $(window).keydown(function(e){
    if (e.key === "Escape") {
      modalHide();
    }
  });

  function modalHide() {
    layout.removeClass("layout_modal-active").find(".modal__main").removeClass("modal__layout_active");
  }
}

$('#folder_name').on('input', () => {
  const folderName = document.getElementById('folder_name')

  let buttonSubmit = document.getElementById('btn_addFolder');

  if(folderName.value != ''){
    buttonSubmit.removeAttribute('disabled')
  }else{
    buttonSubmit.setAttribute("disabled", "disabled")
  }


});

function getClassForModal(modalName){


  var layout = $('.layout')

  if (modalFilled == true){
    layout.addClass('layout_modal-active')
    layout.find("." + modalName + "").addClass("modal__layout_active");
  }

}

$(document).on('click', '.modal__action', function () {
  modalHide();
});
// modal hide
$(window).keydown(function(e){
  if (e.key === "Escape") {
    modalHide();
  }
});

$(document).on('click', '.btn_tertiary', function(){
  modalHide()
})

function modalHide() {
  let layout = $('.layout')
  layout.removeClass("layout_modal-active");
  layout.find(".modal__layout").removeClass("modal__layout_active");
}

function fillModal(modal, dataId, callback, modalName){

  if(modal.hasAttribute('data-contact')){
    modal.setAttribute('data-contact', dataId)
  }else if(modal.hasAttribute('data-id')){
    modal.setAttribute('data-id', dataId)
  }else if(modal.hasAttribute('data-msgid')){
    modal.setAttribute('data-msgid', dataId)
  }
  let sendData = new FormData()
  sendData.append('id', dataId);
  sendData.append('_token', csrfToken);

  if(modal.classList.contains('modal__layout_profile')){
    $.ajax({
      url: '/user-info',
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){

          let userInfo = response.userInfo

          let avatar = modal.querySelector('#avatarBlock')
          if(avatar){
            avatar.innerHTML = ''
            if(userInfo.image == null){
              avatar.innerHTML = `<img src="/image/avatar.png" />`
            }else{
              avatar.innerHTML = `<img src="/storage/${userInfo.image}" />`
            }
          }


          let nameBlock = modal.querySelector('#username')
          if(nameBlock){
            nameBlock.innerHTML = ''
            nameBlock.innerHTML = `<h4>${[userInfo.first_name, userInfo.last_name].join(' ')}</h4>`
            if(userInfo.signature) {
              nameBlock.innerHTML += `<p>${userInfo.signature}</p>`
            }
          }

          let tooltips = modal.querySelector('.tooltips__list')
          if(tooltips){
            tooltips.querySelector('#views').innerHTML = userInfo.view
            tooltips.querySelector('#score').innerHTML = userInfo.score
            tooltips.querySelector('#favorite').innerHTML = userInfo.subscribe
          }

          let description = modal.querySelector('#description')
          if(description){
            description.innerHTML = ''
            if(userInfo.description){
              description.innerHTML = userInfo.description
            }
          }

          let socials = modal.querySelector('#socials')
          if(socials){
            socials.innerHTML = ''

            for(let key in userInfo.socials){
              if(userInfo.socials.hasOwnProperty(key)){
                socials.innerHTML += `<div class="socials__item">
            <a href="${userInfo.socials[key]}" class="socials__link" target="_blank">
            <svg>
            <use xlink:href="${getSocialIcon(key)}"></use>
            </svg>
            </a>
            </div>`
              }
            }
          }
          let settings = modal.querySelector('#checkboxes')
          if(settings){

            settings.innerHTML = ''
            settings.innerHTML = userInfo.settings

          }

          let buttons = modal.querySelector('#buttons')
          let button = buttons.querySelector('#buttonDetail')
          if(button){
            button.remove()
          }

          buttons.innerHTML = `<div class="form-item"><div class="form-item__main"><div class="form-item__field"><a class="btn w-100" href="/chat/${userInfo.id}" id="buttonDetail">
                                    <span class="button__text">Отправить сообщение</span>
                                </a>
                                </div>
                                </div>
                                </div>
                                <div class="form-item mb-0"><div class="form-item__main"><div class="form-item__field">
                                <a class="btn btn_tertiary w-100" href="/id${userInfo.id}" id="buttonDetail">
                                    <span class="button__text">Перейти в профиль</span>
                                </a>
                                </div>
                                </div>
                                </div>`

        }

        modalFilled = true
        callback(modalName)
      },
      error: function (error) {
        console.error('Ошибка на сервере:', error);
        modalFilled = false
        return false
      }
    });

  }else if(modal.classList.contains('modal__layout_delete_chat')){
    $.ajax({
      url: '/get-user',
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){

          let userInfo = response.userInfo

          let description = document.getElementById('checkbox_text')
          let pElement = modal.querySelector('p')
          if(pElement){
            pElement.remove()
          }
          pElement = document.createElement('p');
          pElement.textContent = `Подтвердите удаление сообщение / чата с пользователем ${userInfo.first_name}`

          description.parentNode.insertBefore(pElement, description);

          let textCheckbox = modal.querySelector('#delete_text')
          textCheckbox.innerHTML = ''
          textCheckbox.innerHTML = `<p>Также удалить у ${userInfo.first_name}</p>`
          modalFilled = true
          callback(modalName)
        }
      },
      error: function (error) {
        console.error('Ошибка на сервере:', error);
        modalFilled = false
        return false
      }
    });

  }else if(modal.classList.contains('modal__layout_folder-edit')){

    let folderName = document.getElementById('change_folder_name')

    folderName.value = document.querySelector(`.tabs__item[data-tabId="${dataId}"]`).querySelector('.title__text').textContent

    $.ajax({
      url: '/folder-edit',
      cache: false,
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){
          let userData = response.contacts

          let usersContainer = modal.querySelector('#userList')
          let users = modal.querySelectorAll('.series__group');
          users.forEach(user => {
            user.remove()
          })

          const containerOption = modal.querySelector('#countFolders');
          containerOption.innerHTML = ''
          let items = []

          for (let i=3; i <= response.folders.countFolders; i++){
            const value = i.toString()
            const label = i.toString()
            const obj = {value, label}
            if(i == response.folders.sort){
              obj.selected = true
            }
            items.push(obj)
          }
          // console.log('11111',this.tempChoices)
          if(containerOption.dataset.active != 'true'){

            const choices = new Choices(containerOption, {
              searchEnabled: false,
              itemSelectText: '',
              allowHTML: true,
              choices: items
            });
            containerOption.dataset.active = true
            tempChoices = choices

          }else{

            tempChoices.clearChoices()
            tempChoices.setChoices(items)

          }

          usersContainer.innerHTML = userData

          modalFilled = true
          callback(modalName)
        }
      },
      error: function (error) {
        console.error('Ошибка на сервере:', error);
        modalFilled = false
        return modalFilled;
      }
    });

  }else if(modal.classList.contains('modal__layout_move-user-to-folder')){

    $.ajax({
      url: '/get-user-folders',
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){

          let folderContainer = modal.querySelector('#user_folders')

          let items = []

          for (let folder in response.folders){

            folderContainer.innerHTML += `<option value="${response.folders[folder].id}">${response.folders[folder].folder_name}</option>`

          }
          const containerOption = document.querySelector('.js-choice');
          const choices = new Choices(containerOption, {
            searchEnabled: false,
            itemSelectText: ''
          });
          modalFilled = true
          callback(modalName)

        }
      },
      error: function (error) {
        console.error('Ошибка на сервере:', error);
        return true
      }
    });

  }else if(modal.classList.contains('modal__layout_folder-delete')){

    $.ajax({
      url: '/get-data-del-folder',
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){

          let folderData = response.folder

          let folder_name = modal.querySelector('#folder_name')
          folder_name.innerHTML = ''
          folder_name.innerHTML = `Удалить папку «${folderData.folder_name}»`

          let usersBlock = modal.querySelector('#users_block')

          if(folderData.users != null){
            if(usersBlock.classList.contains('hide')){
              usersBlock.classList.remove('hide')
            }
            let items = []
            let userFoldersBlock = modal.querySelector('#user_folders')
            userFoldersBlock.innerHTML = ''
            let user_folders = response.user_folders
            for (let folder in user_folders){
              const value = user_folders[folder].id.toString()
              const label = user_folders[folder].folder_name
              const obj = {value, label}
              items.push(obj)

              // userFoldersBlock.innerHTML += `<option value="${user_folders[folder].id}">${user_folders[folder].folder_name}</option>`
            }
            const container = document.querySelector('.select');
            if(container.dataset.active != 'true'){
              const choices = new Choices(container, {
                searchEnabled: false,
                itemSelectText: '',
                noChoicesText: 'Не создано папок для переноса',
                noResultsText: 'Ничего не найдено',
                placeholderValue: 'Укажите папку',
                choices: items
              });
              container.dataset.active = true
              tempChoices = choices
            }else{
              tempChoices.clearChoices()
              tempChoices.setChoices(items)
            }

          }else{
            usersBlock.classList.add('hide')
          }
          modalFilled = true
          callback(modalName)
        }
      },
      error: function (error) {
        console.error('Ошибка на сервере:', error);
        modalFilled = false
        return false
      }
    });

  }else if(modal.classList.contains('modal__layout_forward-message')){
    $.ajax({
      url: '/get-users-to-forward',
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){
          let userData = response.contacts
          let usersContainer = modal.querySelector('#userList')
          usersContainer.innerHTML = ''
          usersContainer.innerHTML = userData

          modalFilled = true
          callback(modalName)
        }
      },
      error: function (error) {
        console.error('Ошибка на сервере:', error);
        return false
      }
    });
  }else if(modal.classList.contains('modal__layout_сomplaint-folder')){

    const containerOption = modal.querySelector('#reasons')
    const choices = new Choices(containerOption, {
      searchEnabled: false,
      itemSelectText: ''
    });

    modalFilled = true
    callback(modalName)
  }else if(modal.classList.contains('modal__layout_folder-create')){

    let folder_name = modal.querySelector('input[name="folder_name"]')
    folder_name.value = ''

    $.ajax({
      url: '/get-users-to-create',
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {

        if(response.status == true){
          let userData = response.contacts
          let usersContainer = modal.querySelector('#usersCreate')
          let users = modal.querySelectorAll('.series')
          users.forEach(user => {
            user.remove()
          })


          usersContainer.innerHTML = response.contacts;

          modalFilled = true

          callback(modalName)
        }else{
          // console.log(response)
          showNotification(response.error, 4000)
        }
      },
      error: function (error) {
        console.error('Ошибка на сервере:', error);

        return false
      }
    });
  }



}

function putUserInModal(user, container, modal = ''){

  let userImage = user.image == null ? '/image/avatar.png' : user.image
  let userName = [user.first_name, user.last_name].join(' ')
  let userBlock = ''
  if (modal != ''){
    messageId = modal.getAttribute('data-msgid')
  }

  userBlock = `<div class="series no-wrap">
            <div class="series__group d-flex align_center mb-0">
                <div class="form-item mb-0">
                    <div class="form-item__main">
                        <div class="form-item__field">
                            <div class="custom-check">
                                <label class="custom-check__label">
                                    <input class="custom-check__input" type="checkbox" name="chekbox_${user.id}" data-userid="${user.id}" data-id="${user.id}">
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
            <div class="series__group series__group_quarty mb-0" onclick="checkUser(${user.id})">
                <div class="media media_tertiary mb-0">
                    <img data-src="${userImage}" src="${userImage}" alt="image description">
                </div>
            </div>
            <div class="series__group d-flex align_center mb-0" onclick="checkUser(${user.id})">
                <div class="wysiwyg mb-0">
                    <h6>${userName}</h6>
                </div>
            </div>
        </div>`

  container.innerHtml += userBlock
  // container.insertAdjacentHTML('afterend', userBlock)
}

let folderName = document.getElementById('change_folder_name')

function foldernameOnInput(){
  let button = document.getElementById('btn_change_folder')
  if(folderName.value.length == 0){
    button.setAttribute('disabled', 'disabled')
  }else{
    button.removeAttribute('disabled')
  }
}

folderName.addEventListener('input', foldernameOnInput);



function getSocialIcon(socialName){
  if(socialName == 'telegram'){
    return '/image/svg/sprite.svg#socials_01'
  }else if(socialName == 'watsapp'){
    return '/image/svg/sprite.svg#socials_02'
  }else if(socialName == 'vkontakte'){
    return '/image/svg/sprite.svg#socials_03'
  }else if(socialName == 'instagram'){
    return '/image/svg/sprite.svg#socials_04'
  }else if(socialName == 'youtube'){
    return '/image/svg/sprite.svg#socials_05'
  }else if(socialName == 'yandex_dzen'){
    return '/image/svg/sprite.svg#socials_06'
  }
}

/**
 *-------------------------------------------------------------
 * CLEAR/DELETE HISTORY
 *-------------------------------------------------------------
 */

let btnClearHistory = document.querySelector('#clearHistory')

btnClearHistory.addEventListener('click', (item)=>{

  let sendData = new FormData()
  sendData.append('user_id', getMessengerId())
  sendData.append('_token', $("meta[name=csrf-token]").attr("content"))

  $.ajax({
    url: '/delete-history',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){

        let dataId = getMessengerId()
        let parent = item.target.closest('.tabs__item')

        updateMessageCard(dataId, parent)



      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }
  });


})

let btnDeleteChats = document.querySelectorAll('#delete_chat')


btnDeleteChats.forEach(btnDeleteChat => {

  btnDeleteChat.addEventListener('click', (item)=>{

    let parent = item.target.closest('.modal__layout')

    let checkbox = parent.querySelector('input[name="delete_from"]')

    let sendData = new FormData()
    sendData.append('user_id', getMessengerId())
    sendData.append('delete_from', checkbox.checked)
    sendData.append('_token', $("meta[name=csrf-token]").attr("content"))

    $.ajax({
      url: '/delete-history',
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){

          let dataId = getMessengerId()
          let parent = item.target.closest('.tabs__item')
          modalHide()
          let activeUser = document.querySelector(`.preview__item_active[data-contact="${dataId}"]`)
          if(activeUser){
            activeUser.click()
          }

        }
      },
      error: function (error) {
        console.error('Ошибка при сохранении на сервере:', error);
      }
    });

  })

})




function addTabFolder(folder){

  let footer = document.querySelector('.layout__footer');
  let currentView = document.querySelector('.layout__main')
  currentView.style.display = 'none'
  let parentElement = footer.parentNode
  const fragment = document.createRange().createContextualFragment(folder)
  parentElement.insertBefore(fragment, footer)

}


function changeFolderName(event){
  event.preventDefault()
  let form = document.querySelector('.modal__layout_folder-edit')
  let folder_name = form.querySelector('input[type="text"]').value
  folder_sort = form.querySelector('select').value


  let sendData = new FormData()
  sendData.append('_token', csrfToken)
  sendData.append('id', form.dataset.id)
  sendData.append('folder_name', folder_name)
  sendData.append('sort', folder_sort)
  let users = form.querySelectorAll('input[type="checkbox"].custom-check__input')
  users.forEach(user=>{
    if(user.checked){
      sendData.append('users[]', user.dataset.id)
    }
  })

  $.ajax({
    url: '/change-folder-name',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){

        let userFoldersContainer = document.querySelector('.tabs__list_second')
        userFoldersContainer.innerHTML = '';
        userFoldersContainer.innerHTML = response.folders
        let userTabs = document.querySelector('#tabs')
        userTabs.innerHTML = ''
        userTabs.innerHTML = response.tabs
        modalHide()

      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }
  });

}

/**
 *-------------------------------------------------------------
 * MOVE USER TO FOLDER
 *-------------------------------------------------------------
 */

function moveUser(event){
  event.preventDefault()

  let btn = event.target

  let parent = btn.closest('.form-item').closest('.modal__main').querySelector('.form')

  let selected = parent.querySelector('#user_folders').value

  let folderFromMove = parent.closest('.modal__layout_active').dataset.id

  let userToMove = document.querySelector('.preview__item_active').dataset.contact


  let sendData = new FormData()
  sendData.append('_token', csrfToken)
  sendData.append('folder_move_id', selected)
  sendData.append('userToMove', userToMove)
  sendData.append('folder_from_id', folderFromMove)

  $.ajax({
    url: '/contact-to-move',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){
        modalHide()
        location.reload()
      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }
  });

}
/**
 *-------------------------------------------------------------
 * DELETE FOLDER
 *-------------------------------------------------------------
 */

function deleteFolder(event){


  let modal = document.querySelector('.modal__layout_folder-delete')
  let sendData = new FormData();
  sendData.append('id', modal.dataset.id)
  sendData.append('_token', csrfToken)
  let usersBlock = modal.querySelector('#users_block')
  if(usersBlock.classList.contains('hide')){
    sendData.append('folder_id', null)
  }else{
    sendData.append('folder_id',usersBlock.querySelector('#user_folders').value)
  }
  $.ajax({
    url: '/detele-folder',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){
        modalHide()
        location.reload()
      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }
  });


}
/**
 *-------------------------------------------------------------
 * BLOCK USER
 *-------------------------------------------------------------
 */

function blockUser(event){

  let modalForm = document.querySelector('.modal__layout_сomplaint-folder')
  let userId = getMessengerId() //document.querySelector('.modal__layout_сomplaint-folder').dataset.contact
  let reason = modalForm.querySelector('#reasons').value //event.target.closest('.form-item').closest('.modal__main').querySelector('#reasons').value
  let del_conversation = modalForm.querySelector('#delete_conversation').checked//event.target.closest('.form-item').closest('.modal__main').querySelector('#delete_conversation').checked

  let sendData = new FormData();
  sendData.append('_token', csrfToken);
  sendData.append('user_id', userId);
  sendData.append('reason', reason);
  sendData.append('del_conv', del_conversation)
  $.ajax({
    url: '/block-user',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){
        if(response.reload == true){
          location.reload()
        }else{
          modalHide()
          showNotification(response.complaint, 5000);
        }
      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }

  });


}

/**
 *-------------------------------------------------------------
 * REPLY MESSAGE
 *-------------------------------------------------------------
 */

function replyMessage(event){

  let activeTab = document.querySelector(`.chats__dialog[data-id="${getMessengerId()}"]`)

  if(activeTab){

    let sendCard = activeTab.querySelector('.dialog__footer')

    if(sendCard){

      let preview = sendCard.querySelector(".dialog__preview")

      if(preview){

        preview.remove()

      }

      let message_id = event.target.closest('.dropdown__item').dataset.msgid

      if(message_id){

        let messageBox = activeTab.querySelector(`[data-messageid="${message_id}"]`)
        if (messageBox){
          let message = messageBox.querySelector('.wysiwyg').innerHTML
          if(message){

            let answerForm = sendCard.querySelector('.dialog__formular')

            if(answerForm){

              answerForm.insertAdjacentHTML('beforebegin', replyTemplate(message, message_id));
              answerForm.scrollIntoView({ behavior: 'smooth' })
              let scroll = document.querySelector('.scroll')
              if(scroll){
                scroll.classList.add('hide')
              }

            }

          }
        }

      }

    }

  }

  // console.log(activeTab.querySelector(`[data-messageid="${message_id}"]`).querySelector('.wysiwyg'))
  //находим поле форму в sendCard и вставляем превью перед ней
  //
  // answerForm.insertAdjacentHTML('beforebegin', replyTemplate(message, message_id));
  //
  // answerForm.scrollIntoView({ behavior: 'smooth', block: 'center' })

}

/**
 *-------------------------------------------------------------
 * GET REACTION
 *-------------------------------------------------------------
 */

function getReaction(event){

  let targetClass = 'messages__item'
  console.log(event.target)
  let msgId = event.target.closest('.emotions__media').dataset.msgid

  event.preventDefault();
  const emojiPicker = new EmojiButton({
    // styleProperties:{
    //   'z-index': 99
    // },
    zIndex: 999,
    theme: 'white',
    style: 'twemoji',
    autoHide: false,
    categories: ['smileys'],
    emojiSize: '1em',
    showCategoryName: false,
    showSearch: false,
    showRecents: false,
    showCategoryButtons: false,
    showPreview: false,
    showVariants: true,
    position: "bottom-start",
    emojiVersion: '12.1',
  });
  emojiPicker.togglePicker(event.target.closest('.emotions__media'))

  let picker = document.querySelector('.wrapper')
  picker.style.zIndex = '9';

  let activeTab = document.querySelector(`.chats__dialog[data-id="${getMessengerId()}"]`)
  let container = activeTab.querySelector(`[data-messageid="${msgId}"]`).querySelector('.reactions__list');

  emojiPicker.on("emoji", (emoji) => {
    let sendData = new FormData();
    sendData.append('_token', csrfToken);
    sendData.append('msgId', msgId);
    sendData.append('reaction', emoji)
    $.ajax({
      url: '/set-reaction',
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){
          container.innerHTML = `<div class="reactions__item"><div class="reactions__media">` + response.reaction + `</div></div>`
          container.style.display = "flex"
        }
      },
      error: function (error) {
        console.error('Ошибка при сохранении на сервере:', error);
      }

    });

  });

}

/**
 *-------------------------------------------------------------
 * DELETE MESSAGE
 *-------------------------------------------------------------
 */

function deleteMessage(event){

  let msgId = event.target.closest('.dropdown__item').dataset.msgid;

  let sendData = new FormData();
  sendData.append('_token', csrfToken);
  sendData.append('message_id', msgId)
  $.ajax({
    url: '/delete-message',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){

        let oldMessage = document.querySelector(`[data-messageid="${response.message_id}"]`)
        oldMessage.insertAdjacentHTML('beforebegin', response.message);
        oldMessage.remove()
      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }

  });

}

let userDialog = document.querySelector('.preview__item_active')

if(userDialog){
  userDialog.addEventListener('click', function(){

    let parentItem = this.closest('.preview__list');
    let items = parentItem.querySelectorAll('.preview__item')
    items.forEach((item)=>{
      item.classList.remove('preview__item_active')
    })
    this.classList.add('preview__item_active')
    const dataId = $(this).attr("data-contact");
    let parent = this.closest('.tabs__item')
    parent.classList.remove('hide')
    updateMessageCard(dataId, parent);
    setMessengerId(dataId);
    disableOnLoad(false)
  })

}

function checkUser(id){

  let modal = document.querySelector('.modal__layout_active')
  let checkbox = modal.querySelector(`input[type="checkbox"][data-id="${id}"]`)
  if(checkbox.hasAttribute('checked')){
    checkbox.removeAttribute('checked')
  }else{
    checkbox.setAttribute('checked', 'checked')
  }

}
/**
 *-------------------------------------------------------------
 * FOLDER CREATE
 *-------------------------------------------------------------
 */
function createUserFolder(event){

  let modal = document.querySelector('.modal__layout_active')
  let folder_name = modal.querySelector('#folder_name')

  let sendData = new FormData();
  sendData.append('_token', csrfToken)
  sendData.append('folder_name', folder_name.value)


  let checkboxes = modal.querySelectorAll('input[type="checkbox"]')
  for (let input in checkboxes){
    if (checkboxes[input].checked){
      sendData.append('users[]', checkboxes[input].dataset.id)
    }
  }

  $.ajax({
    url: '/add-folder',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){
        let userFoldersContainer = document.querySelector('.tabs__list_second')
        userFoldersContainer.innerHTML = '';
        userFoldersContainer.innerHTML = response.folders
        let userTabs = document.querySelector('#tabs')
        userTabs.innerHTML = ''
        userTabs.innerHTML = response.tabs
        folder_name.value = ''
        modalHide()
      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }
  });

}
/**
 *-------------------------------------------------------------
 * FORWARD MESSAGE
 *-------------------------------------------------------------
 */

function forwardMessage(event){


  let modal = document.querySelector('.modal__layout_forward-message')
  let message_id = ''
  if(modal.hasAttribute('data-msgid')){
    message_id = modal.dataset.msgid
  }
  let sendData = new FormData();
  let checkboxes = modal.querySelectorAll(`input[type="checkbox"]`)
  for (let input in checkboxes){
    if (checkboxes[input].checked){
      sendData.append('users[]', checkboxes[input].dataset.id)
    }
  }
  sendData.append('_token', csrfToken)
  sendData.append('message_id', message_id)
  sendData.append('forward_from', getMessengerId())
  $.ajax({
    url: '/forward-message',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){
        if(response.users.length == 1){
          for (let user in response.users){
            let tabs = document.querySelector('#tabs');
            let tab = tabs.querySelector('.tabs__item_active')
            let tabId = tab.dataset.tabid
            refreshTab(tabId, getMessengerId())
            userDialog = document.querySelector(`.preview__item[data-contact="${response.users[user].id}"]`);
            if(userDialog) {
              modalHide()
              userDialog.click()
            }
          }
        }else{
          modalHide()
        }
      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }
  });


}

/**
 *-------------------------------------------------------------
 * COPY MESSAGE
 *-------------------------------------------------------------
 */

function copyMessage(event){

  let message_id = event.target.closest('.dropdown__item').dataset.msgid

  let sendData = new FormData();
  sendData.append('_token', csrfToken)
  sendData.append('message_id', message_id)
  $.ajax({
    url: '/copy-message',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){
        if(response.type == 'file'){
          const file = response.copyText;
          copyFileToClipboard(file)
        }else{
          navigator.clipboard.writeText(response.copyText)
          showNotification('Сообщение скопировано', 5000);
        }
      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }
  });

}

async function copyFileToClipboard(fileUrl) {
  try {
    const response = await fetch(fileUrl);
    const blob = await response.blob();
    const item = new ClipboardItem({ [blob.type]: blob });
    await navigator.clipboard.write([item]);
    showNotification('Файл скопирован в буфер обмена', 5000);
  } catch (error) {
    console.error('Ошибка:', error);
  }
}

function searchMessage(event){

  let activeTab = event.target.closest('.chats__dialog')

  let userTitle = activeTab.querySelector('#titleDiv')

  if (userTitle.style.display === "none"){
    userTitle.style.display = "block"
  }else{
    userTitle.style.display = "none"
  }
  let searchDiv = activeTab.querySelector('#searchDiv')
  if (searchDiv.style.display === "none"){
    searchDiv.style.display = "block"
    let searchInput = searchDiv.querySelector('#searchCorrespondence')
    searchInput.focus()
  }else{
    searchDiv.style.display = "none"

  }



  // let inputSearch = document.querySelector('#search')
  // inputSearch.focus()

}

function searchUserInModal(event){
  event.preventDefault();
  let searchTerm = event.target.value
  let modal = document.querySelector('.modal__layout_active')

  let users = modal.querySelectorAll('h6');

  if(searchTerm.length > 0){
    let sendData = new FormData();
    sendData.append('_token', csrfToken)
    sendData.append('searchText', event.target.value)

    $.ajax({
      url: '/find-user',
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){
          userData = response.contacts

          let userBlock = modal.querySelector('#userList')
          userBlock.innerHTML = ''
          userBlock.innerHTML = userData
        }
      },
      error: function (error) {
        console.error('Ошибка при сохранении на сервере:', error);
      }
    });
  }else{
    let sendData = new FormData()
    sendData.append('_token', csrfToken)
    sendData.append('folderId', 0)
    $.ajax({
      url: '/find-user',
      method: 'POST',
      data: sendData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){
          userData = response.contacts

          let userBlock = modal.querySelector('#userList')
          userBlock.innerHTML = ''
          userBlock.innerHTML = userData
        }
      },
      error: function (error) {
        console.error('Ошибка при сохранении на сервере:', error);
      }
    });
  }

}

function focusMessage(messageId){
  let message = document.querySelector(`[data-messageid="${messageId}"]`)
  if(message){
    message.scrollIntoView({ behavior: 'smooth' })
  }
}

function replyTemplate(reply, message_id) {

  let container = ''

  if(reply.includes('<audio')){
    container = '<div class="dialog__preview preview" data-message_id="' + message_id + '" id="replyMessage"><div class="preview__media"><svg><use xlink:href="/image/svg/sprite.svg#attach"></use></svg></div><div class="preview__title title"><span class="title__text">' + reply + '</span></div><div class="preview__action" onclick="removeReply()"><svg><use xlink:href="/image/svg/sprite.svg#close"></use></svg></div></div>'
  }else if(reply.includes('<img')){
    let tempElement = document.createElement('div');
    tempElement.innerHTML = reply;

    let imgElement = tempElement.querySelector('img');
    let srcAttributeValue = imgElement.getAttribute('src');

    container =  `<div class="dialog__preview preview" data-message_id="` + message_id + `" id="replyMessage">
                    <div class="preview__media">
                    <img data-src="` + srcAttributeValue + `" src="` + srcAttributeValue + `" alt="image description"/>
                    </div>
                    <div class="preview__title title">
                    <span class="title__text">Прикрепленное изображение</span>
                    </div>
                    <div class="preview__action" onclick="removeReply()">
                    <svg>
                    <use xlink:href="/image/svg/sprite.svg#close"></use>
                    </svg>
                    </div>
                    </div>`

  }else{
    container =  `<div class="dialog__preview preview" data-message_id="${message_id}" id="replyMessage">
                    <div class="preview__title title">
                    <span class="title__text">` + reply + `</span>
                    </div>
                    <div class="preview__action" onclick="removeReply()">
                    <svg>
                    <use xlink:href="/image/svg/sprite.svg#close"></use>
                    </svg>
                    </div>
                    </div>`
  }

  return container

  // return '<div class="dialog__preview preview" data-message_id="${message_id}" id="replyMessage"><span class="fas fa-times cancel" onclick="removeReply()"></span><p style="padding:0px 30px;">' + escapeHtml(reply) + '</p></div>'

}

function removeReply(){

  let activeTab = document.querySelector(`.chats__dialog[data-id="${getMessengerId()}"]`)
  if (activeTab){
    console.log('activeTab->',activeTab)
    let replyPreview = document.querySelector('.dialog__preview')
    console.log('replyPreview->', replyPreview)
    if(replyPreview){
      replyPreview.remove()
    }

  }

}

function showNotification(message, duration) {

  notification.textContent = message;
  notification.style.display = 'block';
  notification.classList.remove('fade-out');
  var layout = $('.layout')
  if(layout){
    layout.addClass('layout_modal-active')
  }


  setTimeout(() => {
    notification.classList.add('fade-out');
  }, duration - 500);

  setTimeout(() => {
    notification.style.display = 'none';
    notification.classList.remove('fade-out');
    layout.removeClass('layout_modal-active')
  }, duration);
}


function createFormData(){

  let sendData = new FormData()
  sendData.append('_token', csrfToken)

  return sendData

}


function readMessages(tabId){

  let user_id = getMessengerId()

  let sendData = createFormData()
  sendData.append('user_id', user_id)
  sendData.append('folder_id', tabId)

  $.ajax({
    url: '/read-messages',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){

        let userFolders = document.querySelector('.tabs__list_second')
        userFolders.innerHTML = ''
        userFolders.innerHTML = response.userFolders
        refreshTab(tabId, getMessengerId())


      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }
  });


}

function setSettings(event){

  let sendData = createFormData()
  sendData.append('name', event.target.name)
  sendData.append('value', event.target.checked)
  sendData.append('user_id', getMessengerId())
  let tabs = document.querySelector('#tabs');
  let tab = tabs.querySelector('.tabs__item_active')
  let tabId = tab.dataset.tabid
  sendData.append('folder_id', tabId)
  $.ajax({
    url: '/set-user-settings',
    method: 'POST',
    data: sendData,
    processData: false,
    contentType: false,
    dataType: "JSON",
    success: function (response) {
      if(response.status == true){

        let userFolders = document.querySelector('.tabs__list_second')
        userFolders.innerHTML = ''
        userFolders.innerHTML = response.userFolders
        refreshTab(tabId, getMessengerId())


      }
    },
    error: function (error) {
      console.error('Ошибка при сохранении на сервере:', error);
    }
  });


}

function fillAllModal(userId){

  let modals = document.querySelectorAll('.modal__layout')

  modals.forEach(modal => {
    console.log(modal)
    fillModal(modal, userId)

  })


}