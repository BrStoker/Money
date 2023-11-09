/**
 * changes date string to time ago string.
 * @param dateString - The date string to convert to a time ago string.
 * @returns A string that tells the user how long ago the date was.
 */
function dateStringToTimeAgo(dateString) {
  if(dateString != null){
    const now = new Date();
    const date = new Date(dateString);

    const seconds = Math.floor((now - date) / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const weeks = Math.floor(days / 7);
    if (seconds < 300) {
      return "Онлайн";
    } else if (minutes < 60) {
      return `Был(а) ${minutes} мин. назад`;
    } else if (hours < 24) {
      return `Был(а) ${hours} ч назад`;
    } else if (days < 7) {
      return `Был(а) ${days} д назад`;
    } else {
      return `Был(а) ${weeks} нед. назад`;
    }
  }else{
    return '';
  }


}
/**
 * It returns a function that, when invoked, will wait for a specified amount of time before executing
 * the original function.
 * @param callback - The function to be executed after the delay.
 * @param delay - The amount of time to wait before calling the callback.
 * @returns A function that will call the callback function after a delay.
 */
function debounce(callback, delay) {
  let timerId;
  return function (...args) {
    clearTimeout(timerId);
    timerId = setTimeout(() => {
      callback.apply(this, args);
    }, delay);
  };
}

function formatDateToObject(dateString) {
  let messageDate = new Date(dateString);
  let now = new Date();

  let messageYear = messageDate.getFullYear();
  let messageMonth = messageDate.getMonth() + 1;
  let messageDay = messageDate.getDate();

  let currentYear = now.getFullYear();
  let currentMonth = now.getMonth() + 1;
  let currentDay = now.getDate();

  let timeOptions = { hour: '2-digit', minute: '2-digit' };

  if (
      messageYear === currentYear &&
      messageMonth === currentMonth &&
      messageDay === currentDay
  ) {
    return { date: 'Сегодня', time: messageDate.toLocaleTimeString('ru-RU', timeOptions) };
  } else if (
      messageYear === currentYear &&
      messageMonth === currentMonth &&
      messageDay === currentDay - 1
  ) {
    return { date: 'Вчера', time: messageDate.toLocaleTimeString('ru-RU', timeOptions) };
  } else {
    return { date: dateString.split(' ')[0], time: messageDate.toLocaleTimeString('ru-RU', timeOptions) };
  }
}
