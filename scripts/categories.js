var shown=true;
var categories=new Array();

function showHideCategories(){
    if(shown){
        $('#categoriesDiv').stop(true,true).slideUp(0);
        shown=false;
    }else{
        $('#categoriesDiv').stop(true,true).slideDown(0);
        shown=true;
    }
}

function addCategory(name,id){
    r='<div width=285 onclick="selectCategories(this);"class="category" id="'+id+'">';
    r+=name;
    r+="</div>";
    return r;
}

function updateCategories(){
    $('#categoriesDiv').empty();
    $.ajax({
    url  : 'listCategory.php', 
    success : function(output){
                categories=$.parseJSON(output);
                for(var k in categories){
                    $('#categoriesDiv').append(addCategory(categories[k].name,categories[k].id));
                    categories[k].selected=true;
                    $('#'+categories[k].id).css( "color", "white" );
                }
    }
});
    
}
function selectCategories(obj){
    allFalse=true;
    for(var k in categories){
        if(categories[k].id!==obj.id)
            if(categories[k].selected)
                allFalse=false;
    }
    if(categories[obj.id].selected&&allFalse)
        return
    categories[obj.id].selected=!categories[obj.id].selected;
    if(categories[obj.id].selected)
        $('#'+obj.id).css( "color", "white" );
    else
        $('#'+obj.id).css( "color", "black" );
    showStuff();
}

function getSelectedCategories(){
    r=new Array();
    for(var k in categories){
        if(categories[k].selected)
            r[k]=categories[k];
    }
    return r;
}
