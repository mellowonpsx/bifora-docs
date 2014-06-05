b = false; //Booleano che serve perchè jquery è scemo...

function showUpload() 
{
    var a = "<div id='ulDiv' class='preview'>";
    a += "<input type='file' id='nascosto'>";
    a += "<h1 id='h'>*DRAG A FILE HERE TO BEGIN UPLOAD*</h1>";
    a += "</div>";
    $('#content').append(a);
    $('#nascosto').change(  function() 
                            {
                                if (b)
                                    openFileDialog();
                            });
    b = true;
    resizeInput();
    $(window).bind('resize',    function() 
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
    if($('#h').css('opacity')==='0')
        $('#dialog').remove();
    var a = "<div id='dialog'>";
        a += "File:<br>" + $('#nascosto').val().replace("C:\\fakepath\\","")+"   (Click to change selected document.)";
        a += "<br>Title:<br><input type='text' id='title'>";
        a += "<br>Description:<br><textarea id='desc'></textarea>";
        a += "<br>Type:<br><select id='type'></select>";
        a += "<br>Category:<br><select id='categoriesSelect'></select>";
        a += "<br>Private:<br><input type='checkbox' id='private'>";
        a += "<br><input type='button' onclick='uploadDocument();' value='Upload'></input>";
        a += "<input type='button' onclick='dismissDialog();' value='Cancel'></input>";
        a += "</div>";
    $('#ulDiv').append(a);
    
    $('#h').css('opacity', '0');
    $('#h').css('position', 'absolute');
    $('#nascosto').css('height', '40px');
    setCategoryOptions();
}
function setCategoryOptions() 
{
    for (var k in categories) 
    {
        $('#categoriesSelect').append(addCategoryOption(categories[k].name, categories[k].id));
        categories[k].selected = true;
        $('#' + categories[k].id).css("color", "white");
    }
}
function addCategoryOption(name, id) 
{
    return "<option value='" + id + "'>" + name + "</option>";
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
        data: {category: JSON.stringify(getSelectedCategories())}, // ho modificato getSelectedCategories perchÃ© category era pieno di "null" sugli elementi vuoti del vettore
        success:    function(output) 
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
    $.ajax(
    {
        url: 'loadDocument.php',
        data: data,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(data) 
        {
            alert(data);
        }
    });
}



