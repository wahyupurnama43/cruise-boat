/**
 * default.js
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

  /**
   * Sparkline
   */
  $('.sparklines').sparkline('html', {
    enableTagOptions: true
  });

  /**
   * Table toolbar
   */
  (function() {
    /** globar variable within this function scope */
    var panel = '.panel#toolbar-showcase';
    var count = 0;

    /** subscribe to `selected` row event */
    $(document).on('fa.selectrow.selected', function(event, data) {
      /** define variable */
      var $panel = data.element.parents(panel);
      var $toolbar = $panel.find('#toolbar-toshow');

      /**
       * check for the count panel #toolbar-showcase only
       * as we dont want the event affect other panel/table
       */
      if ($panel.length > 0) {
        /** total the count variable */
        count = count + 1;

        /** check for the count panel #toolbar-showcase only */
        if (count === 1) {
          $toolbar.removeClass('hide');
        }
      }
    });

    /** subscribe to `unselected` row event */
    $(document).on('fa.selectrow.unselected', function(event, data) {
      /** define variable */
      var $panel = data.element.parents(panel);
      var $toolbar = $panel.find('#toolbar-toshow');

      /**
       * check for the count panel #toolbar-showcase only
       * as we dont want the event affect other panel/table
       */
      if ($panel.length > 0) {
        /** minus the count variable */
        count = count - 1;

        /** check for the count */
        if (count === 0) {
          $toolbar.addClass('hide');
        }
      }
    });
  })();

}));
