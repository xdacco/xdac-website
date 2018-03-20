(function($) {
    'use strict';

    var userFeed = new Instafeed({
        get: 'user',
        userId: '623597756',
        clientId: '02b47e1b98ce4f04adc271ffbd26611d',
        accessToken: '623597756.02b47e1.3dbf3cb6dc3f4dccbc5b1b5ae8c74a72',
        resolution: 'standard_resolution',
        template: '<a href="{{link}}" target="_blank" id="{{id}}"><img src="{{image}}" /></a>',
        sortBy: 'most-recent',
        limit: 9,
        links: false
    });
    userFeed.run();

})(jQuery);
