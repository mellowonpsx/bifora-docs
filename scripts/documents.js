function showUpload(){
    a="<div id='ulDiv' class='preview'>";
    a+="<h1>*DRAG A FILE HERE TO BEGIN UPLOAD*</h1>";
    a+="</div>";
    $('#content').append(a);
    $('#ulDiv').bind('drop', function (e) {
                alert();
                handleDrop(e);
            });
    showStuff();
}
function handleDrop(e) {
    e.dataTransfer = e.originalEvent.dataTransfer;
    var files = e.dataTransfer.files;

    for (var i = 0, f; f = files[i]; i++) {
        openFileDialog(f);
    }
}
function openFileDialog(f){
    a="<div class='dialog'>";
    a+="File:<br>"+f.name;
    a+="<br>Title:<br><input type='text' id='title'>";
    a+="<br>Description:<br><textarea id='desc'></textarea>";
    a+="<br>Type:<br><select id='type'></select>";
    a+="<br>Private:<br><input type='checkbox' id='private'>";
    a+="<br><input type='button' value='Upload'></input>";
    a+="<input type='button' onclick='dismissDialog();' value='Cancel'></input>";
    a+="</div>";
    
    $('#content').append(a);
}
function dismissDialog(){
  
    $('.dialog').remove();
}
/*function showStuff(){
    $('#content').append(addPreview("aaa","bbb","ccc",new Array("aaa", "bbb","ddd"),"a"));
    $('#content').append(addPreview("aaa","bbb","ccc",new Array("aaa", "bbb","ddd"),"a"));
}*/

function showStuff(){
    $.ajax({
    url  : 'listDocument.php',
    type: "POST",
    data: { category: JSON.stringify(getSelectedCategories()) }, 
    success : function(output){
                //alert(output);
                documents=$.parseJSON(output);
                $('#content').empty();
                if(usr.edit)
                    showUpload();
                for(var k in documents){
                    $('#content').append(addPreview(documents[k].title,documents[k].filename,documents[k].extension,new Array("aaa", "bbb","ddd"),"a"));
                }
            }
    });
}

    function addPreview(title,description,type,tags,private){
        r="<div class='preview'><h3>"+title+"</h3><br><i>"+description+"</i><br>Type:"+type+"<br>Tags:";
        for(i=0;i<tags.length;i++)
            r+=tags[i]+"; ";
        r+="<br>";
        return r;
    }

function uploadDocument(){
    
}



