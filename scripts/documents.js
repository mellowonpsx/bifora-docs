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
    addGreyDiv();
    if ($('#h').css('opacity') === '0')
        $('#dialog').remove();
    var a = "<div id='dialog'>";
    a += "<div class='dialog'><div onclick='nascoClick()'>File:<br>" + $('#nascosto').val().replace("C:\\fakepath\\", "") + "   (Click to change selected document.)</div>";
    a += "<form id='documentDataForm'>";
    a += "<br>Title:<br><input type='text' id='title' name='title'>";
    a += "<br>Description:<br><textarea id='desc' name='description' maxlength=200></textarea>";
    a += "<br>Type:<br><select id='typeSelect' name='type'></select>"; //da trasformare in qualcosa di visuale!!
    a += "<br>Categories:<ul id='categoriesUl' class='killableUl'></ul>";
    a += "<br><select id='categoriesSelect' onchange='addCategoryLi(this)'></select>";
    a += "<br>Tag:<ul id='tagUl' class='killableUl'></ul>";
    a += "<br><input type='text' id='tagInput' ></input>";
    a += "<input type='button' onclick='addTagLi();' value='Add' name='tagButton' autocomplete='on'></input>";
    a += "<br>Private:<br><input type='checkbox' id='private' name='isPrivate'>";
    a += "</form>";
    a += "<br><input type='button' onclick='uploadDocument();' value='Upload' name='submitButton'></input>";
    a += "<input type='button' onclick='dismissDialog();' value='Cancel' name='cancelButton'></input>";
    a += "</div></div>";
    $('.greyDiv').append(a);
    $('#h').css('opacity', '0');
    $('#h').css('position', 'absolute');
    $('#nascosto').css('height', '40px');
    setCategoryOptions();
    setTypeOptions();
    setTagAutocomplete();
}

function nascoClick(){
    $('#nascosto').click();
}
function setTagAutocomplete(){
    $('#tagInput').autocomplete({
                        source: function( request, response ) {
                                $.ajax({
                                  url: "listTag.php",
                                  dataType: "json",
                                  type: "POST",   
                                  data: {
                                    q: request.term,
                                    tagSearchKey: $('#tagInput').val()
                                  },
                                  success: function( data ) {
                                    response( data["data"] );
                                  }
                                });
                              },
                        minLength: 2});

        $('#tagInput').keypress(function(e)
                  {
                      if(e.which === 13) 
                      {
                        addTagLi();
                      }
                  });
}
function addCategoryLi(p,cat) {
    if(cat!==undefined){
        var a = "<li class='killable'>"+"<input type='hidden' name='categoryList[]' value='" + cat.id + "'>"+"<span>" + cat.name + "</span><span onclick='kill(this)' class='killer'>\t\tx </span></li>";    
        $('#categoriesUl').append(a);
        return;
    }
        
    if(p.options[p.selectedIndex].text==="-SELECT A CATEGORY-")
        return;
    for(var i=0; i<$('#categoriesUl li').length; i++)
        if($('#categoriesUl li')[i].id===p.options[p.selectedIndex].text)return;  
    var a = "<li class='killable'>"+"<input type='hidden' name='categoryList[]' value='" + p.options[p.selectedIndex].value + "'>"+"<span>" + p.options[p.selectedIndex].text + "</span><span onclick='kill(this)' class='killer'>\t\tx </span></li>";    
    $('#categoriesUl').append(a);
}
function addTagLi(tag){
    if(tag===undefined){
        if(""===$('#tagInput').val())return;
        for(var i=0; i<$('#tagUl li').length; i++)
            if($('#tagUl li')[i].id===$('#tagInput').val())return;
        var a = "<li class='killable' id='"+$('#tagInput').val()+"'>"+"<input type='hidden' name='tagList[]' value='" + $('#tagInput').val() + "'>"+"<span>" + $('#tagInput').val()+ "</span><span onclick='kill(this)' class='killer'>\t\tx </span></li>";
        $('#tagInput').val(""); 
    }else{
        var a = "<li class='killable' id='"+tag+"'>"+"<input type='hidden' name='tagList[]' value='" + tag + "'>"+"<span>" + tag+ "</span><span onclick='kill(this)' class='killer'>\t\tx </span></li>";
    }
    $('#tagUl').append(a);

}
function kill(obj) {
    var parent = obj.parentNode;
    parent.parentNode.removeChild(parent);
}
function setCategoryOptions()
{
    $('#categoriesSelect').append(addOption("-SELECT  A CATEGORY-"));
    for (var k in categories.data)
    {
        $('#categoriesSelect').append(addOptionIdName(categories.data[k].id, categories.data[k].name)); //category is not textual!!
    }
}

function loadTypeOptions()
{
    types;
    $.ajax(
            {
                url: 'listDocumentType.php',
                
                success: function(output)
                {
                    
                    types = $.parseJSON(output).data;
                    
                }
            });

}
function setTypeOptions(){
    for (var k in types)
    {
        $('#typeSelect').append(addOption(types[k]));
    }
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
    removeGreyDiv();
}
function showStuff(a)
{
    a = typeof a !== 'undefined' ? a : 1;
    $.ajax(
            {
                url: 'listDocument.php',
                type: "POST",                                                        
                //sostituire 1 con page number, attenzione, la numerazione parte da 1 e non da 0!!!
                data: {
                        category: JSON.stringify(getSelectedCategories()), // ho modificato getSelectedCategories perché category era pieno di "null" sugli elementi vuoti del vettore
                        //se non esiste viene assunto automaticamente come 1 lato server (esistenza con isset(_POST["pageNumber"]);
                        pageNumber: a,
                        //se non esiste non deve essere settato (lato server faccio check su isset(_POST["yearLimit"]);
                        yearLimit: $("#year").val(),
                        searchQuery: $("#search").val()
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
                    if(documents.status)
                    {
                        documentsList = documents.data.documentList;
                        refreshPaginator(documents.data.numberOfDocument,documents.data.documentPerPage,a);
                        //funziona!!!!
                        //alert(documents.numberOfDocument);
                        //alert(documents.documentPerPage);
                        //alert(output);
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
                            $('#preview').append(addPreview(documentsList[k].title, documentsList[k].description, documentsList[k].type,arr,documentsList[k].isPrivate,documentsList[k].owned,documentsList[k].id));

                        }
                    }
                }
            });
}

function addPreview(title, description, type, tags, private,owned,id)
{
    r="<div class='preview'>";
    r+="<div class='"+type+"'>";
    if(owned)
        r+="<span class='killerDoc' onclick='killDoc(this)' float=right>x</span><span class='killerDoc' onclick='editDoc(this)' float=right>e</span></div>";
    r+="<div>";
    r+="<h3 id='"+id+"' onclick='documentDetail(this)' class='ubermargin'>"+ title+ "</h3>";
    r+="<br><h5 class='ubermargin'>" + description +"</h5>";
    r+="<br><h5 class='ubermargin'>Tags: ";
    for (i = 0; i < tags.length; i++)
        r += tags[i] + "; ";
    r += "</h5><br></div>";
    return r;
}
function killDoc(obj){
    var id= obj.parentNode.parentNode.childNodes[1].childNodes[0].id;
    $.ajax(
    {
        url: 'deleteDocument.php',
        type: "POST",
        data: {idDocument:id},
        success:    function(output) 
                    {  
                        
                        data=$.parseJSON(output);
                        if(confirm("Do you want to really delete the file?"))
                            $.ajax(
                            {
                                url: 'deleteDocument.php',
                                type: "POST",
                                data: {idDocument:id,eraseTempKey:data['data']},
                                success:    function(output1) 
                                            {  
                                               alert("Deleted");
                                               showStuff();
                                            }          
                            });
                        else
                            ;
                    }          
    });
    event.stopPropagation();
}
function editDoc(obj){
    var doc= obj.parentNode.parentNode.childNodes[1].childNodes[0];
    documentEdit(doc);
    event.stopPropagation();
}
function uploadDocument()
{
    if($('#nascosto').val() === '')
    {
        // se cerco di cambiare il file e poi faccio cancel,
        // mi toglie il documento, ma sopratutto svuota il form...
        // voluto o errore?
        alert("empty input file!"); //da segnalare in altro modo
        return;
    }
    
    if($('#title').val()==='')
    {
        alert("empty title!");
        return;
    }
        
    var data = new FormData();
    data.append('file', document.getElementById('nascosto').files[0]);
    
    if ($("#documentId").length)
    {
        alert("già caricato"); // da togliere
        //already uploaded, need to reperform data insertion
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
                        if(typeof result.data.documentId !== 'undefined')
                        {
                            if (!$("#documentId").length)
                            {
                                $("#private").after("<input type='hidden' id='documentId' name='documentId' value='" + result.data.documentId + "'>");
                            }
                        }
                        if ($("#private").length)
                        {
                            //filenamen non vuoto, non so quanto abbia senso questo controllo quei!
                            updateDocument();
                            showStuff();
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

function updateDocument(id)
{   $("#private").after("<input type='hidden' id='documentId' name='documentId' value='" + id + "'>");
    var data = $("#documentDataForm").serialize();
    //alert(data);
    $.ajax(
            {
                url: 'updateDocument.php',
                type: 'POST',
                data: data,
                success: function(output)
                {
                    var result = $.parseJSON(output);
                  
                    if (result.status == "true")
                    {
                        alert("file updated");
                        dismissDialog();
                        showStuff();
                    }
                    else
                    {
                        alert(result.error);
                        //close or reset upload box
                    }
                }
            });
}

function documentDetail(r){
   //var data = new FormData();
   //data.append('idDocument',r.id);
   $.ajax(
            {
                url: 'getDocumentDetail.php',
                type: 'POST',
                data: {idDocument: r.id},
                //processData: false,
                //contentType: false,
                success: function(output)
                {
                 //alert(output);
                 //return;
                 documento=$.parseJSON(output).data;
                 addGreyDiv();
                
                 //manca roba private
                 var    a = "<div id='dialog'>";
                        a += "<div class='dialog'>";
                        a += "<br>Title:<br><span>"+documento.title+"</span>";
                        a += "<br>Description:<br><span>"+documento.description+"</span>";
                        a += "<br>Type:<br><span>"+documento.type+"</span>"; //da trasformare in qualcosa di visuale!!
                        a += "<br>Categories:<br><span>";
                   
                    for(entry in documento.categories){
                        
                        a+=documento.categories[entry].name+";";
                    };
                    //manca getCategories
                    a += "<br>Tag:<br>";
                    for(entry in documento.tags){
                      
                        a+=documento.tags[entry].name+";";
                    };
                    //manca getTags
                    a += "<br><input type='button' onclick='dismissDialog();' value='Cancel' name='cancelButton'></input>";
                    a += "</div></div>";
                    $(".greyDiv").append(a);
                }
            });
}

function documentEdit(r){
   $.ajax(
            {
                url: 'getDocumentDetail.php',
                type: 'POST',
                data: {idDocument: r.id},
                success: function(output)
                {
                // alert(output);
                // return;
                documento=$.parseJSON(output).data;
                addGreyDiv();

                
                var a  = "<div id='dialog'>";
                    a += "<div class='dialog'>";
                    a += "<form id='documentDataForm'>";
                    a += "<br>Title:<br><input type='text' id='title' name='title'>";
                    a += "<br>Description:<br><textarea id='description' name='description' maxlength=200></textarea>";
                    a += "<br>Type:<br><select id='typeSelect' name='type'></select>"; //da trasformare in qualcosa di visuale!!
                    a += "<br>Categories:<ul id='categoriesUl' class='killableUl'></ul>";
                    a += "<br><select id='categoriesSelect' onchange='addCategoryLi(this)'></select>";
                    a += "<br>Tag:<ul id='tagUl' class='killableUl'></ul>";
                    a += "<br><input type='text' id='tagInput' ></input>";
                    a += "<input type='button' onclick='addTagLi();' value='Add' name='tagButton' autocomplete='on'></input>";
                    a += "<br>Private:<br><input type='checkbox' id='private' name='isPrivate'>";
                    a += "</form>";
                    a += "<br> <input type='button' onclick='updateDocument("+documento.id+");' value='Edit' name='submitButton'></input>";
                    a += "<input type='button' onclick='dismissDialog();' value='Cancel' name='cancelButton'></input>";
                    a += "</div></div>";
                    for(entry in documento.categories)
                        a+=documento.categories[entry].name+";";
                    a += "<br>Tag:<br>";
                    for(entry in documento.tags)
                        a+=documento.tags[entry].name+";";
                    a += "<br><input type='button' onclick='dismissDialog();' value='Cancel' name='cancelButton'></input>";
                    a += "</div></div>";
                    $(".greyDiv").append(a);
                    document.getElementById("title").value=documento.title;
                    document.getElementById("description").value=documento.description;
                    type=document.getElementById("typeSelect");
                    setTypeOptions();
                    setCategoryOptions();
                    setTagAutocomplete();
                    $("#typeSelect option").filter(function() {
                        return $(this).text() === documento.type; 
                    }).prop('selected', true);
                    for(c in documento.categories){
                        addCategoryLi("A",documento.categories[c]);
                    }
                    for(c in documento.tags){
                        addTagLi(documento.tags[c].name);
                    }
                    if(documento.private)
                        document.getElementById("private").value=true;
                    else
                        document.getElementById("private").value=false;
                    
                }
                
            });
            
}

