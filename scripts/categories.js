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
    url  : 'category.class.php?type=list', 
    success : function(output){
                categories=$.parseJSON(output);
                for(var k in categories){
                    $('#categoriesDiv').append(addCategory(categories[k],k));
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
function showStuff(){
    $('#content').append(addPreview("aaa","bbb","ccc",new Array("aaa", "bbb","ddd"),"a"));
    $('#content').append(addPreview("aaa","bbb","ccc",new Array("aaa", "bbb","ddd"),"a"));
}
    function addPreview(title,description,type,tags,private){
        r="<div class='preview'><h3>"+title+"</h3><br><i>"+description+"</i><br>Type:"+type+"<br>Tags:";
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



