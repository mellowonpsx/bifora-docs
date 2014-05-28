
$( document ).ready(function() {
    main();
});
/*------------------------------------------------------------------------------
 * ------------LOGIN------------------------------------------------------------
 ------------------------------------------------------------------------------*/

var user="aaaa";
var salted_md5=111;

function initLogin(){
    $('#loginWrapper').hover(
        function () {
            $('#loginDiv').stop(true, true).delay(50).slideDown(100);
        }, 
        function () {
            $('#loginDiv').stop(true, true).slideUp(200);        
        }
    );
    $('#loginDiv').stop(true, true).slideUp(0);    
}
function loggati(){
    user=$('#user').val();
    pass=$('#pass').val();
    
}
function md5pass(pass,salt){
    return CryptoJS.MD5(pass||salt);
}

/*------------------------------------------------------------------------------
 * ------------CATEGORIES-------------------------------------------------------
 ------------------------------------------------------------------------------*/
var shown=true;

function showHideCategories(){
    if(shown){
        $('#categoriesDiv').stop(true,true).slideUp(0);
        shown=false;
    }else{
        $('#categoriesDiv').stop(true,true).slideDown(0);
        updateCategories();
        shown=true;
    }
}

function addCategory(name){
    r='<div width=285 class="category">';
    r+=name;
    r+="</div>";
    return r;
}

function updateCategories(){
    $('#categoriesDiv').empty();
    $('#categoriesDiv').append(addCategory("aaa"));
    $('#categoriesDiv').append(addCategory("bbb"));
}

function showStuff(){
    $('#content').append(addPreview("aaa","bbb","ccc",new Array("aaa", "bbb","ddd"),"a"));
    $('#content').append(addPreview("aaa","bbb","ccc",new Array("aaa", "bbb","ddd"),"a"));
}
    function addPreview(title,description,type,tags,private){
        r="<div class=preview>";
        r+="<h3>"+title+"</h3>";
        r+="<br><i>"+description;
        r+="</i><br>Type:"+type;
        r+="<br>Tags:";
        for(i=0;i<tags.length;i++)
            r+=tags[i]+"; ";
        r+="<br>";
        return r;
    }
function main(){
    showStuff();
    initLogin();
    updateCategories();
    }
