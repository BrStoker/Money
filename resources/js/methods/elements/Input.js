export default{

    getCaretPosition(ctrl) {
        if (document.selection) {
            ctrl.focus();
            var range = document.selection.createRange();
            var rangelen = range.text.length;
            range.moveStart('character', -ctrl.value.length);
            var start = range.text.length - rangelen;
            return {
                'start': start,
                'end': start + rangelen
            };
        } else if (ctrl.selectionStart || ctrl.selectionStart == '0') {
            return {
                'start': ctrl.selectionStart,
                'end': ctrl.selectionEnd
            };
        } else {
            return {
                'start': 0,
                'end': 0
            };
        }
    },
    setCaretPosition(ctrl, start, end) {
        if (ctrl.setSelectionRange) {
            ctrl.focus();
            ctrl.setSelectionRange(start, end);
        } else if (ctrl.createTextRange) {
            var range = ctrl.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
      },
      InputTextMaskPrepare(e) {

        if(typeof(e.target) != 'undefined' && e.target instanceof HTMLElement == true) {
          
          let item = e.target

          let mask = item.getAttribute('data-mask');

          if(mask != null && mask.length > 0) {

            return mask

          }

        }

      }
}