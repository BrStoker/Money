export default{
    OnBlur(e){
      //this.InputTextMaskBlur(e)
    },
    OnFocus(e){
      //this.InputTextMaskFocus(e)
    },
    OnKeyUp(e){
      //this.InputTextKeyUp(e)
    },
    OnInputTextMaskFocus(e) {

        let mask = this.InputTextMaskPrepare(e)

        if(mask != undefined) {

          if(e.target.value.length == 0) {

            e.target.value = mask

          } else if(e.target.value[0] != mask) {

            e.target.value = mask + e.target.value

          }

        }

      },
      OnInputTextMaskBlur(e) {

        let mask = this.InputTextMaskPrepare(e)

        if(mask != undefined && e.target.value.length == 1) {

          e.target.value = ''

        }

      },
      OnInputTextKeyUp(e) {
        this.InputTextMaskFocus(e)
      }
}