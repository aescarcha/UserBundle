var docReady = $.Deferred();
var facebookReady = $.Deferred();
var facebookConnected = $.Deferred();
var $items;
var $list
var fbAccessToken = '';

$(document).ready(docReady.resolve);

window.fbAsyncInit = function() {
    FB.init({
      appId      : 'TheAppId',
      status     : true,
      cookie     : true,
      xfbml      : true,
      version    : 'v2.3'
  });
    facebookReady.resolve();
    checkConnect();
};

(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


function makeFacebookPhotoURL( id, fbAccessToken ) {
    return 'https://graph.facebook.com/' + id + '/picture?access_token=' + fbAccessToken;
}

function checkConnect()
{
    FB.getLoginStatus(function(response) {
        if ( response.status === 'connected') {
            var uid = response.authResponse.userID;
            fbAccessToken = response.authResponse.accessToken;
            facebookConnected.resolve();
        } else if (response.status === 'not_authorized') {
            //show login box or no fb photos
        } else {
        }
});
}

function login( callback ) {
    FB.login(function(response) {
        if (response.authResponse) {
                    //console.log('Welcome!  Fetching your information.... ');
                    if (callback) {
                        callback(response);
                    }
                } else {
                    console.log('User cancelled login or did not fully authorize.');
                }
            },{scope: 'user_photos, user_location'} );
}

function getAlbums( callback ) {
    FB.api(
           '/me/albums',
           {fields: 'id,cover_photo'},
           function(albumResponse) {
                        //console.log( ' got albums ' );
                        if (callback) {
                            callback(albumResponse);
                        }
                    }
                    );

}

function getPhotosForAlbumId( albumId, callback ) {
    FB.api(
           '/'+albumId+'/photos',
           {fields: 'id'},
           function(albumPhotosResponse) {
                        //console.log( ' got photos for album ' + albumId );
                        if (callback) {
                            callback( albumId, albumPhotosResponse );
                        }
                    }
                    );
}

function getLikesForPhotoId( photoId, callback ) {
    FB.api(
           '/'+albumId+'/photos/'+photoId+'/likes',
           {},
           function(photoLikesResponse) {
            if (callback) {
                callback( photoId, photoLikesResponse );
            }
        }
        );
}

function getFriends( callback ) {
    FB.api(
           '/me/friends',
           {fields: 'picture, name'},
           function(response) {
            if (callback) {
                callback(response);
            }
        }
        );

}

function getPhotos(callback) {

    var allPhotos = [];

    login(function(loginResponse) {
        fbAccessToken = loginResponse.authResponse.accessToken || '';
        getAlbums(function(albumResponse) {
            var i, album, deferreds = {}, listOfDeferreds = [];

            for (i = 0; i < albumResponse.data.length; i++) {
                album = albumResponse.data[i];
                deferreds[album.id] = $.Deferred();
                listOfDeferreds.push( deferreds[album.id] );
                getPhotosForAlbumId( album.id, function( albumId, albumPhotosResponse ) {
                    var i, facebookPhoto;
                    for (i = 0; i < albumPhotosResponse.data.length; i++) {
                        facebookPhoto = albumPhotosResponse.data[i];
                        allPhotos.push({
                            'id'    :   facebookPhoto.id,
                            'added' :   facebookPhoto.created_time,
                            'url'   :   makeFacebookPhotoURL( facebookPhoto.id, fbAccessToken )
                        });
                    }
                    deferreds[albumId].resolve();
                });
            }

            $.when.apply($, listOfDeferreds ).then( function() {
                if (callback) {
                    callback( allPhotos );
                }
            }, function( error ) {
                if (callback) {
                    callback( allPhotos, error );
                }
            });
        });
});
}

/**
 * Gets videos from fb and executes callback
 * Two types are allowed, uploaded and tagged
 */
function getVideos(callback) {

    var allVideos = [];

    login(function(loginResponse) {
        fbAccessToken = loginResponse.authResponse.accessToken || '';
        FB.api(
               '/me/videos?type=uploaded',
               function(response) {
                        if (callback) {
                            callback(response.data);
                        }
                    });
});
}

/**
 * Buils a bootstrap modal selector with the given photos
 * And element with id facebook-photo-modal must be given
 * @param  array photos 
 */
function buildPhotosModal( photos, title, callback )
{
    var $modal = $('#facebook-photo-modal');
    $.each(photos, function(index, photo){
        $modal.find('.container-fluid').append('<div class="col-md-6 col-lg-4"><a class="gallery-img" data-id="'+ photo.id +'" href="#"><img data-id="'+ photo.id +'" class="img-responsive center-block" src="' + photo.url + '" /></a></div>')
    });
    $modal.on('shown.bs.modal', function (e) {
        $modal.find('.loading').fadeOut(100);
        if(!photos || photos.length == 0){
            $modal.find('.default').removeClass('hidden').fadeIn(100);
        }
        setHeightsWrap( '.modal .container-fluid', '.img-responsive');
        if(typeof(callback) === 'function'){
            callback();
        }
    });
    $modal.modal();

}

/**
 * Buils a bootstrap modal selector with the given videos
 * Yes, this is refactor meat
 * @param  array videos
 */
function buildVideosModal( videos, title, callback )
{
    var $modal = $('#facebook-video-modal');
    $.each(videos, function(index, video){
        $modal.find('.container-fluid').append('<div class="col-md-6 col-lg-4"><a class="gallery-video" href="#"><img data-id="'+ video.id +'" class="img-responsive center-block" data-source="' + video.source + '" src="' + video.picture + '" /></a></div>')
    });
    $modal.on('shown.bs.modal', function (e) {
        $modal.find('.loading').fadeOut(100);
        if(!videos || videos.length == 0){
            $modal.find('.default').removeClass('hidden').fadeIn(100);
        }
        setHeightsWrap( '.modal .container-fluid', '.img-responsive');
        if(typeof(callback) === 'function'){
            callback();
        }
    });
    $modal.modal();

}

/**
 * Buils a bootstrap modal selector with the given friends, and add the invite more by send-dialog
 * @param  array friends
 */
function buildFriendsModal( friends, title, fbButton, callback )
{
    modalHtml = '<div class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>                   <h4 class="modal-title" id="gridSystemModalLabel">' + title + '</h4></div><div class="modal-body"><div class="container-fluid"></div></div></div>';
    var $modal = $(modalHtml);
    $.each(friends.data, function(index, user){
        $modal.find('.container-fluid').append('<div class="col-md-6 col-lg-4 friend-container"><a class="friend" data-id="'+ user.id +'" href="#"><div class="col-sm-2 col-md-3 col-xs-3"><img data-id="'+ user.id +'" class="media-object img-circle img-responsive center-block" data-source="' + user.picture.data.url + '" src="' + user.picture.data.url + '" /></div><div class="col-md-9 col-sm-10 col-xs-9">'+ user.name +'</div></a></div>')
    });

    $modal.find('.container-fluid').append('<div class="clearfix"></div><div class="col-md-4 col-lg-3 intro-top"><a class="btn btn-social btn-facebook fb-send-dialog"><i class="fa fa-facebook"></i>' + fbButton + '</a></div>')

    $modal.on('shown.bs.modal', function (e) {
        if(typeof(callback) === 'function'){
            callback();
        }
    });
    $modal.modal();
}

function getAlbum(albumName, callback)
{
    FB.api(
            "/me/albums",
            {fields: 'id,cover_photo,name'},
            function (response) {
            if (!response || response.error) {
                createAlbum(albumName, callback);
            }
            else {
                var desiredAlbum;
                $.each(response.data, function(index, album){
                    if(album.name == albumName){
                        desiredAlbum = album;
                        return;
                    }
                });
                if(desiredAlbum){
                    callback(desiredAlbum);
                } else {
                    createAlbum(albumName, callback);
                }
            }
        });
}

function createAlbum(albumName, callback)
{
    FB.api(
           "/me/albums",
           "POST",
           {
            "name": albumName,
            "message": "[Test Message]"
        }, function(response){
            callback(response);
        });
}

function uploadPhotoToAlbum( src, album, callback )
{
    getAlbum( album, function(response){
        var albumID = response.id;
        FB.api(
               "/" + albumID + "/photos",
               "POST",
               {
                message: "Test message",
                url: src,
                no_story: true
            },
            function (response) {
                if (!response || response.error) {
                    //error uploading
                    console.log('Error uploading');
                } else {
                    //uploaded OK
                    console.log(response);
                    callback( response );
                }
        }
        );
    });
}

function uploadVideoToFanpage( src, callback )
{
    return uploadToFanpage( src, 2, callback );
}

function uploadVideoFileToFanpage( form, callback )
{
    return uploadFileToFanpage( form, callback );
}

function uploadPhotoToFanpage( src, callback )
{
    return uploadToFanpage( src, 1, callback );
}

function uploadPhotoFileToFanpage( form, callback )
{
    return uploadFileToFanpage( form, callback );
}

function uploadToFanpage( src, type, callback )
{
    $.ajax({
        type: "POST",
        url: "/challenge/upload-to-fanpage",
        data: {'src' : src, 'type' : type},
        dataType: 'json'
    }).done(function(response){
        callback(response);
    });
}

function uploadFileToFanpage( form, callback )
{
    var formData = new FormData($(form)[0]);

    $.ajax({
        type: "POST",
        url: "/challenge/upload-to-fanpage",
        data: formData,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false
    }).done(function(response){
        callback(response);
    });
}

function uploadVideoToAlbum( src, album, callback )
{
    getAlbum( album, function(response){
        var albumID = response.id;
        FB.api(
               "/me/videos",
               "POST",
               {
                file_url: src,
            },
            function (response) {
                if (!response || response.error) {
                    //error uploading
                    console.log(response);
                    console.log('Error uploading');
                } else {
                    //uploaded OK
                    console.log(response);
                    callback( response );
                }
        }
        );
    });
}

function setHeightsWrap(list, items)
{
    $list       = $( list );
    $items      = $list.find( items );
    setHeights();
    $( window ).on( 'resize', setHeights );
    $list.find( 'img' ).on( 'load', setHeights );
}

function setHeights()
{
    $items.css( 'height', 'auto' );

    var perRow = Math.floor( $list.width() / $items.width() );
    if( perRow == null || perRow < 2 ) return true;
    for( var i = 0, j = $items.length; i < j; i += perRow )
    {
        var maxHeight   = 0,
        $row        = $items.slice( i, i + perRow );

        $row.each( function()
        {
            var itemHeight = parseInt( $( this ).outerHeight() );
            if ( itemHeight > maxHeight ) maxHeight = itemHeight;
        });
        $row.css( 'height', maxHeight );
    }
}
