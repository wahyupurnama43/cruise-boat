/**
 * app.js
 *
 * Copyright 2016 pampersdry@gmail.com
 * License https://wrapbootstrap.com/help/licenses
 */
(function(factory) {

  'use strict';

  if (typeof define === 'function' && define.amd) {
    /** AMD. Register as an anonymous module. */
    define([], factory);
  } else if (typeof exports === 'object') {
    /** Node/CommonJS */
    module.exports = factory();
  } else {
    /** Browser globals */
    factory();
  }
}(function() {

  'use strict';

  var APP = {
    /**
     * Template sidebar sparklines
     * NOTE: require sparkline plugin
     */
    sidebarSparklines: {
      init: function() {
        $('aside .sidebar-sparklines').sparkline('html', {
          enableTagOptions: true
        });
      }
    },

    /**
     * Template header dropdown
     */
    headerDropdown: {
      init: function(options) {
        /** core dropdown function */
        function coreDropdown(e) {
          /** define variable */
          var $target = $(e.target);
          var $mediaList = $target.find('.media-list');
          var $indicator = $target.find('.indicator');

          /** show indicator */
          $indicator
            .addClass('animation animating fadeInDown')
            .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
              $(this).removeClass('animation animating fadeInDown');
            });

          /** Check for content via ajax */
          $.ajax({
            url: options.url,
            cache: false,
            type: 'POST',
            dataType: 'json'
          }).done(function(data) {
            /** define some variable */
            var template = $target.find('.mustache-template').html();
            var rendered = Mustache.render(template, data);

            /** hide indicator */
            $indicator.addClass('hide');

            /** update data total */
            $target.find('.count').html('(' + data.data.length + ')');

            /** render mustache template */
            $mediaList.prepend(rendered);

            /** add some intro animation */
            $mediaList.find('.media.new').each(function() {
              $(this)
                .addClass('animation animating flipInX')
                .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                  $(this).removeClass('animation animating flipInX');
                });
            });
          });
        }

        /** the dropdown */
        $(options.dropdown).one('shown.bs.dropdown', coreDropdown);
      }
    }
  };

  /** Init template sidebar summary */
  APP.sidebarSparklines.init();

  /** Init template message dropdown */
  APP.headerDropdown.init({
    'dropdown': '#header-dd-message',
    'url': $('body').data('baseurl') + 'api/message.php'
  });

  /** Init template notification dropdown */
  APP.headerDropdown.init({
    'dropdown': '#header-dd-notification',
    'url': $('body').data('baseurl') + 'api/notification.php'
  });

}));
