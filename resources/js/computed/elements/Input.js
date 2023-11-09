export default {

    isRequired() {
        return (this.required != undefined && this.required == true ? true : false)
    },
    isDisabled(){
        return (this.disabled != undefined && this.disabled == true ? true : false)
    },
    isChecked(){
        return (this.checked != undefined && this.checked == true ? true : false)
    },
    getType(){
        return (this.input.type != 'password' || this.input.type == 'password' && this.show == false ? this.input.type : 'text')
    }

}