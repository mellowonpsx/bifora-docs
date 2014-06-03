
$( document ).ready(function() {
    main();
});

function main(){
    $(document).bind('drop dragover', function (e){
                e.preventDefault();
             });
    showStuff();
    initLogin();
    updateCategories();
}
 
