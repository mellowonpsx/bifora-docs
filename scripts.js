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
        $('.selected').append("Poi qua mettiamo le categorie<br>");
        $('.selected').width("300px");
        sSelected='c';
    }
}
function showLogin(){
    if(sSelected!=='l'){
        $('.selected').empty();
        $('.selected').append("Poi qua mettiamo il login<br>");
        $('.selected').width("300px");
        sSelected='l';
    }
}
function hide(){
    $('.selected').empty();
    $('.selected').width("0px");
    sSelected='h';
}
function showStuff(){
    $('.content').append(addPreview("aaa","bbb","ccc",new Array("aaa", "bbb","ddd"),"a"));
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
    }
