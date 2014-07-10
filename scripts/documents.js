b = false;
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
        if (b)openFileDialog();
    });
    b = true;

}

function openFileDialog()
{
    addGreyDiv();
    var a = "<div id='dialog' >";
    a += "<div class='dialog'><div onclick='nascoClick()'>File:<br>" + $('#nascosto').val().replace("C:\\fakepath\\", "") + "   (Click to change selected document.)</div>";
    a += "<form id='documentDataForm'>";
    a += "<br><label class='ubermargin' for='title'>Title:</label><br><input type='text' id='title' name='title'>";
    a += "<br><label class='ubermargin' for='desc'>Description:</label><br><textarea cols='40' rows='5' id='desc' name='description' maxlength=1000></textarea>";
    a += "<br><label class='ubermargin' for='typeSelect'>Type:</label><select id='typeSelect' name='type'></select>"; //da trasformare in qualcosa di visuale!!
    a += "<label for='private' class='ubermargin'>\t\tPrivate:</label><input type='checkbox' id='private' name='isPrivate'>";
    a += "<br><label for='categoriesSelect' class='ubermargin'>Categories:</label><select id='categoriesSelect' onchange='addCategoryLi(this)'></select>";
    a += "<br><ul id='categoriesUl' class='killableUl'></ul>";
    a += "<br><label for='tagInput' class='ubermargin'>Tag:</label><input type='text' id='tagInput' ></input>";
    a += "<br><ul id='tagUl' class='killableUl'></ul>";
   
    a += "</form>";
    a += "<br><input type='button' onclick='uploadDocument();' value='Upload' name='submitButton'></input>";
    a += "<input type='button' onclick='dismissDialog();' value='Cancel' name='cancelButton'></input>";
    a += "</div></div>";
    $('.greyDiv').append(a);
    setCategoryOptions();
    setTypeOptions();
    setTagAutocomplete();
    $(".dialog").on("click",function(event){
                            event.stopPropagation();
                        });
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
                        minLength: 2,
                        select: function( event, ui ) {

                             addTagLi();
                             event.preventDefault();
                             
                        }});

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
        var a = "<li class='killable' id='"+cat.name+"'>"+"<input type='hidden' name='categoryList[]' value='" + cat.id + "'>"+"<span>" + cat.name + "</span><span onclick='kill(this)' class='killer'>\t\tx </span></li>";    
        $('#categoriesUl').append(a);
        return;
    }
        
    if(p.options[p.selectedIndex].text==="-SELECT A CATEGORY-")
        return;
    for(var i=0; i<$('#categoriesUl li').length; i++)
    {
        //alert(p.options[p.selectedIndex].text);
        if($('#categoriesUl li')[i].id===p.options[p.selectedIndex].text) return;
    }
    var a = "<li class='killable' id='"+p.options[p.selectedIndex].text+"'>"+"<input type='hidden' name='categoryList[]' value='" + p.options[p.selectedIndex].value + "'>"+"<span>" + p.options[p.selectedIndex].text + "</span><span onclick='kill(this)' class='killer'>\t\tx </span></li>";    
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
    removeGreyDiv();
}
function showStuff(a)
{
    a = typeof a !== 'undefined' ? a : 1;
    $.ajax(
            {
                url: 'listDocument.php',
                type: "POST",                                                        
                data: {
                        category: JSON.stringify(getSelectedCategories()),
                        pageNumber: a,
                        yearLimit: $("#year").val(),
                        searchQuery: $("#search").val()
                       
                      },
                success: function(output)
                {

                    documents = $.parseJSON(output);
                    //alert(output);
                    if(documents.status)
                    {
                        documentsList = documents.data.documentList;

   
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
                            if(usr.type==="ADMIN")
                                title=documentsList[k].title+" - <i>"+documentsList[k].ownerName+"</i>";
                            else
                                title=documentsList[k].title;
                            addPreview(title, documentsList[k].description, documentsList[k].type,arr,documentsList[k].isPrivate,documentsList[k].owned,documentsList[k].id);

                        }
                        if(documents.data.numberOfDocument==0)
                            $('#preview').append("<h3>No results found!</h5>");
                        else
                            $('#preview').append("<div id='paginator'></div>");
                        refreshPaginator(documents.data.numberOfDocument,documents.data.documentPerPage,a);
                    }
                    

                }
                
            });
            
    $('html, body').animate({
            scrollTop: 0
        }, 500);
}

function addPreview(title, description, type, tags, private,owned,id)
{
    classi=" ";
    
    if(owned&&usr.type!="ADMIN")
       classi+="owned ";
    if(private==1)
         classi+="private";
    
    r ="<div>";
    r+="<div class='preview"+classi+"' onclick='documentDetail("+id+")'>";
    r+="<div class='"+type+" block-left'>";
    r+="</div>";
    r+="<div class='block-left'>";
    r+="<h3 id='"+id+"'  class='ubermargin hand'>";
    
    r+= title+ "</h3>";
    r+="<br><h5 class='ubermargin'>" 
    r+= description.substring(0,Math.min(description.length,140));
    if(description.length>140)
        r+=" [...]";
    r+="</h5>";
    r+="<br><h5 class='ubermargin'>Tags: ";
    for (i = 0; i < tags.length; i++)
        r += tags[i] + "; ";
    r += "</h5><br></div></div>";
    r+="<div class='block-right'><a href='http://localhost/bifora-docs/downloadDocument.php?idDocument="+id+"'><img src='css/img/download.png' class='right hand dl'></img></a>";
    if(owned)
        r+="<img src='css/img/delete.png' class='killerDoc' onclick='killDoc(this)' float=right></img><img src='css/img/edit.png' class='killerDoc' onclick='editDoc(this)' float=right></img>";
     
    r+="</div></div>";
    $('#preview').append(r);
}
function killDoc(obj,a){
    if(typeof a !== 'undefined')
        id=a;
    else
        id= obj.parentNode.parentNode.childNodes[0].childNodes[1].childNodes[0].id;
    $.ajax(
    {
        url: 'deleteDocument.php',
        type: "POST",
        data: {idDocument:id},
        success:    function(output) 
                    {  
                        data=$.parseJSON(output);
                        if(data.status==="false")
                        {
                            alert(data.error);
                        }
                        else
                        {
                            if(confirm("Do you want to really delete the file?"))
                            {
                                $.ajax(
                                {
                                    url: 'deleteDocument.php',
                                    type: "POST",
                                    data: {idDocument:id,eraseTempKey:data['data']},
                                    success:    function(output1) 
                                                {
                                                    out=$.parseJSON(output1);
                                                    if(out.status==="false")
                                                    {
                                                        alert(out.error);
                                                    }
                                                    showStuff();
                                                }          
                                });
                            }
                        }
                    }          
    });
    event.stopPropagation();
}
function editDoc(obj,a){
     if(typeof a !== 'undefined') {
        id=a;
        dismissDialog();
    }
    else
        id= obj.parentNode.parentNode.childNodes[0].childNodes[1].childNodes[0].id;
    documentEdit(id);
    event.stopPropagation();
}

function uploadDocument()
{
    if($('#nascosto').val() === '')
    {
        alert("empty input file!"); 
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
                    var result = $.parseJSON(output);
                    if (result.status === "false")
                    {
                        alert(result.error);
                    }
                    else
                    {
                        if(typeof result.data.documentId !== 'undefined') //if inutile?
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
                        //alert("file updated"); //se è tutto a posto è inutile dirlo all'utente
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
                data: {idDocument: r},
                //processData: false,
                //contentType: false,
                success: function(output)
                {

                 //return;
                 documento=$.parseJSON(output);
                if(documento.status==="false")
                {
                    alert(documento.error);
                    showStuff();
                    return;
                }
                documento=documento.data;
                addGreyDiv();
                
                 //manca roba private
                       
                        a = "<div class='dialog'>";
                        a += "<div class='"+documento.type+" small block-left'></div>";
                        a += "<br><h3 class='ubermargin'>"+documento.title;
                        if(usr.type==="ADMIN")
                            a+=" - <i>"+documento.ownerName+"</i>";
                        a +="</h3>";
                        a += "<br><br><i>"+documento.description+"</i>";
                        a += "<br><br><b>Categories:\t</b><span>";
                        
                   
                    for(entry in documento.categories){
                        
                        a+="["+documento.categories[entry].name+"] ";
                    };
                    a+="</span>";
                    //manca getCategories
                    a += "<br><br><b>Tag:\t</b><span>";
                    for(entry in documento.tags){
                      
                        a+="#"+documento.tags[entry].name+" ";
                    };
                    a+="</span>";
                    //manca getTags
                    //a += "<br><input type='button' onclick='dismissDialog();' value='Cancel' name='cancelButton'></input>";
                    a +="<div class='block-right'><a href='http://localhost/bifora-docs/downloadDocument.php?idDocument="+documento.id+"'><img src='css/img/download.png' class='right hand'></img></a>";
                    if(documento.owned)
                       a+="<img src='css/img/delete.png' class='right hand' onclick='killDoc(this,"+documento.id+")' float=right></img><img src='css/img/edit.png' class='right hand' onclick='editDoc(this,"+documento.id+")' float=right></img>";
                    a+="</div>";
                    a += "</div>";
                    
                    $(".greyDiv").append(a);
                    $(".dialog").on("click",function(event){
                            event.stopPropagation();
                        });
                   
                }
            });
}

function documentEdit(r){
   $.ajax(
            {
                url: 'getDocumentDetail.php',
                type: 'POST',
                data: {idDocument: r},
                success: function(output)
                {
                // alert(output);
                // return;
                documento=$.parseJSON(output);
                if(documento.status==="false")
                {
                    alert(documento.error);
                    showStuff();
                    return;
                }
                documento=documento.data;
                addGreyDiv();
                
                    a = "<div class='dialog'>";
                    a += "<form id='documentDataForm'>";
                    a += "<form id='documentDataForm'>";
                    a += "<br><label class='ubermargin' for='title'>Title:</label><br><input type='text' id='title' name='title'>";
                    if(usr.type==="ADMIN")
                        a += "<br><label class='ubermargin'>Owned by: "+documento.ownerName+"</label>";
                    a += "<br><label class='ubermargin' for='desc'>Description:</label><br><textarea cols='40' rows='5' id='desc' name='description' maxlength=1000></textarea>";
                    a += "<div class='inline'><br><label class='ubermargin' for='typeSelect'>Type:</label><select id='typeSelect' name='type'></select>"; //da trasformare in qualcosa di visuale!!
                    a += "<label for='private' class='ubermargin'>\t\tPrivate:</label><input type='checkbox' id='private' name='isPrivate'>";
                    a += "<br><label for='categoriesSelect' class='inline ubermargin'>Categories:</label><select id='categoriesSelect' class='inline' onchange='addCategoryLi(this)'></select>";
                    a += "<ul id='categoriesUl' class='killableUl'></ul>";
                    a += "<br><label for='tagInput' class='inline ubermargin'>Tag:</label><input type='text' class='inline' id='tagInput' ></input>";
                    a += "<ul id='tagUl' class='killableUl'></ul></div>";

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
                    a += "</div>";
                    $(".greyDiv").append(a);
                    $(".dialog").on("click",function(event){
                            event.stopPropagation();
                        });
                    document.getElementById("title").value=documento.title;
                    document.getElementById("desc").value=documento.description;
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
                    if(documento.isPrivate==1){
                        document.getElementById("private").checked=true;
                        document.getElementById("private").value=true;
                    }
                    else{
                        document.getElementById("private").checked=false;
                        document.getElementById("private").value=false;
                    }

                }
                
            });
            
}

