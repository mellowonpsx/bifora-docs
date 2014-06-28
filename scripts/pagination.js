function refreshPaginator(max,per,sel){
    n=Math.ceil(max/per);
    $("#paginator").empty();
    if(sel!==1)
        addPagElem("<",sel-1,false);
    for(i=1;i<n+1;i++){
        addPagElem(i,i,i===sel);
    }
    if(sel!==n)
        addPagElem(">",sel+1,false);
}
function addPagElem(text,val,selected){
    if(selected)
        $("#paginator").append("<span>("+text+")</span>");
    else
        $("#paginator").append("<span onclick='pagChange("+val+")'>"+text+"</span>");
}
function pagChange(val){
    showStuff(val);
}