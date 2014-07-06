pag=1;
function refreshPaginator(max,per,sel){
    pag=sel;
    n=Math.ceil(max/per);
    $("#paginator").empty();
    if(sel!==1){
        addPagElem("<",sel-1,false);
    }
    else
      $("#paginator").append("<span'>_</span>");
    if(sel-5>1)
        $("#paginator").append("<span'>...</span>");
    for(i=Math.max(1,sel-5);i<sel;i++){
        addPagElem(i,i,sel===i);
    }
    for(i=sel;i<Math.min(n+1,sel+5);i++){
        addPagElem(i,i,sel===i);
    }
    if(sel+5<n)
        $("#paginator").append("<span'>...</span>");
    if(sel!==n)
        addPagElem(">",sel+1,false);
    else
        $("#paginator").append("<span'>_</span>");
   
}
function addPagElem(text,val,selected){
    if(selected)
    $("#paginator").append("<span class='hand blue' >"+text+"</span>");
    else
    $("#paginator").append("<span onclick='pagChange("+val+")' class='hand'>"+text+"</span>");
}
function pagChange(val){
    showStuff(val);
}