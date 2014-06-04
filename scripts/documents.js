b=false;
function showUpload(){

    a="<div id='ulDiv' class='preview'>";
    a+="<input type='file' id='nascosto'>";
    a+="<h1 id='h'>*DRAG A FILE HERE TO BEGIN UPLOAD*</h1>";
    a+="</div>";
    $('#content').append(a); 
    $('#nascosto').change(
            function(){
                if (b)
                    openFileDialog();
            });
       
    b=true;
    $('#ulDiv').css('position','relative');
    $('#nascosto').css('opacity','0');
    $('#nascosto').css('position','absolute');
    $('#nascosto').css('z-index','20');
    $('#nascosto').css('height',$('#ulDiv').css('height'));
    $('#nascosto').css('width',$('#ulDiv').css('width'));

    $(window).bind('resize',function(){
           $('#nascosto').css('height',$('#ulDiv').css('height'));
           $('#nascosto').css('width',$('#ulDiv').css('width')); 
    });
}

function openFileDialog(){
    a="<div id='dialog'>";
    a+="File:<br>"+$('#nascosto').val();
    a+="<br>Title:<br><input type='text' id='title'>";
    a+="<br>Description:<br><textarea id='desc'></textarea>";
    a+="<br>Type:<br><select id='type'></select>";
    a+="<br>Private:<br><input type='checkbox' id='private'>";
    a+="<br><input type='button' onclick='uploadDocument();' value='Upload'></input>";
    a+="<input type='button' onclick='dismissDialog();' value='Cancel'></input>";
    a+="</div>";
    $('#ulDiv').append(a);
    $('#h').css('opacity','0');
    $('#h').css('position','absolute');
    $('#nascosto').css('height','0px');
    $('#nascosto').css('width','0px');
}
function dismissDialog(){
    $('#dialog').remove();
    $('#h').css('opacity','1');
    $('#h').css('position','relative');
    $('#nascosto').css('height',$('#ulDiv').css('height'));
    $('#nascosto').css('width',$('#ulDiv').css('width')); 
}
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
    var data=new FormData();
    data.append('file',document.getElementById('nascosto').files[0]);
    $.ajax({
        url: 'loadDocument.php',
        data: data,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function ( data ) {
            alert( data );
        }
    });

}



