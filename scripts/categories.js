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
      $.ajax({
        url  : 'category.class.php?type=list', 
        success :  function(output){
                    arr=$.parseJSON(output);
                    alert(arr[1]);
                    for(i=0;i<arr.size();i++)
                        $('#categoriesDiv').addCategory(arr[i]);
        }

});
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


