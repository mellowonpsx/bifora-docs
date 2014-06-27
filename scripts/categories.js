function showHideCategories() 
{
    if (shown) 
    {
        $('#categoriesDiv').stop(true, true).slideUp(0);
        shown = false;
    } else 
    {
        $('#categoriesDiv').stop(true, true).slideDown(0);
        shown = true;
    }
    resizeInput();
}

function updateCategories() 
{
    $('#categoriesDiv').empty();
    $.ajax(
        {
            url: 'listCategory.php',
            success: function(output) 
            {
                categories = $.parseJSON(output);
                for (var k in categories) 
                {
                    $('#categoriesDiv').append(addCategory(categories[k].name, categories[k].id,categories[k].empty));
                    categories[k].selected = true;
                    $('#' + categories[k].id).css("color", "white");
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
                //end of right way
                showStuff();
        }
    });
}
function addCategory(name, id,empty) 
{
    var r = '<li onclick="selectCategories(this);" class="killableCat" id="' + id + '"><div>';
        r += "<span class='nameCat'>"+name+"</span>";
        r += "<span onclick='editCat(event,this)' class='killerCat'>\t\te </span>";
        if(empty)
        r += "<span onclick='killCat(event,this)' class='killerCat'>\t\tx </span>";
        r += "<div class='editCat'><input type='text' onclick='event.stopPropagation();' name='categoryName'><input type='button' onclick='submitEditCategory(event,this)' value='Edit'></div>";
        r += "</div></li>";
    return r;
}

function killCat(event,obj) {
    var id= obj.parentNode.parentNode.id;
    $.ajax(
    {
        url: 'deleteCategory.php',
        type: "POST",
        data: {id:id},
        success:    function(output) 
                    {  
                       updateCategories();
                    }
    });
    event.stopPropagation();
}
function editCat(event,obj){
    if(obj.parentNode.getElementsByClassName("nameCat")[0].style.visibility!=="hidden")
    {
        obj.parentNode.getElementsByClassName("nameCat")[0].style.visibility="hidden";
        obj.parentNode.getElementsByClassName("editCat")[0].style.visibility="visible";
        obj.parentNode.getElementsByClassName("editCat")[0].firstElementChild.value=obj.parentNode.getElementsByClassName("nameCat")[0].innerHTML;
    }
    else
    {
        obj.parentNode.getElementsByClassName("nameCat")[0].style.visibility="visible";
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
        url: 'editCategory.php',
        type: "POST",
        data: {
                id:id,
                value:value,
            
        },
        success:    function(output) 
                    {       
                       alert(output);
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
        url: 'addCategory.php',
        type: "POST",
        data: $("#categoryName"),
        success:    function(output) 
                    {       
                       //alert(output); //aggiungere gestione errori
                       out=$.parseJSON(output);
                       
                       if(out.status==="false")
                           alert(out.error);
                       else
                           updateCategories();
                    }
    });
}
function selectCategories(obj) 
{
    var allFalse = true;
    for (var k in categories) 
    {
        if (categories[k].id !== obj.id)
        {
            if (categories[k].selected)
                allFalse = false;
        }
    }
    if (categories[obj.id].selected && allFalse)
    {
        return;
    }
    categories[obj.id].selected = !categories[obj.id].selected;
    if (categories[obj.id].selected)
        $('#' + obj.id).css("color", "white");
    else
        $('#' + obj.id).css("color", "black");
    showStuff();
}

function getSelectedCategories() 
{
    var r = new Array();
    var i = 0;
    for (var k in categories) 
    {
        if (categories[k].selected)
        {
            r[i] = categories[k];
            i++;
        }
    }
    return r;
}
