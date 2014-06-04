
$( document ).ready(function() {
    main();
});

function main(){
    $('body').bind('drop dragover', function (event){
                event.preventDefault(); 
             });
    initLogin();
    updateCategories();
}
 
