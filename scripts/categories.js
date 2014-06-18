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
                    $('#categoriesDiv').append(addCategory(categories[k].name, categories[k].id));
                    categories[k].selected = true;
                    $('#' + categories[k].id).css("color", "white");
                }
                $('#categoriesDiv').append(addAdder());
                showStuff();
        }
    });

}
function addCategory(name, id) 
{
    var r = '<li onclick="selectCategories(this);" class="killableCat" id="' + id + '">';
        r += '<span>'+name+"</span>";
        r += "<span onclick='killCat(this)' class='killerCat'>\t\tx </span>";
        r += "</li>";
    return r;
}
function killCat(obj) {
    //Controlla che sia vuota!!!!
    var parent = obj.parentNode;
    parent.parentNode.removeChild(parent);
}
function addAdder(){
    var r='<div class="category"><form id="addCategory">';
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
