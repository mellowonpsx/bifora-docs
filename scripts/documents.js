b = false; //Booleano che serve perch� jquery � scemo...

function showUpload()
{
    var a = "<div id='ulDiv' class='preview'>";
    a += "<input type='file' id='nascosto'>";
    a += "<h1 id='h'>*DRAG A FILE HERE TO BEGIN UPLOAD*</h1>";
    a += "</div>";
    $('#content').append(a);
    $('#nascosto').change(function()
    {
        if (b)
            openFileDialog();
    });
    b = true;
    resizeInput();
    $(window).bind('resize', function()
    {
        resizeInput();
    });
}
function resizeInput()
{
    $('#nascosto').css('height', $('#ulDiv').css('height'));
    $('#nascosto').css('width', $('#ulDiv').css('width'));
}
function openFileDialog()
{
    if ($('#h').css('opacity') === '0')
        $('#dialog').remove();
    var a = "<div id='dialog'>";
    a += "File:<br>" + $('#nascosto').val().replace("C:\\fakepath\\", "") + "   (Click to change selected document.)";
    a += "<form id='documentDataForm'>";
    a += "<br>Title:<br><input type='text' id='title' name='title'>";
    a += "<br>Description:<br><textarea id='desc' name='description'></textarea>";
    a += "<br>Type:<br><select id='typeSelect' name='type'></select>"; //da trasformare in qualcosa di visuale!!
    a += "<br>Categories : <ul id='categoriesUl'></ul>";
    a += "<br><select id='categoriesSelect' name='categoriesSelect' onchange='addCategoryLi(this)'></select>";
    a += "<br>Tag:<ul id='tagUl'></ul>";
    a += "<br><input type='text' id='tagInput'></input>";
    a += "<input type='button' onclick='addTagLi();' value='Add' name='tagButton' autocomplete='on'></input>";
    a += "<br>Private:<br><input type='checkbox' id='private' name='isPrivate'>";
    a += "</form>";
    a += "<br><input type='button' onclick='uploadDocument();' value='Upload' name='submitButton'></input>";
    a += "<input type='button' onclick='dismissDialog();' value='Cancel' name='cancelButton'></input>";
    a += "</div>";
    $('#ulDiv').append(a);
    $('#h').css('opacity', '0');
    $('#h').css('position', 'absolute');
    $('#nascosto').css('height', '40px');
    setCategoryOptions();
    setTypeOptions();
    setTagAutocomplete();
}
function setTagAutocomplete(){
    var tags;
    $.ajax(
        {
            url: 'listTag.php',

            success: function(output)
            {
                alert(output);
                tags=$.parseJSON(output);
                 $('#tagInput').autocomplete(
                    {

                        source:tags
                    }        
                )

            }
        });
}
function addCategoryLi(p) {
    var a = "<li><span>" + p.options[p.selectedIndex].text + "</span><span onclick='kill(this)'>\t\tx </span></li>";
    $('#categoriesUl').append(a);
}
function addTagLi(){
    var a = "<li><span>" + $('#tagInput').val()+ "</span><span onclick='kill(this)'>\t\tx </span></li>";
    $('#tagUl').append(a);;
}
function kill(obj) {
    var parent = obj.parentNode;
    parent.parentNode.removeChild(parent)

}
function setCategoryOptions()
{
    for (var k in categories)
    {
        $('#categoriesSelect').append(addOption(categories[k].name, categories[k].id));
    }
}

function setTypeOptions()
{
    var types;
    $.ajax(
            {
                url: 'listDocumentType.php',
                success: function(output)
                {
                    types = $.parseJSON(output);
                    for (var k in types)
                    {
                        $('#typeSelect').append(addOption(types[k])); //type is textual
                    }
                }
            });

}

function addOption(name)
{
    return "<option value='" + name + "'>" + name + "</option>";
}
function dismissDialog()
{
    $('#dialog').remove();
    $('#h').css('opacity', '1');
    $('#h').css('position', 'relative');
    $('#nascosto').css('height', $('#ulDiv').css('height'));
}
function showStuff()
{
    $.ajax(
            {
                url: 'listDocument.php',
                type: "POST",
                data: {category: JSON.stringify(getSelectedCategories())}, // ho modificato getSelectedCategories perché category era pieno di "null" sugli elementi vuoti del vettore
                success: function(output)
                {
                    //alert(output); // da togliere, mostra il contenuto ritornato
                    documents = $.parseJSON(output);
                    $('#content').empty();
                    if (usr.edit)
                        showUpload();
                    for (var k in documents)
                    {
                        $('#content').append(addPreview(documents[k].title, documents[k].description, documents[k].extension, new Array("aaa", "bbb", "ddd")));
                    }
                }
            });
}

function addPreview(title, description, type, tags, private)
{
    r = "<div class='preview'><h3>" + title + "</h3><br><i>" + description + "</i><br>Type:" + type + "<br>Tags:";
    for (i = 0; i < tags.length; i++)
        r += tags[i] + "; ";
    r += "<br>";
    return r;
}

function uploadDocument()
{
    var data = new FormData();
    data.append('file', document.getElementById('nascosto').files[0]);
    if ($("#filename").length && $("#extension").length)
    {
        //already uploaded, need to reperform
        insertDocument();
        return;
    }
    $.ajax(
            {
                url: 'uploadDocument.php',
                data: data,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function(output)
                {
                    //alert(output); //da togliere
                    var result = $.parseJSON(output);
                    if (result.status == "true")
                    {
                        $("#private").after("<input type='hidden' id='filename' name='filename' value='" + result.filename + "'>");
                        $("#filename").after("<input type='hidden' id='extension' name='extension' value='" + result.extension + "'>");
                        insertDocument(result);
                    }
                    else
                    {
                        alert("error on uploading file");
                    }
                }
            });
}

function insertDocument()
{
    var data = $("#documentDataForm").serialize();
    //alert(data); //da togliere
    $.ajax(
            {
                url: 'insertDocument.php',
                type: 'POST',
                data: data,
                success: function(output)
                {
                    //alert(output); //da togliere
                    var result = $.parseJSON(output);
                    if (result.status == "true")
                    {
                        alert("file uploaded");
                        //close or reset upload box
                    }
                    else
                    {
                        alert(result.error);
                        //close or reset upload box
                    }
                }
            });
}



