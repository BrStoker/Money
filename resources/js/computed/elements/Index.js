export default
{
    className()
    {
        return (this.classCss != undefined ? this.classCss.replaceAll(' ','').replaceAll(',',' ') : '')
    },
    classNameItem()
    {
        return (this.classItem != undefined ? this.classItem.replaceAll(' ','').replaceAll(',',' ') : '')
    },
    classNameWrapper()
    {
        return (this.classWrapper != undefined ? this.classWrapper.replaceAll(' ','').replaceAll(',',' ') : '')
    }
}