b = false; //Booleano che serve perch� jquery � scemo...
var tags;
function showUpload()
{
    var a = "<div id='ulDiv'>";
            a += "<input type='file' id='nascosto'>";
            a += "<h1 id='h'>*DRAG A FILE HERE TO BEGIN UPLOAD*</h1>";
        a += "</div>";
    $('#preview').append(a);
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
    a += "<br>Categories:<ul id='categoriesUl' class='killableUl'></ul>";
    a += "<br><select id='categoriesSelect' onchange='addCategoryLi(this)'></select>";
    a += "<br>Tag:<ul id='tagUl' class='killableUl'></ul>";
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

    $.ajax(
        {
            url: 'listTag.php',

            success: function(output)
            {
                tags=$.parseJSON(output);
                 $('#tagInput').autocomplete(
                    {
                        source:tags 
                    }        
                )

            }
        });
        $('#tagInput').keypress(function(e)
                  {
                      if(e.which === 13) 
                      {
                        addTagLi();
                      }
                  });
}
function addCategoryLi(p) {
    if(p.options[p.selectedIndex].text==="-SELECT A CATEGORY-")
        return;
    for(var i=0; i<$('#categoriesUl li').length; i++)
        if($('#categoriesUl li')[i].id===p.options[p.selectedIndex].text)return;  
    var a = "<li class='killable'>"+"<input type='hidden' name='categoryList[]' value='" + p.options[p.selectedIndex].value + "'>"+"<span>" + p.options[p.selectedIndex].text + "</span><span onclick='kill(this)' class='killer'>\t\tx </span></li>";    
    $('#categoriesUl').append(a);
}
function addTagLi(){
    if(""===$('#tagInput').val())return;
    for(var i=0; i<$('#tagUl li').length; i++)
        if($('#tagUl li')[i].id===$('#tagInput').val())return;
    var n="<span>\t(New)</span>";
    for(var k in tags){
        if(tags[k]===$('#tagInput').val())
            n="";
    }
    var a = "<li class='killable' id='"+$('#tagInput').val()+"'>"+"<input type='hidden' name='tagList[]' value='" + $('#tagInput').val() + "'>"+"<span>" + $('#tagInput').val()+n+ "</span><span onclick='kill(this)' class='killer'>\t\tx </span></li>";
    $('#tagUl').append(a);
    $('#tagInput').val(""); 
}
function kill(obj) {
    var parent = obj.parentNode;
    parent.parentNode.removeChild(parent);
}
function setCategoryOptions()
{
    $('#categoriesSelect').append(addOption("-SELECT  A CATEGORY-"));
    for (var k in categories)
    {
        $('#categoriesSelect').append(addOptionIdName(categories[k].id, categories[k].name)); //category is not textual!!
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

function addOptionIdName(id, name)
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
                //sostituire 1 con page number, attenzione, la numerazione parte da 1 e non da 0!!!
                data: {
                        category: JSON.stringify(getSelectedCategories()), // ho modificato getSelectedCategories perché category era pieno di "null" sugli elementi vuoti del vettore
                        //se non esiste viene assunto automaticamente come 1 lato server (esistenza con isset(_POST["pageNumber"]);
                        pageNumber: 1,
                        //se non esiste non deve essere settato (lato server faccio check su isset(_POST["yearLimit"]);
                        yearLimit: 2012,
                        searchQuery: ""
                        //la ricerca estrae i record contenenti TUTTE le keyword in (titolo oppure descrizione oppure tag collegati)
                        //può anche essere che una keyword appartenga al titolo, una alla descrizione ed una al tag collegato).
                        //searchQuery: "filmato spada" //funziona
                        //searchQuery: "film spa" //funziona
                        //searchQuery: "lmat ad" //funziona
                        //searchQuery: "filmato" //funziona
                        //searchQuery: "spada osè" //non funziona, credo per l'accentata
                        //prove con i tag
                        //searchQuery: "primo" //funziona
                        //searchQuery: "prova26" //funziona
                        //searchQuery: "secondo" //funziona
                        //misto tag, parole
                        //searchQuery: "primo quantistica trattato fisica" //funziona
                        //searchQuery: "pri quantistica trattato fisica" //funziona
                      },
                success: function(output)
                {
                    //da togliere alternativo ad alert
                    //$('#preview').empty();
                    //$('#preview').append(output);
                    //return;
                    //alert(output); // da togliere, mostra il contenuto ritornato
                    documents = $.parseJSON(output);
                    documentsList = documents.documentList;
                    //funziona!!!!
                    //alert(documents.numberOfDocument);
                    //alert(documents.documentPerPage);
                    $('#preview').empty();
                    if ($("#ed").attr("href")!=="css/noedit.css"){
                        showUpload();
                    }
                    for (var k in documentsList)
                    {
                        var arr=new Array();
                        for(var h in documentsList[k].tags){
                            arr.push(documentsList[k].tags[h].name);
                        }
                        $('#preview').append(addPreview(documentsList[k].title, documentsList[k].description, documentsList[k].extension,arr));
                
                    }
                }
            });
}

function addPreview(title, description, type, tags, private)
{
    r = "<div class='preview'><h3>" + title + "</h3><br><i>" + description + "</i><br>Type:" + type + "<br>Tags: ";
    for (i = 0; i < tags.length; i++)
        r += tags[i] + "; ";
    r += "<br>";
    return r;
}

function uploadDocument()
{
    if($('#nascosto').val() == '')
    {
        // se cerco di cambiare il file e poi faccio cancel,
        // mi toglie il documento, ma sopratutto svuota il form...
        // voluto o errore?
        alert("empty input file"); //da segnalare in altro modo
        return;
    }
    
    var data = new FormData();
    data.append('file', document.getElementById('nascosto').files[0]);
    
    if ($("#idDocument").length)
    {
        //already uploaded, need to reperform
        updateDocument();
        return;
    }
    if ($("#categoriesUl li").length===0){
        alert("You must select at least one category!");
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
                        /*$("#private").after("<input type='hidden' id='filename' name='filename' value='" + result.filename + "'>");
                        $("#filename").after("<input type='hidden' id='extension' name='extension' value='" + result.extension + "'>");*/
                        if(typeof result.id !== 'undefined')
                        {
                            if (!$("#documentId").length)
                            {
                                $("#private").after("<input type='hidden' id='documentId' name='documentId' value='" + result.id + "'>");
                            }
                        }
                        if ($("#private").length)
                        {
                            updateDocument();
                            return;
                        }
                        else
                        {
                             alert("empty input file");
                        }
                    }
                    else
                    {
                        alert("error on uploading file");
                    }
                }
            });
}

function updateDocument()
{
    var data = $("#documentDataForm").serialize();
    //alert(data);
    $.ajax(
            {
                url: 'updateDocument.php',
                type: 'POST',
                data: data,
                success: function(output)
                {
                    alert(output); //da togliere
                    var result = $.parseJSON(output);
                    if (result.status == "true")
                    {
                        alert("file updated");
                        dismissDialog();
                    }
                    else
                    {
                        alert(result.error);
                        //close or reset upload box
                    }
                }
            });
}



