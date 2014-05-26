/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var sSelected='h';
$( document ).ready(function() {
    main();
});
function showCategories(){
    if(sSelected!=='c'){
        $('.selected').empty();

        $('.selected').width("300px");
        sSelected='c';
    }
}
    function addCategory(name){
        r='<div width=285 onmouseover="focusIN(this);" onmouseout="focusOUT(this);" onclick="clickCategory(this);" class="category">';
        r+=name;
        r+="</div>";
        return r;
    }
    function focusIN(obj){
            obj.style.background='black';
            obj.style.color='white';
    }
    function focusOUT(obj){
            obj.style.background='#cccccc';
            obj.style.color='black';
    }
    function clickCategory(obj){
        if(obj.style.border==='solid black')
            obj.style.border="none";
        else if(obj.style.border==="none")
            obj.style.border="solid black";
        
    }
function initLogin(){
    $('#loginWrapper').hover(
        function () {
            //mostra sottomenu
            $('#loginDiv').stop(true, true).delay(50).slideDown(100);
 
        }, 
        function () {
            //nascondi sottomenu
            $('#loginDiv').stop(true, true).slideUp(200);        
        }
    );
    $('#loginDiv').stop(true, true).slideUp(0);    

}
function initCategories(){
    $('#categoriesWrapper').hover(
        function () {
            //mostra sottomenu
            $('#categoriesDiv').stop(true, true).delay(50).slideDown(100);
        }, 
        function () {
            //nascondi sottomenu
            $('#categoriesDiv').stop(true, true).slideUp(200);        
        }
    );
    $('#categoriesDiv').stop(true, true).slideUp(0);
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
    showCategories();
    showStuff();
    initLogin();
    initCategories();
    }
