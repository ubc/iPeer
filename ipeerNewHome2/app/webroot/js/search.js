/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    /*
     * function getUrl(limit)
     * Return url with page limit size and exsited params
     */
    function getUrl(limit){
        var url = window.location.href;
        var urlArray = url.split('/');
        var hasLimit = false;
        url = '';
        for(var i = 0; i<urlArray.length; i++){
            if(urlArray[i].indexOf("limit:")>-1){
                url = url+'limit:'+limit+'/';
                hasLimit = true;
            }
            else{
                url = url+urlArray[i]+'/';
            }
        }
        if(!hasLimit){
            url = url+'limit:'+limit+'/';
        }
        return url.substring(0, url.length-1);
    }