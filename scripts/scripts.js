var shown = true;
var categories = new Array();
var usr = {edit: false};
var types= new Array();

$(document).ready(function() 
{
    main();
});

function main() 
{
    initLogin();
    updateCategories();
    initSearch();
    initYear();
    loadTypeOptions();
}
function initYear(){
    thisYear=new Date().getFullYear();
    $('#year').append(addOptionIdName(0,"-ALL YEARS-"));
    for (i=0;i<5;i++)
    {
        $('#year').append(addOption(thisYear-i)); //category is not textual!!
    }
}
function changeYear(){
    alert($("#year").val());
    showStuff();
}
function initSearch()
{
    $("#search").keypress(function(event)
                {
                   
                    if(event.keyCode === 13)
                    {
                        event.preventDefault();
                        showStuff();
                    }
                });

    $('#search').autocomplete({
                        source: function( request, response ) {
                                $.ajax({
                                  url: "listTag.php",
                                  dataType: "json",
                                  type: "POST",   
                                  data: {
                                    q: request.term,
                                    tagSearchKey: $('#search').val()
                                  },
                                  success: function( data ) {
                                    response( data["data"] );
                                  }
                                 
                                });
                              },
                        minLength: 2,
                        select: function( event, ui ) {
                            if($("#search").val().lastIndexOf(" ")>0)
                                $("#search").val($("#search").val().substring(0,$("#search").val().lastIndexOf(" ")+1)+ui.item.value);
                            else
                               $("#search").val(ui.item.value);
                            event.preventDefault();
                        },
                        focus: function( event, ui ) {
                            if($("#search").val().lastIndexOf(" ")>0)
                                $("#search").val($("#search").val().substring(0,$("#search").val().lastIndexOf(" ")+1)+ui.item.value);
                            else
                               $("#search").val(ui.item.value);
                            event.preventDefault();
                        
                        }
    });

}
function editMode()
{
    if($("#ed").attr("href")==="css/noedit.css")
    {
        if(usr.type==="ADMIN")
            $("#ed").attr("href","css/adminEdit.css");
        else
            $("#ed").attr("href","css/noAdminEdit.css");
    }
    else
    {
        $("#ed").attr("href","css/noedit.css");
    }
    showStuff(pag);
}

//UTILS
function setCookie(cname, cvalue, exdays) 
{
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) 
{
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) 
    {
        var c = ca[i].trim();
        if (c.indexOf(name) == 0)
        {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function addGreyDiv(){
    $("body").append("<div class='greyDiv'></div>");
}
function removeGreyDiv(){
    $(".greyDiv").remove();
}

