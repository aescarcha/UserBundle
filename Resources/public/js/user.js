$(document).ready(function(){
    var loggedUser;
    if (typeof jsonUser !== 'undefined' && jsonUser.length > 0) {
        loggedUser = $.parseJSON(jsonUser);
        if(loggedUser.id){
            loadUserData(loggedUser);
            hideAndShow(loggedUser);
        }
    } else {
        getUser();
    }
    $( document ).ajaxComplete(function() {
        if (typeof loggedUser !== 'undefined' ) {
            hideAndShow(loggedUser);
        }
    });
});

function getUser()
{
    $.getJSON( '/logged-user', function( data ){
        if(data.id){
            loggedUser = data;
            loadUserData(data);
            hideAndShow(loggedUser);
        }
    });
}

function loadUserData(data)
{
    $('.logged-user-avatar').attr('src', data.profilePicture);
    $('.logged-user-profile').each(function() {
        /* Change logged user profile hrefs to use the own profile */
        var lastChar = $(this).attr("href").slice( -1 ); 
        if(lastChar >= '0' && lastChar <= '9'){
            /* Last char is a number, so it's already an id */
            return;
        }
        if(lastChar == "/"){
            $(this).attr("href", $(this).attr("href") + data.id);
        } else {
            $(this).attr("href", $(this).attr("href") + '/' + data.id);
        }
    });
}

function hideAndShow(data)
{
    if( data.id ){
        $('.user-action.all-user:not(.hidden-user-'+ data.id +')').removeClass('user-action');
        $('.user-action.user-' + data.id).removeClass('user-action');
    }
}