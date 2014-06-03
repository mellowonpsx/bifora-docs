var shown=true;
var categories=new Array();
var categoriesSelected=new Array();

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
                    categoriesSelected[k]=false;
                }
    }
});
    
}
function selectCategories(obj){
    categoriesSelected[obj.id]=!categoriesSelected[obj.id];
    if(categoriesSelected[obj.id])
        $('#'+obj.id).css( "color", "white" );
    else
        $('#'+obj.id).css( "color", "black" );
    
}
