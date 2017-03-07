# Aescarcha UserBundle
## Introduction

This bundle is a wrapper for FOS User Bundle and HWI oauth, to provide entities, repositories and Facebook support
It also provides a REST Api for users.

REST API requires fractal and FosRestBundle

## Install
    composer require aescarcha/user-bundle

#### Configure the service

##### config.yml


    fos_user:
        db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
        firewall_name: main
        user_class: Aescarcha\UserBundle\Entity\User
        profile:
            form:
                type: app_user_profile

    hwi_oauth:
        connect:
            account_connector: my_user_provider
        # name of the firewall in which this bundle is active, this setting MUST be set
        firewall_name: secured_area
        fosub:
            username_iterations: 30
            properties:
                # these properties will be used/redefined later in the custom FOSUBUserProvider service.
                facebook: facebookId
        resource_owners:
            facebook:
                type:                facebook
                client_id:           Your-client-id
                client_secret:       your-client-secret
                infos_url:           "https://graph.facebook.com/me?fields=id,name,email,picture.type(square),birthday,locale,location,gender,first_name,last_name,link,timezone,verified"
                scope:               "email user_friends user_photos user_videos user_location user_about_me user_birthday basic_info"
                paths:
                    locale:     locale
                    birthday:   birthday
                    location:   location.name     
                    profilepicture: picture.data.url


##### services.yml

    fos_user.doctrine_registry:
        alias: doctrine
    my_user_provider:
        class: "%my_user_provider.class%"
        #this is the place where the properties are passed to the UserProvider - see config.yml
        arguments: [@fos_user.user_manager,{facebook: facebookId}]


##### AppKernel.php
        $bundles = array(
            new Aescarcha\UserBundle\AescarchaUserBundle(),
        );


## Tests
Tests are provided on the repo, but they're not working because the test requires some Entities and Repositories to work, making them work in a clean symfony install is also a TODO

## TODOs:
Remove not-needed stuff