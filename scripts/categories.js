function updateCategories() 
{
    $('#categoriesDiv').empty();
    $.ajax(
        {
            url: 'listCategory.php',
            success: function(output) 
            {
                categories = $.parseJSON(output);
                if(categories.status === "false")
                {
                    alert(categories.error);
                }
                else
                {
                    for (var k in categories.data) 
                    {
                        
                        $('#categoriesDiv').append(addCategory(categories.data[k].name, categories.data[k].id,categories.data[k].empty));
                        categories.data[k].selected = true;
                    }
                    $('#categoriesDiv').append(addAdder());
                    //this is the right way to do it!!! 
                    $('#categoryName').keypress(function(event)
                    {
                        if(event.keyCode == 13)
                        {
                            event.preventDefault();
                            $("#categoryButton").click();
                        }
                    });
                }
                //end of right way
                showStuff();
        }
    });
}
function addCategory(name, id,empty) 
{
    var r = '<li onclick="selectCategories(this);" class="killableCat" id="' + id + '"><div>';
        r += "<span class='nameCat'>"+name+"</span>";
        r += "<img src='css/img/edit.png' onclick='editCat(event,this)' class='killerCat'></img>";
        if(empty)
        r += "<img src='css/img/delete.png'  onclick='killCat(event,this)' class='killerCat'></img>";
        r += "<div class='editCat'><input type='text' onclick='event.stopPropagation();' name='categoryName' class='cateditor'><input type='button' onclick='submitEditCategory(event,this)' value='Edit' class='cateditor'></div>";
        r += "</div></li>";
    return r;
}

function killCat(event,obj) {
    var id= obj.parentNode.parentNode.id;
    $.ajax(
    {
        url: 'removeCategory.php',
        type: "POST",
        data: {categoryId:id},
        success:    function(output) 
                    {
                        out=$.parseJSON(output);
                        if(out.status==="false")
                            alert(out.error);
                        updateCategories();
                    }
    });
    event.stopPropagation();
}
function editCat(event,obj){
    if(obj.parentNode.getElementsByClassName("nameCat")[0].style.visibility!=="hidden")
    {
        obj.parentNode.getElementsByClassName("editCat")[0].style.visibility="visible";
        obj.parentNode.getElementsByClassName("editCat")[0].firstElementChild.value=obj.parentNode.getElementsByClassName("nameCat")[0].innerHTML;
    }
    else
    {
        obj.parentNode.getElementsByClassName("editCat")[0].style.visibility="hidden";
    }
    event.stopPropagation();
}
function submitEditCategory(event,obj)
{
    var id= obj.parentNode.parentNode.parentNode.id;
    var value=obj.parentNode.firstElementChild.value;
    $.ajax(
    {
        url: 'updateCategory.php',
        type: "POST",
        data: {
                categoryId:id,
                categoryName:value,
            
        },
        success:    function(output) 
                    {
                        out=$.parseJSON(output);
                        if(out.status==="false")
                            alert(out.error);
                        updateCategories();
                    }
    });
    
    event.stopPropagation();
}

function addAdder(){
    var r='<div class="categoryAdder"><form id="addCategory">';
            r+='<input type="text" id="categoryName" name="categoryName">'; 
            r+='<input type="button" id="categoryButton" onclick="submitCategory()" value="Add">';
        r+='</form></div>';
    return r;
}
function submitCategory(){
    if($("#categoryName").val()==="")
        return;
    $.ajax(
    {
        url: 'insertCategory.php',
        type: "POST",
        data: $("#categoryName"),
        success:    function(output) 
                    {       
                       out=$.parseJSON(output);
                       if(out.status==="false")
                           alert(out.error);
                       //else
                       updateCategories(); 
                    }
    });
}
function selectCategories(obj) 
{
    
    var allFalse = true;
    for (var k in categories.data) 
    {
        if (categories.data[k].id !== obj.id)
        {
            if (categories.data[k].selected)
                allFalse = false;
        }
    }
            
    if (categories.data[obj.id].selected && allFalse)
    {

        return;
    }
    categories.data[obj.id].selected = !categories.data[obj.id].selected;
    if (categories.data[obj.id].selected){
        $('#' + obj.id).removeClass('unselected');
    }
    else{
        $('#' + obj.id).addClass('unselected');
    }
    showStuff();
}

function getSelectedCategories() 
{
    var r = new Array();
    var i = 0;
    for (var k in categories.data) 
    {
        if (categories.data[k].selected)
        {
            r[i] = categories.data[k];
            i++;
        }
    }
    return r;
}