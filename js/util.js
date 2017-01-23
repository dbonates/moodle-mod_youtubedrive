/***************************
 * User: Eduardo Kraus
 * Date: 08/03/15
 * Time: 18:56
 ***************************/


console.log("teste");

window.onload=function()
{
    videoBox = document.getElementById('youtubedriveworkaround');
    videoBoxWidth = getWidthElement( videoBox );

    videoed = document.getElementById('videoed');
    if( videoed )
    {
        videoBoxHeight = videoBoxWidth*3/4;

        videoed.style.width  = videoBoxWidth+"px";
        videoed.style.height = videoBoxHeight+"px";
    }

    videohd1 = document.getElementById('videohd1');
    if( videohd1 )
    {
        videoBoxHeight = videoBoxWidth*9/16;

        videohd1.style.width  = videoBoxWidth+"px";
        videohd1.style.height = videoBoxHeight+"px";
    }

    videohd2 = document.getElementById('videohd2');
    if( videohd2 )
    {
        videoBoxHeight = videoBoxWidth*9/16;

        videohd2.style.width  = videoBoxWidth+"px";
        videohd2.style.height = videoBoxHeight+"px";
    }

}


function getWidthElement(element)
{
    if( element.offsetWidth )
        return element.offsetWidth;
    if( element.clientWidth )
        return element.clientWidth;
}