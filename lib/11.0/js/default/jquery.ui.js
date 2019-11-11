/**
 * jCarouselLite - jQuery plugin to navigate images/any content in a carousel style widget.
 * @requires jQuery v1.2 or above
 *
 * http://gmarwaha.com/jquery/jcarousellite/
 *
 * Copyright (c) 2007 Ganeshji Marwaha (gmarwaha.com)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Version: 1.0.1
 * Note: Requires jquery 1.2 or above from version 1.0.1
 */

/**
 * Creates a carousel-style navigation widget for images/any-content from a simple HTML markup.
 *
 * The HTML markup that is used to build the carousel can be as simple as...
 *
 *  <div class="carousel">
 *      <ul>
 *          <li><img src="image/1.jpg" alt="1"></li>
 *          <li><img src="image/2.jpg" alt="2"></li>
 *          <li><img src="image/3.jpg" alt="3"></li>
 *      </ul>
 *  </div>
 *
 * As you can see, this snippet is nothing but a simple div containing an unordered list of images.
 * You don't need any special "class" attribute, or a special "css" file for this plugin.
 * I am using a class attribute just for the sake of explanation here.
 *
 * To navigate the elements of the carousel, you need some kind of navigation buttons.
 * For example, you will need a "previous" button to go backward, and a "next" button to go forward.
 * This need not be part of the carousel "div" itself. It can be any element in your page.
 * Lets assume that the following elements in your document can be used as next, and prev buttons...
 *
 * <button class="prev">&lt;&lt;</button>
 * <button class="next">&gt;&gt;</button>
 *
 * Now, all you need to do is call the carousel component on the div element that represents it, and pass in the
 * navigation buttons as options.
 *
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev"
 * });
 *
 * That's it, you would have now converted your raw div, into a magnificient carousel.
 *
 * There are quite a few other options that you can use to customize it though.
 * Each will be explained with an example below.
 *
 * @param an options object - You can specify all the options shown below as an options object param.
 *
 * @option btnPrev, btnNext : string - no defaults
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev"
 * });
 * @desc Creates a basic carousel. Clicking "btnPrev" navigates backwards and "btnNext" navigates forward.
 *
 * @option btnGo - array - no defaults
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev",
 *      btnGo: [".0", ".1", ".2"]
 * });
 * @desc If you don't want next and previous buttons for navigation, instead you prefer custom navigation based on
 * the item number within the carousel, you can use this option. Just supply an array of selectors for each element
 * in the carousel. The index of the array represents the index of the element. What i mean is, if the
 * first element in the array is ".0", it means that when the element represented by ".0" is clicked, the carousel
 * will slide to the first element and so on and so forth. This feature is very powerful. For example, i made a tabbed
 * interface out of it by making my navigation elements styled like tabs in css. As the carousel is capable of holding
 * any content, not just images, you can have a very simple tabbed navigation in minutes without using any other plugin.
 * The best part is that, the tab will "slide" based on the provided effect. :-)
 *
 * @option mouseWheel : boolean - default is false
 * @example
 * $(".carousel").jCarouselLite({
 *      mouseWheel: true
 * });
 * @desc The carousel can also be navigated using the mouse wheel interface of a scroll mouse instead of using buttons.
 * To get this feature working, you have to do 2 things. First, you have to include the mouse-wheel plugin from brandon.
 * Second, you will have to set the option "mouseWheel" to true. That's it, now you will be able to navigate your carousel
 * using the mouse wheel. Using buttons and mouseWheel or not mutually exclusive. You can still have buttons for navigation
 * as well. They complement each other. To use both together, just supply the options required for both as shown below.
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev",
 *      mouseWheel: true
 * });
 *
 * @option auto : number - default is null, meaning autoscroll is disabled by default
 * @example
 * $(".carousel").jCarouselLite({
 *      auto: 800,
 *      speed: 500
 * });
 * @desc You can make your carousel auto-navigate itself by specfying a millisecond value in this option.
 * The value you specify is the amount of time between 2 slides. The default is null, and that disables auto scrolling.
 * Specify this value and magically your carousel will start auto scrolling.
 *
 * @option speed : number - 200 is default
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev",
 *      speed: 800
 * });
 * @desc Specifying a speed will slow-down or speed-up the sliding speed of your carousel. Try it out with
 * different speeds like 800, 600, 1500 etc. Providing 0, will remove the slide effect.
 *
 * @option easing : string - no easing effects by default.
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev",
 *      easing: "bounceout"
 * });
 * @desc You can specify any easing effect. Note: You need easing plugin for that. Once specified,
 * the carousel will slide based on the provided easing effect.
 *
 * @option vertical : boolean - default is false
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev",
 *      vertical: true
 * });
 * @desc Determines the direction of the carousel. true, means the carousel will display vertically. The next and
 * prev buttons will slide the items vertically as well. The default is false, which means that the carousel will
 * display horizontally. The next and prev items will slide the items from left-right in this case.
 *
 * @option circular : boolean - default is true
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev",
 *      circular: false
 * });
 * @desc Setting it to true enables circular navigation. This means, if you click "next" after you reach the last
 * element, you will automatically slide to the first element and vice versa. If you set circular to false, then
 * if you click on the "next" button after you reach the last element, you will stay in the last element itself
 * and similarly for "previous" button and first element.
 *
 * @option visible : number - default is 3
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev",
 *      visible: 4
 * });
 * @desc This specifies the number of items visible at all times within the carousel. The default is 3.
 * You are even free to experiment with real numbers. Eg: "3.5" will have 3 items fully visible and the
 * last item half visible. This gives you the effect of showing the user that there are more images to the right.
 *
 * @option start : number - default is 0
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev",
 *      start: 2
 * });
 * @desc You can specify from which item the carousel should start. Remember, the first item in the carousel
 * has a start of 0, and so on.
 *
 * @option scrool : number - default is 1
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev",
 *      scroll: 2
 * });
 * @desc The number of items that should scroll/slide when you click the next/prev navigation buttons. By
 * default, only one item is scrolled, but you may set it to any number. Eg: setting it to "2" will scroll
 * 2 items when you click the next or previous buttons.
 *
 * @option beforeStart, afterEnd : function - callbacks
 * @example
 * $(".carousel").jCarouselLite({
 *      btnNext: ".next",
 *      btnPrev: ".prev",
 *      beforeStart: function(a) {
 *          alert("Before animation starts:" + a);
 *      },
 *      afterEnd: function(a) {
 *          alert("After animation ends:" + a);
 *      }
 * });
 * @desc If you wanted to do some logic in your page before the slide starts and after the slide ends, you can
 * register these 2 callbacks. The functions will be passed an argument that represents an array of elements that
 * are visible at the time of callback.
 *
 *
 * @cat Plugins/Image Gallery
 * @author Ganeshji Marwaha/ganeshread@gmail.com
 */

(function($) {                                          // Compliant with jquery.noConflict()
$.fn.jCarouselLite = function(o) {
    o = $.extend({
        btnPrev: null,
        btnNext: null,
        btnGo: null,
        mouseWheel: false,
        auto: null,
        hoverPause: false,

        speed: 200,
        easing: null,

        vertical: false,
        circular: true,
        visible: 3,
        start: 0,
        scroll: 1,

        beforeStart: null,
        afterEnd: null
    }, o || {});

    return this.each(function() {                           // Returns the element collection. Chainable.

        var running = false, animCss=o.vertical?"top":"left", sizeCss=o.vertical?"height":"width";
        var div = $(this), ul = $("ul", div), tLi = $("li", ul), tl = tLi.size(), v = o.visible;

        if(o.circular) {
            ul.prepend(tLi.slice(tl-v+1).clone())
              .append(tLi.slice(0,o.scroll).clone());
            o.start += v-1;
        }

        var li = $("li", ul), itemLength = li.size(), curr = o.start;
        div.css("visibility", "visible");

        li.css({overflow: "hidden", float: o.vertical ? "none" : "left"});
        ul.css({margin: "0", padding: "0", position: "relative", "list-style-type": "none", "z-index": "1"});
        div.css({overflow: "hidden", position: "relative", "z-index": "2", left: "0px"});

        var liSize = o.vertical ? height(li) : width(li);   // Full li size(incl margin)-Used for animation
        var ulSize = liSize * itemLength;                   // size of full ul(total length, not just for the visible items)
        var divSize = liSize * v;                           // size of entire div(total length for just the visible items)

        li.css({width: li.width(), height: li.height()});
        ul.css(sizeCss, ulSize+"px").css(animCss, -(curr*liSize));

        div.css(sizeCss, divSize+"px");                     // Width of the DIV. length of visible images

        if(o.btnPrev) {
            $(o.btnPrev).click(function() {
                return go(curr-o.scroll);
            });
            if(o.hoverPause) {
                $(o.btnPrev).hover(function(){stopAuto();}, function(){startAuto();});
            }
        }


        if(o.btnNext) {
            $(o.btnNext).click(function() {
                return go(curr+o.scroll);
            });
            if(o.hoverPause) {
                $(o.btnNext).hover(function(){stopAuto();}, function(){startAuto();});
            }
        }

        if(o.btnGo)
            $.each(o.btnGo, function(i, val) {
                $(val).click(function() {
                    return go(o.circular ? o.visible+i : i);
                });
            });

        if(o.mouseWheel && div.mousewheel)
            div.mousewheel(function(e, d) {
                return d>0 ? go(curr-o.scroll) : go(curr+o.scroll);
            });

        var autoInterval;

        function startAuto() {
          stopAuto();
          autoInterval = setInterval(function() {
                  go(curr+o.scroll);
              }, o.auto+o.speed);
        };

        function stopAuto() {
            clearInterval(autoInterval);
        };

        if(o.auto) {
            if(o.hoverPause) {
                div.hover(function(){stopAuto();}, function(){startAuto();});
            }
            startAuto();
        };

        function vis() {
            return li.slice(curr).slice(0,v);
        };

        function go(to) {
            if(!running) {

                if(o.beforeStart)
                    o.beforeStart.call(this, vis());

                if(o.circular) {            // If circular we are in first or last, then goto the other end
                    if(to<0) {           // If before range, then go around
                        ul.css(animCss, -( (curr + tl) * liSize)+"px");
                        curr = to + tl;
                    } else if(to>itemLength-v) { // If beyond range, then come around
                        ul.css(animCss, -( (curr - tl) * liSize ) + "px" );
                        curr = to - tl;
                    } else curr = to;
                } else {                    // If non-circular and to points to first or last, we just return.
                    if(to<0 || to>itemLength-v) return;
                    else curr = to;
                }                           // If neither overrides it, the curr will still be "to" and we can proceed.

                running = true;

                ul.animate(
                    animCss == "left" ? { left: -(curr*liSize) } : { top: -(curr*liSize) } , o.speed, o.easing,
                    function() {
                        if(o.afterEnd)
                            o.afterEnd.call(this, vis());
                        running = false;
                    }
                );
                // Disable buttons when the carousel reaches the last/first, and enable when not
                if(!o.circular) {
                    $(o.btnPrev + "," + o.btnNext).removeClass("disabled");
                    $( (curr-o.scroll<0 && o.btnPrev)
                        ||
                       (curr+o.scroll > itemLength-v && o.btnNext)
                        ||
                       []
                     ).addClass("disabled");
                }

            }
            return false;
        };
    });
};

function css(el, prop) {
    return parseInt($.css(el[0], prop)) || 0;
};
function width(el) {
    return  el[0].offsetWidth + css(el, 'marginLeft') + css(el, 'marginRight');
};
function height(el) {
    return el[0].offsetHeight + css(el, 'marginTop') + css(el, 'marginBottom');
};

})(jQuery);/**
 * jQBrowser v0.2 - Extend jQuery's browser detection capabilities
 *   * http://davecardwell.co.uk/javascript/jquery/plugins/jquery-browserdetect/0.2/
 *
 * Dave Cardwell <http://davecardwell.co.uk/>
 *
 * Built on the shoulders of giants:
 *   * John Resig <http://jquery.com/>
 *   * Peter-Paul Koch <http://www.quirksmode.org/?/js/detect.html>
 *
 *
 * Copyright (c) 2006 Dave Cardwell, dual licensed under the MIT and GPL
 * licenses:
 *   * http://www.opensource.org/licenses/mit-license.php
 *   * http://www.gnu.org/licenses/gpl.txt
 */


/**
 * For the latest version of this plugin, and a discussion of its usage and
 * implementation, visit:
 *   * http://davecardwell.co.uk/javascript/jquery/plugins/jquery-browserdetect/
 */

new function() {
    /**
     * The following functions and attributes form the Public interface of the
     * jQBrowser plugin, accessed externally through the $.browser object.
     * See the relevant function definition later in the source for further
     * information.
     *
     * $.browser.browser()
     * $.browser.version.number()
     * $.browser.version.string()
     * $.browser.OS()
     *
     * $.browser.aol()
     * $.browser.camino()
     * $.browser.firefox()
     * $.browser.flock()
     * $.browser.icab()
     * $.browser.konqueror()
     * $.browser.mozilla()
     * $.browser.msie()
     * $.browser.netscape()
     * $.browser.opera()
     * $.browser.safari()
     *
     * $.browser.linux()
     * $.browser.mac()
     * $.browser.win()
     */
    var Public = {
        // The current browser, its version as a number or a string, and the
        // operating system its running on.
          'browser': function() { return Private.browser;   },
          'version': {
              'number': function() { return Private.version.number; },
              'string': function() { return Private.version.string; }
          },
               'OS': function() { return Private.OS;        },

        // A boolean value indicating whether or not the given browser was
        // detected.
              'aol': function() { return Private.aol;       },
           'camino': function() { return Private.camino;    },
          'firefox': function() { return Private.firefox;   },
            'flock': function() { return Private.flock;     },
             'icab': function() { return Private.icab;      },
        'konqueror': function() { return Private.konqueror; },
          'mozilla': function() { return Private.mozilla;   },
             'msie': function() { return Private.msie;      },
         'netscape': function() { return Private.netscape;  },
            'opera': function() { return Private.opera;     },
           'safari': function() { return Private.safari;    },

        // A boolean value indicating whether or not the given OS was
        // detected.
            'linux': function() { return Private.linux;     },
              'mac': function() { return Private.mac;       },
              'win': function() { return Private.win;       }
    };

    // Allow external access to the 'Public' interface through the $.browser
    // object.
    $.browser = Public;



    /**
     * The following functions and attributes form the internal methods and
     * state of the jQBrowser plugin.  See the relevant function definition
     * later in the source for further information.
     *
     * Private.browser
     * Private.version
     * Private.OS
     *
     * Private.aol
     * Private.camino
     * Private.firefox
     * Private.flock
     * Private.icab
     * Private.konqueror
     * Private.mozilla
     * Private.msie
     * Private.netscape
     * Private.opera
     * Private.safari
     *
     * Private.linux
     * Private.mac
     * Private.win
     */
    var Private = {
        // Initially set to 'Unknown', if detected each of these properties will
        // be updated.
          'browser': 'Unknown',
          'version': {
              'number': undefined,
              'string': 'Unknown'
          },
               'OS': 'Unknown',

        // Initially set to false, if detected one of the following browsers
        // will be updated.
              'aol': false,
           'camino': false,
          'firefox': false,
            'flock': false,
             'icab': false,
        'konqueror': false,
          'mozilla': false,
             'msie': false,
         'netscape': false,
            'opera': false,
           'safari': false,

        // Initially set to false, if detected one of the following operating
        // systems will be updated.
            'linux': false,
              'mac': false,
              'win': false
    };



    /**
     * Loop over the items in 'data' trying to find a browser match with the
     * test in data[i].browser().  Once found, attempt to determine the
     * browser version.
     *
     *       'name': A string containing the full name of the browser.
     * 'identifier': By default this is a lowercase version of 'name', but
     *               this can be overwritten by explicitly defining an
     *               'identifier'.
     *    'browser': A function that returns a boolean value indicating
     *               whether or not the given browser is detected.
     *    'version': An optional function that overwrites the default version
     *               testing.  Must return the result of a .match().
     *
     * Please note that the order of the data array is important, as some
     * browsers contain details of others in their navigator.userAgent string.
     * For example, Flock's contains 'Firefox' so much come before Firefox's
     * test to avoid false positives.
     */
    for( var  i = 0,                    // counter
             ua = navigator.userAgent,  // the navigator's user agent string
             ve = navigator.vendor,     // the navigator's vendor string
           data = [                     // browser tests and data
                { // Safari <http://www.apple.com/safari/>
                          'name': 'Safari',
                       'browser': function() { return /Apple/.test(ve) }
                },
                { // Opera <http://www.opera.com/>
                          'name': 'Opera',
                       'browser': function() {
                                      return window.opera != undefined
                                  }
                },
                { // iCab <http://www.icab.de/>
                          'name': 'iCab',
                       'browser': function() { return /iCab/.test(ve) }
                },
                { // Konqueror <http://www.konqueror.org/>
                          'name': 'Konqueror',
                       'browser': function() { return /KDE/.test(ve) }
                },
                { // AOL Explorer <http://downloads.channel.aol.com/browser>
                    'identifier': 'aol',
                          'name': 'AOL Explorer',
                       'browser': function() {
                                      return /America Online Browser/.test(ua)
                                  },
                       'version': function() {
                                      return ua.match(/rev(\d+(?:\.\d+)+)/)
                                  }
                },
                { // Flock <http://www.flock.com/>
                          'name': 'Flock',
                       'browser': function() { return /Flock/.test(ua) }
                },
                { // Camino <http://www.caminobrowser.org/>
                          'name': 'Camino',
                       'browser': function() { return /Camino/.test(ve) }
                },
                { // Firefox <http://www.mozilla.com/firefox/>
                          'name': 'Firefox',
                       'browser': function() { return /Firefox/.test(ua) }
                },
                { // Netscape <http://browser.netscape.com/>
                          'name': 'Netscape',
                       'browser': function() { return /Netscape/.test(ua) }
                },
                { // Internet Explorer <http://www.microsoft.com/windows/ie/>
                  //                   <http://www.microsoft.com/mac/ie/>
                    'identifier': 'msie',
                          'name': 'Internet Explorer',
                       'browser': function() { return /MSIE/.test(ua) },
                       'version': function() {
                                      return ua.match(
                                          /MSIE (\d+(?:\.\d+)+(?:b\d*)?)/
                                      )
                                  }
                },
                { // Mozilla <http://www.mozilla.org/products/mozilla1.x/>
                          'name': 'Mozilla',
                       'browser': function() {
                                      return /Gecko|Mozilla/.test(ua)
                                  },
                       'version': function() {
                                      return ua.match(/rv:(\d+(?:\.\d+)+)/)
                                  }
                 }
             ];
         i < data.length;
         i++
    ) {
        if( data[i].browser() ) { // we have a match
            // If the identifier is not explicitly set, use a lowercase
            // version of the given name.
            var identifier = data[i].identifier ? data[i].identifier
                                                : data[i].name.toLowerCase();

            // Make a note that this browser was detected.
            Private[ identifier ] = true;

            // $.browser.browser() will now return the correct browser.
            Private.browser = data[i].name;

            var result;
            if( data[i].version != undefined && (result = data[i].version()) ) {
                // Use the explicitly set test for browser version.
                Private.version.string = result[1];
                Private.version.number = parseFloat( result[1] );
            } else {
                // Otherwise use the default test which searches for the
                // version number after the browser name in the user agent
                // string.
                var re = new RegExp(
                    data[i].name + '(?:\\s|\\/)(\\d+(?:\\.\\d+)+(?:(?:a|b)\\d*)?)'
                );

                result = ua.match(re);
                if( result != undefined ) {
                    Private.version.string = result[1];
                    Private.version.number = parseFloat( result[1] );
                }
            }

            // Once we've detected the browser there is no need to check the
            // others.
            break;
        }
    };



    /**
     * Loop over the items in 'data' trying to find a operating system match
     * with the test in data[i].os().
     *
     *       'name': A string containing the full name of the operating
     *               system.
     * 'identifier': By default this is a lowercase version of 'name', but
     *               this can be overwritten by explicitly defining an
     *               'identifier'.
     *         'OS': A function that returns a boolean value indicating
     *               whether or not the given operating system is detected.
     */
    for( var  i = 0,                  // counter
             pl = navigator.platform, // the navigator's platform string
           data = [                   // OS data and tests
                { // Microsoft Windows <http://www.microsoft.com/windows/>
                    'identifier': 'win',
                          'name': 'Windows',
                            'OS': function() { return /Win/.test(pl) }
                },
                { // Apple Mac OS <http://www.apple.com/macos/>
                          'name': 'Mac',
                            'OS': function() { return /Mac/.test(pl) }
                },
                { // Linux <http://www.linux.org/>
                          'name': 'Linux',
                            'OS': function() { return /Linux/.test(pl) }
                }
           ];
       i < data.length;
       i++
    ) {
        if( data[i].OS() ) { // we have a match
            // If the identifier is not explicitly set, use a lowercase
            // version of the given name.
            var identifier = data[i].identifier ? data[i].identifier
                                                : data[i].name.toLowerCase();

            // Make a note that the OS was detected.
            Private[ identifier ] = true;

            // $.browser.OS() will now return the correct OS.
            Private.OS = data[i].name;

            // Once we've detected the browser there is no need to check the
            // others.
            break;
        }
    };
}();/*!
 * jQuery blockUI plugin
 * Version 2.39 (23-MAY-2011)
 * @requires jQuery v1.2.3 or later
 *
 * Examples at: http://malsup.com/jquery/block/
 * Copyright (c) 2007-2010 M. Alsup
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Thanks to Amir-Hossein Sobhi for some excellent contributions!
 */

;(function($) {

if (/1\.(0|1|2)\.(0|1|2)/.test($.fn.jquery) || /^1.1/.test($.fn.jquery)) {
	alert('blockUI requires jQuery v1.2.3 or later!  You are using v' + $.fn.jquery);
	return;
}

$.fn._fadeIn = $.fn.fadeIn;

var noOp = function() {};

// this bit is to ensure we don't call setExpression when we shouldn't (with extra muscle to handle
// retarded userAgent strings on Vista)
var mode = document.documentMode || 0;
var setExpr = $.browser.msie && (($.browser.version < 8 && !mode) || mode < 8);
var ie6 = $.browser.msie && /MSIE 6.0/.test(navigator.userAgent) && !mode;

// global $ methods for blocking/unblocking the entire page
$.blockUI   = function(opts) { install(window, opts); };
$.unblockUI = function(opts) { remove(window, opts); };

// convenience method for quick growl-like notifications  (http://www.google.com/search?q=growl)
$.growlUI = function(title, message, timeout, onClose) {
	var $m = $('<div class="growlUI"></div>');
	if (title) $m.append('<h1>'+title+'</h1>');
	if (message) $m.append('<h2>'+message+'</h2>');
	if (timeout == undefined) timeout = 3000;
	$.blockUI({
		message: $m, fadeIn: 700, fadeOut: 1000, centerY: false,
		timeout: timeout, showOverlay: false,
		onUnblock: onClose, 
		css: $.blockUI.defaults.growlCSS
	});
};

// plugin method for blocking element content
$.fn.block = function(opts) {
	return this.unblock({ fadeOut: 0 }).each(function() {
		if ($.css(this,'position') == 'static')
			this.style.position = 'relative';
		if ($.browser.msie)
			this.style.zoom = 1; // force 'hasLayout'
		install(this, opts);
	});
};

// plugin method for unblocking element content
$.fn.unblock = function(opts) {
	return this.each(function() {
		remove(this, opts);
	});
};

$.blockUI.version = 2.39; // 2nd generation blocking at no extra cost!

// override these in your code to change the default behavior and style
$.blockUI.defaults = {
	// message displayed when blocking (use null for no message)
	message:  '<h1>Please wait...</h1>',

	title: null,	  // title string; only used when theme == true
	draggable: true,  // only used when theme == true (requires jquery-ui.js to be loaded)
	
	theme: false, // set to true to use with jQuery UI themes
	
	// styles for the message when blocking; if you wish to disable
	// these and use an external stylesheet then do this in your code:
	// $.blockUI.defaults.css = {};
	css: {
		padding:	0,
		margin:		0,
		width:		'30%',
		top:		'40%',
		left:		'35%',
		textAlign:	'center',
		color:		'#000',
		border:		'3px solid #aaa',
		backgroundColor:'#fff',
		cursor:		'wait'
	},
	
	// minimal style set used when themes are used
	themedCSS: {
		width:	'30%',
		top:	'40%',
		left:	'35%'
	},

	// styles for the overlay
	overlayCSS:  {
		backgroundColor: '#000',
		opacity:	  	 0.6,
		cursor:		  	 'wait'
	},

	// styles applied when using $.growlUI
	growlCSS: {
		width:  	'350px',
		top:		'10px',
		left:   	'',
		right:  	'10px',
		border: 	'none',
		padding:	'5px',
		opacity:	0.6,
		cursor: 	'default',
		color:		'#fff',
		backgroundColor: '#000',
		'-webkit-border-radius': '10px',
		'-moz-border-radius':	 '10px',
		'border-radius': 		 '10px'
	},
	
	// IE issues: 'about:blank' fails on HTTPS and javascript:false is s-l-o-w
	// (hat tip to Jorge H. N. de Vasconcelos)
	iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank',

	// force usage of iframe in non-IE browsers (handy for blocking applets)
	forceIframe: false,

	// z-index for the blocking overlay
	baseZ: 1000,

	// set these to true to have the message automatically centered
	centerX: true, // <-- only effects element blocking (page block controlled via css above)
	centerY: true,

	// allow body element to be stetched in ie6; this makes blocking look better
	// on "short" pages.  disable if you wish to prevent changes to the body height
	allowBodyStretch: true,

	// enable if you want key and mouse events to be disabled for content that is blocked
	bindEvents: true,

	// be default blockUI will supress tab navigation from leaving blocking content
	// (if bindEvents is true)
	constrainTabKey: true,

	// fadeIn time in millis; set to 0 to disable fadeIn on block
	fadeIn:  200,

	// fadeOut time in millis; set to 0 to disable fadeOut on unblock
	fadeOut:  400,

	// time in millis to wait before auto-unblocking; set to 0 to disable auto-unblock
	timeout: 0,

	// disable if you don't want to show the overlay
	showOverlay: true,

	// if true, focus will be placed in the first available input field when
	// page blocking
	focusInput: true,

	// suppresses the use of overlay styles on FF/Linux (due to performance issues with opacity)
	applyPlatformOpacityRules: true,
	
	// callback method invoked when fadeIn has completed and blocking message is visible
	onBlock: null,

	// callback method invoked when unblocking has completed; the callback is
	// passed the element that has been unblocked (which is the window object for page
	// blocks) and the options that were passed to the unblock call:
	//	 onUnblock(element, options)
	onUnblock: null,

	// don't ask; if you really must know: http://groups.google.com/group/jquery-en/browse_thread/thread/36640a8730503595/2f6a79a77a78e493#2f6a79a77a78e493
	quirksmodeOffsetHack: 4,

	// class name of the message block
	blockMsgClass: 'blockMsg'
};

// private data and functions follow...

var pageBlock = null;
var pageBlockEls = [];

function install(el, opts) {
	var full = (el == window);
	var msg = opts && opts.message !== undefined ? opts.message : undefined;
	opts = $.extend({}, $.blockUI.defaults, opts || {});
	opts.overlayCSS = $.extend({}, $.blockUI.defaults.overlayCSS, opts.overlayCSS || {});
	var css = $.extend({}, $.blockUI.defaults.css, opts.css || {});
	var themedCSS = $.extend({}, $.blockUI.defaults.themedCSS, opts.themedCSS || {});
	msg = msg === undefined ? opts.message : msg;

	// remove the current block (if there is one)
	if (full && pageBlock)
		remove(window, {fadeOut:0});

	// if an existing element is being used as the blocking content then we capture
	// its current place in the DOM (and current display style) so we can restore
	// it when we unblock
	if (msg && typeof msg != 'string' && (msg.parentNode || msg.jquery)) {
		var node = msg.jquery ? msg[0] : msg;
		var data = {};
		$(el).data('blockUI.history', data);
		data.el = node;
		data.parent = node.parentNode;
		data.display = node.style.display;
		data.position = node.style.position;
		if (data.parent)
			data.parent.removeChild(node);
	}

	$(el).data('blockUI.onUnblock', opts.onUnblock);
	var z = opts.baseZ;

	// blockUI uses 3 layers for blocking, for simplicity they are all used on every platform;
	// layer1 is the iframe layer which is used to supress bleed through of underlying content
	// layer2 is the overlay layer which has opacity and a wait cursor (by default)
	// layer3 is the message content that is displayed while blocking

	var lyr1 = ($.browser.msie || opts.forceIframe) 
		? $('<iframe class="blockUI" style="z-index:'+ (z++) +';display:none;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="'+opts.iframeSrc+'"></iframe>')
		: $('<div class="blockUI" style="display:none"></div>');
	
	var lyr2 = opts.theme 
	 	? $('<div class="blockUI blockOverlay ui-widget-overlay" style="z-index:'+ (z++) +';display:none"></div>')
	 	: $('<div class="blockUI blockOverlay" style="z-index:'+ (z++) +';display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>');

	var lyr3, s;
	if (opts.theme && full) {
		s = '<div class="blockUI ' + opts.blockMsgClass + ' blockPage ui-dialog ui-widget ui-corner-all" style="z-index:'+(z+10)+';display:none;position:fixed">' +
				'<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(opts.title || '&nbsp;')+'</div>' +
				'<div class="ui-widget-content ui-dialog-content"></div>' +
			'</div>';
	}
	else if (opts.theme) {
		s = '<div class="blockUI ' + opts.blockMsgClass + ' blockElement ui-dialog ui-widget ui-corner-all" style="z-index:'+(z+10)+';display:none;position:absolute">' +
				'<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(opts.title || '&nbsp;')+'</div>' +
				'<div class="ui-widget-content ui-dialog-content"></div>' +
			'</div>';
	}
	else if (full) {
		s = '<div class="blockUI ' + opts.blockMsgClass + ' blockPage" style="z-index:'+(z+10)+';display:none;position:fixed"></div>';
	}			 
	else {
		s = '<div class="blockUI ' + opts.blockMsgClass + ' blockElement" style="z-index:'+(z+10)+';display:none;position:absolute"></div>';
	}
	lyr3 = $(s);

	// if we have a message, style it
	if (msg) {
		if (opts.theme) {
			lyr3.css(themedCSS);
			lyr3.addClass('ui-widget-content');
		}
		else 
			lyr3.css(css);
	}

	// style the overlay
	if (!opts.theme && (!opts.applyPlatformOpacityRules || !($.browser.mozilla && /Linux/.test(navigator.platform))))
		lyr2.css(opts.overlayCSS);
	lyr2.css('position', full ? 'fixed' : 'absolute');

	// make iframe layer transparent in IE
	if ($.browser.msie || opts.forceIframe)
		lyr1.css('opacity',0.0);

	//$([lyr1[0],lyr2[0],lyr3[0]]).appendTo(full ? 'body' : el);
	var layers = [lyr1,lyr2,lyr3], $par = full ? $('body') : $(el);
	$.each(layers, function() {
		this.appendTo($par);
	});
	
	if (opts.theme && opts.draggable && $.fn.draggable) {
		lyr3.draggable({
			handle: '.ui-dialog-titlebar',
			cancel: 'li'
		});
	}

	// ie7 must use absolute positioning in quirks mode and to account for activex issues (when scrolling)
	var expr = setExpr && (!$.boxModel || $('object,embed', full ? null : el).length > 0);
	if (ie6 || expr) {
		// give body 100% height
		if (full && opts.allowBodyStretch && $.boxModel)
			$('html,body').css('height','100%');

		// fix ie6 issue when blocked element has a border width
		if ((ie6 || !$.boxModel) && !full) {
			var t = sz(el,'borderTopWidth'), l = sz(el,'borderLeftWidth');
			var fixT = t ? '(0 - '+t+')' : 0;
			var fixL = l ? '(0 - '+l+')' : 0;
		}

		// simulate fixed position
		$.each([lyr1,lyr2,lyr3], function(i,o) {
			var s = o[0].style;
			s.position = 'absolute';
			if (i < 2) {
				full ? s.setExpression('height','Math.max(document.body.scrollHeight, document.body.offsetHeight) - (jQuery.boxModel?0:'+opts.quirksmodeOffsetHack+') + "px"')
					 : s.setExpression('height','this.parentNode.offsetHeight + "px"');
				full ? s.setExpression('width','jQuery.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"')
					 : s.setExpression('width','this.parentNode.offsetWidth + "px"');
				if (fixL) s.setExpression('left', fixL);
				if (fixT) s.setExpression('top', fixT);
			}
			else if (opts.centerY) {
				if (full) s.setExpression('top','(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"');
				s.marginTop = 0;
			}
			else if (!opts.centerY && full) {
				var top = (opts.css && opts.css.top) ? parseInt(opts.css.top) : 0;
				var expression = '((document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + '+top+') + "px"';
				s.setExpression('top',expression);
			}
		});
	}

	// show the message
	if (msg) {
		if (opts.theme)
			lyr3.find('.ui-widget-content').append(msg);
		else
			lyr3.append(msg);
		if (msg.jquery || msg.nodeType)
			$(msg).show();
	}

	if (($.browser.msie || opts.forceIframe) && opts.showOverlay)
		lyr1.show(); // opacity is zero
	if (opts.fadeIn) {
		var cb = opts.onBlock ? opts.onBlock : noOp;
		var cb1 = (opts.showOverlay && !msg) ? cb : noOp;
		var cb2 = msg ? cb : noOp;
		if (opts.showOverlay)
			lyr2._fadeIn(opts.fadeIn, cb1);
		if (msg)
			lyr3._fadeIn(opts.fadeIn, cb2);
	}
	else {
		if (opts.showOverlay)
			lyr2.show();
		if (msg)
			lyr3.show();
		if (opts.onBlock)
			opts.onBlock();
	}

	// bind key and mouse events
	bind(1, el, opts);

	if (full) {
		pageBlock = lyr3[0];
		pageBlockEls = $(':input:enabled:visible',pageBlock);
		if (opts.focusInput)
			setTimeout(focus, 20);
	}
	else
		center(lyr3[0], opts.centerX, opts.centerY);

	if (opts.timeout) {
		// auto-unblock
		var to = setTimeout(function() {
			full ? $.unblockUI(opts) : $(el).unblock(opts);
		}, opts.timeout);
		$(el).data('blockUI.timeout', to);
	}
};

// remove the block
function remove(el, opts) {
	var full = (el == window);
	var $el = $(el);
	var data = $el.data('blockUI.history');
	var to = $el.data('blockUI.timeout');
	if (to) {
		clearTimeout(to);
		$el.removeData('blockUI.timeout');
	}
	opts = $.extend({}, $.blockUI.defaults, opts || {});
	bind(0, el, opts); // unbind events

	if (opts.onUnblock === null) {
		opts.onUnblock = $el.data('blockUI.onUnblock');
		$el.removeData('blockUI.onUnblock');
	}

	var els;
	if (full) // crazy selector to handle odd field errors in ie6/7
		els = $('body').children().filter('.blockUI').add('body > .blockUI');
	else
		els = $('.blockUI', el);

	if (full)
		pageBlock = pageBlockEls = null;

	if (opts.fadeOut) {
		els.fadeOut(opts.fadeOut);
		setTimeout(function() { reset(els,data,opts,el); }, opts.fadeOut);
	}
	else
		reset(els, data, opts, el);
};

// move blocking element back into the DOM where it started
function reset(els,data,opts,el) {
	els.each(function(i,o) {
		// remove via DOM calls so we don't lose event handlers
		if (this.parentNode)
			this.parentNode.removeChild(this);
	});

	if (data && data.el) {
		data.el.style.display = data.display;
		data.el.style.position = data.position;
		if (data.parent)
			data.parent.appendChild(data.el);
		$(el).removeData('blockUI.history');
	}

	if (typeof opts.onUnblock == 'function')
		opts.onUnblock(el,opts);
};

// bind/unbind the handler
function bind(b, el, opts) {
	var full = el == window, $el = $(el);

	// don't bother unbinding if there is nothing to unbind
	if (!b && (full && !pageBlock || !full && !$el.data('blockUI.isBlocked')))
		return;
	if (!full)
		$el.data('blockUI.isBlocked', b);

	// don't bind events when overlay is not in use or if bindEvents is false
	if (!opts.bindEvents || (b && !opts.showOverlay)) 
		return;

	// bind anchors and inputs for mouse and key events
	var events = 'mousedown mouseup keydown keypress';
	b ? $(document).bind(events, opts, handler) : $(document).unbind(events, handler);

// former impl...
//	   var $e = $('a,:input');
//	   b ? $e.bind(events, opts, handler) : $e.unbind(events, handler);
};

// event handler to suppress keyboard/mouse events when blocking
function handler(e) {
	// allow tab navigation (conditionally)
	if (e.keyCode && e.keyCode == 9) {
		if (pageBlock && e.data.constrainTabKey) {
			var els = pageBlockEls;
			var fwd = !e.shiftKey && e.target === els[els.length-1];
			var back = e.shiftKey && e.target === els[0];
			if (fwd || back) {
				setTimeout(function(){focus(back)},10);
				return false;
			}
		}
	}
	var opts = e.data;
	// allow events within the message content
	if ($(e.target).parents('div.' + opts.blockMsgClass).length > 0)
		return true;

	// allow events for content that is not being blocked
	return $(e.target).parents().children().filter('div.blockUI').length == 0;
};

function focus(back) {
	if (!pageBlockEls)
		return;
	var e = pageBlockEls[back===true ? pageBlockEls.length-1 : 0];
	if (e)
		e.focus();
};

function center(el, x, y) {
	var p = el.parentNode, s = el.style;
	var l = ((p.offsetWidth - el.offsetWidth)/2) - sz(p,'borderLeftWidth');
	var t = ((p.offsetHeight - el.offsetHeight)/2) - sz(p,'borderTopWidth');
	if (x) s.left = l > 0 ? (l+'px') : '0';
	if (y) s.top  = t > 0 ? (t+'px') : '0';
};

function sz(el, p) {
	return parseInt($.css(el,p))||0;
};

})(jQuery);
/*!
	jQuery Colorbox v1.4.15 - 2013-04-22
	(c) 2013 Jack Moore - jacklmoore.com/colorbox
	license: http://www.opensource.org/licenses/mit-license.php
*/
(function ($, document, window) {
	var
	// Default settings object.
	// See http://jacklmoore.com/colorbox for details.
	defaults = {
		transition: "elastic",
		speed: 300,
		fadeOut: 300,
		width: false,
		initialWidth: "100",
		innerWidth: false,
		maxWidth: false,
		height: false,
		initialHeight: "20",
		innerHeight: false,
		maxHeight: false,
		scalePhotos: true,
		scrolling: true,
		inline: false,
		html: false,
		iframe: false,
		fastIframe: true,
		photo: false,
		href: false,
		title: false,
		rel: false,
		opacity: 0.5,
		preloading: false,
		className: false,

		// alternate image paths for high-res displays
		retinaImage: false,
		retinaUrl: false,
		retinaSuffix: '@2x.$1',

		// internationalization

		current: "image {current} of {total}",
		previous: "previous",
		next: "next",
		close: "close",
		xhrError: "This content failed to load.",
		imgError: "This image failed to load.",

		
		open: false,
		returnFocus: true,
		reposition: false,
		loop: true,
		slideshow: false,
		slideshowAuto: true,
		slideshowSpeed: 2500,
		slideshowStart: "start slideshow",
		slideshowStop: "stop slideshow",
		photoRegex: /\.(gif|png|jp(e|g|eg)|bmp|ico|webp)((#|\?).*)?$/i,

		onOpen: false,
		onLoad: false,
		onComplete: false,
		onCleanup: false,
		onClosed: false,
		overlayClose: true,
		escKey: true,
		arrowKey: true,
		top: false,
		bottom: false,
		left: false,
		right: false,
		fixed: false,
		data: undefined
	},
	
	// Abstracting the HTML and event identifiers for easy rebranding
	colorbox = 'colorbox',
	prefix = 'cbox',
	boxElement = prefix + 'Element',
	
	// Events
	event_open = prefix + '_open',
	event_load = prefix + '_load',
	event_complete = prefix + '_complete',
	event_cleanup = prefix + '_cleanup',
	event_closed = prefix + '_closed',
	event_purge = prefix + '_purge',

	// Cached jQuery Object Variables
	$overlay,
	$box,
	$wrap,
	$content,
	$topBorder,
	$leftBorder,
	$rightBorder,
	$bottomBorder,
	$related,
	$window,
	$loaded,
	$loadingBay,
	$loadingOverlay,
	$title,
	$current,
	$slideshow,
	$next,
	$prev,
	$close,
	$groupControls,
	$events = $('<a/>'),
	
	// Variables for cached values or use across multiple functions
	settings,
	interfaceHeight,
	interfaceWidth,
	loadedHeight,
	loadedWidth,
	element,
	index,
	photo,
	open,
	active,
	closing,
	loadingTimer,
	publicMethod,
	div = "div",
	className,
	requests = 0,
	init;

	// ****************
	// HELPER FUNCTIONS
	// ****************
	
	// Convenience function for creating new jQuery objects
	function $tag(tag, id, css) {
		var element = document.createElement(tag);

		if (id) {
			element.id = prefix + id;
		}

		if (css) {
			element.style.cssText = css;
		}

		return $(element);
	}
	
	// Get the window height using innerHeight when available to avoid an issue with iOS
	// http://bugs.jquery.com/ticket/6724
	function winheight() {
		return window.innerHeight ? window.innerHeight : $(window).height();
	}

	// Determine the next and previous members in a group.
	function getIndex(increment) {
		var
		max = $related.length,
		newIndex = (index + increment) % max;
		
		return (newIndex < 0) ? max + newIndex : newIndex;
	}

	// Convert '%' and 'px' values to integers
	function setSize(size, dimension) {
		return Math.round((/%/.test(size) ? ((dimension === 'x' ? $window.width() : winheight()) / 100) : 1) * parseInt(size, 10));
	}
	
	// Checks an href to see if it is a photo.
	// There is a force photo option (photo: true) for hrefs that cannot be matched by the regex.
	function isImage(settings, url) {
		return settings.photo || settings.photoRegex.test(url);
	}

	function retinaUrl(settings, url) {
		return settings.retinaUrl && window.devicePixelRatio > 1 ? url.replace(settings.photoRegex, settings.retinaSuffix) : url;
	}

	function trapFocus(e) {
		if ('contains' in $box[0] && !$box[0].contains(e.target)) {
			e.stopPropagation();
			$box.focus();
		}
	}

	// Assigns function results to their respective properties
	function makeSettings() {
		var i,
			data = $.data(element, colorbox);
		
		if (data == null) {
			settings = $.extend({}, defaults);
			if (console && console.log) {
				console.log('Error: cboxElement missing settings object');
			}
		} else {
			settings = $.extend({}, data);
		}
		
		for (i in settings) {
			if ($.isFunction(settings[i]) && i.slice(0, 2) !== 'on') { // checks to make sure the function isn't one of the callbacks, they will be handled at the appropriate time.
				settings[i] = settings[i].call(element);
			}
		}
		
		settings.rel = settings.rel || element.rel || $(element).data('rel') || 'nofollow';
		settings.href = settings.href || $(element).attr('href');
		settings.title = settings.title || element.title;
		
		if (typeof settings.href === "string") {
			settings.href = $.trim(settings.href);
		}
	}

	function trigger(event, callback) {
		// for external use
		$(document).trigger(event);

		// for internal use
		$events.trigger(event);

		if ($.isFunction(callback)) {
			callback.call(element);
		}
	}

	// Slideshow functionality
	function slideshow() {
		var
		timeOut,
		className = prefix + "Slideshow_",
		click = "click." + prefix,
		clear,
		set,
		start,
		stop;
		
		if (settings.slideshow && $related[1]) {
			clear = function () {
				clearTimeout(timeOut);
			};

			set = function () {
				if (settings.loop || $related[index + 1]) {
					timeOut = setTimeout(publicMethod.next, settings.slideshowSpeed);
				}
			};

			start = function () {
				$slideshow
					.html(settings.slideshowStop)
					.unbind(click)
					.one(click, stop);

				$events
					.bind(event_complete, set)
					.bind(event_load, clear)
					.bind(event_cleanup, stop);

				$box.removeClass(className + "off").addClass(className + "on");
			};
			
			stop = function () {
				clear();
				
				$events
					.unbind(event_complete, set)
					.unbind(event_load, clear)
					.unbind(event_cleanup, stop);
				
				$slideshow
					.html(settings.slideshowStart)
					.unbind(click)
					.one(click, function () {
						publicMethod.next();
						start();
					});

				$box.removeClass(className + "on").addClass(className + "off");
			};
			
			if (settings.slideshowAuto) {
				start();
			} else {
				stop();
			}
		} else {
			$box.removeClass(className + "off " + className + "on");
		}
	}

	function launch(target) {
		if (!closing) {
			
			element = target;
			
			makeSettings();
			
			$related = $(element);
			
			index = 0;
			
			if (settings.rel !== 'nofollow') {
				$related = $('.' + boxElement).filter(function () {
					var data = $.data(this, colorbox),
						relRelated;

					if (data) {
						relRelated =  $(this).data('rel') || data.rel || this.rel;
					}
					
					return (relRelated === settings.rel);
				});
				index = $related.index(element);
				
				// Check direct calls to Colorbox.
				if (index === -1) {
					$related = $related.add(element);
					index = $related.length - 1;
				}
			}
			
			$overlay.css({
				opacity: parseFloat(settings.opacity),
				cursor: settings.overlayClose ? "pointer" : "auto",
				visibility: 'visible'
			}).show();
			

			if (className) {
				$box.add($overlay).removeClass(className);
			}
			if (settings.className) {
				$box.add($overlay).addClass(settings.className);
			}
			className = settings.className;

			$close.html(settings.close).show();

			if (!open) {
				open = active = true; // Prevents the page-change action from queuing up if the visitor holds down the left or right keys.
				
				// Show colorbox so the sizes can be calculated in older versions of jQuery
				$box.css({visibility:'hidden', display:'block'});
				
				$loaded = $tag(div, 'LoadedContent', 'width:0; height:0; overflow:hidden').appendTo($content);

				// Cache values needed for size calculations
				interfaceHeight = $topBorder.height() + $bottomBorder.height() + $content.outerHeight(true) - $content.height();
				interfaceWidth = $leftBorder.width() + $rightBorder.width() + $content.outerWidth(true) - $content.width();
				loadedHeight = $loaded.outerHeight(true);
				loadedWidth = $loaded.outerWidth(true);
				
				
				// Opens inital empty Colorbox prior to content being loaded.
				settings.w = setSize(settings.initialWidth, 'x');
				settings.h = setSize(settings.initialHeight, 'y');
				publicMethod.position();

				slideshow();

				trigger(event_open, settings.onOpen);
				
				$groupControls.add($title).hide();

				$box.focus();
				
				// Confine focus to the modal
				// Uses event capturing that is not supported in IE8-
				if (document.addEventListener) {

					document.addEventListener('focus', trapFocus, true);
					
					$events.one(event_closed, function () {
						document.removeEventListener('focus', trapFocus, true);
					});
				}

				// Return focus on closing
				if (settings.returnFocus) {
					$events.one(event_closed, function () {
						$(element).focus();
					});
				}
			}
			
			load();
		}
	}

	// Colorbox's markup needs to be added to the DOM prior to being called
	// so that the browser will go ahead and load the CSS background images.
	function appendHTML() {
		if (!$box && document.body) {
			init = false;
			$window = $(window);
			$box = $tag(div).attr({
				id: colorbox,
				'class': $.support.opacity === false ? prefix + 'IE' : '', // class for optional IE8 & lower targeted CSS.
				role: 'dialog',
				tabindex: '-1'
			}).hide();
			$overlay = $tag(div, "Overlay").hide();
			$loadingOverlay = $tag(div, "LoadingOverlay").add($tag(div, "LoadingGraphic"));
			$wrap = $tag(div, "Wrapper");
			$content = $tag(div, "Content").append(
				$title = $tag(div, "Title"),
				$current = $tag(div, "Current"),
				$prev = $('<button type="button"/>').attr({id:prefix+'Previous'}),
				$next = $('<button type="button"/>').attr({id:prefix+'Next'}),
				$slideshow = $tag('button', "Slideshow"),
				$loadingOverlay,
				$close = $('<button type="button"/>').attr({id:prefix+'Close'})
			);
			
			$wrap.append( // The 3x3 Grid that makes up Colorbox
				$tag(div).append(
					$tag(div, "TopLeft"),
					$topBorder = $tag(div, "TopCenter"),
					$tag(div, "TopRight")
				),
				$tag(div, false, 'clear:left').append(
					$leftBorder = $tag(div, "MiddleLeft"),
					$content,
					$rightBorder = $tag(div, "MiddleRight")
				),
				$tag(div, false, 'clear:left').append(
					$tag(div, "BottomLeft"),
					$bottomBorder = $tag(div, "BottomCenter"),
					$tag(div, "BottomRight")
				)
			).find('div div').css({'float': 'left'});
			
			$loadingBay = $tag(div, false, 'position:absolute; width:9999px; visibility:hidden; display:none');
			
			$groupControls = $next.add($prev).add($current).add($slideshow);

			$(document.body).append($overlay, $box.append($wrap, $loadingBay));
		}
	}

	// Add Colorbox's event bindings
	function addBindings() {
		function clickHandler(e) {
			// ignore non-left-mouse-clicks and clicks modified with ctrl / command, shift, or alt.
			// See: http://jacklmoore.com/notes/click-events/
			if (!(e.which > 1 || e.shiftKey || e.altKey || e.metaKey || e.control)) {
				e.preventDefault();
				launch(this);
			}
		}

		if ($box) {
			if (!init) {
				init = true;

				// Anonymous functions here keep the public method from being cached, thereby allowing them to be redefined on the fly.
				$next.click(function () {
					publicMethod.next();
				});
				$prev.click(function () {
					publicMethod.prev();
				});
				$close.click(function () {
					publicMethod.close();
				});
				$overlay.click(function () {
					if (settings.overlayClose) {
						publicMethod.close();
					}
				});
				
				// Key Bindings
				$(document).bind('keydown.' + prefix, function (e) {
					var key = e.keyCode;
					if (open && settings.escKey && key === 27) {
						e.preventDefault();
						publicMethod.close();
					}
					if (open && settings.arrowKey && $related[1] && !e.altKey) {
						if (key === 37) {
							e.preventDefault();
							$prev.click();
						} else if (key === 39) {
							e.preventDefault();
							$next.click();
						}
					}
				});

				if ($.isFunction($.fn.on)) {
					// For jQuery 1.7+
					$(document).on('click.'+prefix, '.'+boxElement, clickHandler);
				} else {
					// For jQuery 1.3.x -> 1.6.x
					// This code is never reached in jQuery 1.9, so do not contact me about 'live' being removed.
					// This is not here for jQuery 1.9, it's here for legacy users.
					$('.'+boxElement).live('click.'+prefix, clickHandler);
				}
			}
			return true;
		}
		return false;
	}

	// Don't do anything if Colorbox already exists.
	if ($.colorbox) {
		return;
	}

	// Append the HTML when the DOM loads
	$(appendHTML);


	// ****************
	// PUBLIC FUNCTIONS
	// Usage format: $.colorbox.close();
	// Usage from within an iframe: parent.jQuery.colorbox.close();
	// ****************
	
	publicMethod = $.fn[colorbox] = $[colorbox] = function (options, callback) {
		var $this = this;
		
		options = options || {};
		
		appendHTML();

		if (addBindings()) {
			if ($.isFunction($this)) { // assume a call to $.colorbox
				$this = $('<a/>');
				options.open = true;
			} else if (!$this[0]) { // colorbox being applied to empty collection
				return $this;
			}
			
			if (callback) {
				options.onComplete = callback;
			}
			
			$this.each(function () {
				$.data(this, colorbox, $.extend({}, $.data(this, colorbox) || defaults, options));
			}).addClass(boxElement);
			
			if (($.isFunction(options.open) && options.open.call($this)) || options.open) {
				launch($this[0]);
			}
		}
		
		return $this;
	};

	publicMethod.position = function (speed, loadedCallback) {
		var
		css,
		top = 0,
		left = 0,
		offset = $box.offset(),
		scrollTop,
		scrollLeft;
		
		$window.unbind('resize.' + prefix);

		// remove the modal so that it doesn't influence the document width/height
		$box.css({top: -9e4, left: -9e4});

		scrollTop = $window.scrollTop();
		scrollLeft = $window.scrollLeft();

		if (settings.fixed) {
			offset.top -= scrollTop;
			offset.left -= scrollLeft;
			$box.css({position: 'fixed'});
		} else {
			top = scrollTop;
			left = scrollLeft;
			$box.css({position: 'absolute'});
		}

		// keeps the top and left positions within the browser's viewport.
		if (settings.right !== false) {
			left += Math.max($window.width() - settings.w - loadedWidth - interfaceWidth - setSize(settings.right, 'x'), 0);
		} else if (settings.left !== false) {
			left += setSize(settings.left, 'x');
		} else {
			left += Math.round(Math.max($window.width() - settings.w - loadedWidth - interfaceWidth, 0) / 2);
		}
		
		if (settings.bottom !== false) {
			top += Math.max(winheight() - settings.h - loadedHeight - interfaceHeight - setSize(settings.bottom, 'y'), 0);
		} else if (settings.top !== false) {
			top += setSize(settings.top, 'y');
		} else {
			top += Math.round(Math.max(winheight() - settings.h - loadedHeight - interfaceHeight, 0) / 2);
		}

		$box.css({top: offset.top, left: offset.left, visibility:'visible'});

		// setting the speed to 0 to reduce the delay between same-sized content.
		speed = ($box.width() === settings.w + loadedWidth && $box.height() === settings.h + loadedHeight) ? 0 : speed || 0;
		
		// this gives the wrapper plenty of breathing room so it's floated contents can move around smoothly,
		// but it has to be shrank down around the size of div#colorbox when it's done.  If not,
		// it can invoke an obscure IE bug when using iframes.
		$wrap[0].style.width = $wrap[0].style.height = "9999px";
		
		function modalDimensions(that) {
			$topBorder[0].style.width = $bottomBorder[0].style.width = $content[0].style.width = (parseInt(that.style.width,10) - interfaceWidth)+'px';
			$content[0].style.height = $leftBorder[0].style.height = $rightBorder[0].style.height = (parseInt(that.style.height,10) - interfaceHeight)+'px';
		}

		css = {width: settings.w + loadedWidth + interfaceWidth, height: settings.h + loadedHeight + interfaceHeight, top: top, left: left};

		if(speed===0){ // temporary workaround to side-step jQuery-UI 1.8 bug (http://bugs.jquery.com/ticket/12273)
			$box.css(css);
		}
		$box.dequeue().animate(css, {
			duration: speed,
			complete: function () {
				modalDimensions(this);
				
				active = false;
				
				// shrink the wrapper down to exactly the size of colorbox to avoid a bug in IE's iframe implementation.
				$wrap[0].style.width = (settings.w + loadedWidth + interfaceWidth) + "px";
				$wrap[0].style.height = (settings.h + loadedHeight + interfaceHeight) + "px";
				
				if (settings.reposition) {
					setTimeout(function () {  // small delay before binding onresize due to an IE8 bug.
						$window.bind('resize.' + prefix, publicMethod.position);
					}, 1);
				}

				if (loadedCallback) {
					loadedCallback();
				}
			},
			step: function () {
				modalDimensions(this);
			}
		});
	};

	publicMethod.resize = function (options) {
		if (open) {
			options = options || {};
			
			if (options.width) {
				settings.w = setSize(options.width, 'x') - loadedWidth - interfaceWidth;
			}
			if (options.innerWidth) {
				settings.w = setSize(options.innerWidth, 'x');
			}
			$loaded.css({width: settings.w});
			
			if (options.height) {
				settings.h = setSize(options.height, 'y') - loadedHeight - interfaceHeight;
			}
			if (options.innerHeight) {
				settings.h = setSize(options.innerHeight, 'y');
			}
			if (!options.innerHeight && !options.height) {
				$loaded.css({height: "auto"});
				settings.h = $loaded.height();
			}
			$loaded.css({height: settings.h});
			
			publicMethod.position(settings.transition === "none" ? 0 : settings.speed);
		}
	};

	publicMethod.prep = function (object) {
		if (!open) {
			return;
		}
		
		var callback, speed = settings.transition === "none" ? 0 : settings.speed;

		$loaded.empty().remove(); // Using empty first may prevent some IE7 issues.

		$loaded = $tag(div, 'LoadedContent').append(object);
		
		function getWidth() {
			settings.w = settings.w || $loaded.width();
			settings.w = settings.mw && settings.mw < settings.w ? settings.mw : settings.w;
			return settings.w;
		}
		function getHeight() {
			settings.h = settings.h || $loaded.height();
			settings.h = settings.mh && settings.mh < settings.h ? settings.mh : settings.h;
			return settings.h;
		}
		
		$loaded.hide()
		.appendTo($loadingBay.show())// content has to be appended to the DOM for accurate size calculations.
		.css({width: getWidth(), overflow: settings.scrolling ? 'auto' : 'hidden'})
		.css({height: getHeight()})// sets the height independently from the width in case the new width influences the value of height.
		.prependTo($content);
		
		$loadingBay.hide();
		
		// floating the IMG removes the bottom line-height and fixed a problem where IE miscalculates the width of the parent element as 100% of the document width.
		
		$(photo).css({'float': 'none'});

		callback = function () {
			var total = $related.length,
				iframe,
				frameBorder = 'frameBorder',
				allowTransparency = 'allowTransparency',
				complete;
			
			if (!open) {
				return;
			}
			
			function removeFilter() { // Needed for IE7 & IE8 in versions of jQuery prior to 1.7.2
				if ($.support.opacity === false) {
					$box[0].style.removeAttribute('filter');
				}
			}
			
			complete = function () {
				clearTimeout(loadingTimer);
				$loadingOverlay.hide();
				trigger(event_complete, settings.onComplete);
			};

			
			$title.html(settings.title).add($loaded).show();
			
			if (total > 1) { // handle grouping
				if (typeof settings.current === "string") {
					$current.html(settings.current.replace('{current}', index + 1).replace('{total}', total)).show();
				}
				
				$next[(settings.loop || index < total - 1) ? "show" : "hide"]().html(settings.next);
				$prev[(settings.loop || index) ? "show" : "hide"]().html(settings.previous);
				
				if (settings.slideshow) {
					$slideshow.show();
				}
				
				// Preloads images within a rel group
				if (settings.preloading) {
					$.each([getIndex(-1), getIndex(1)], function(){
						var src,
							img,
							i = $related[this],
							data = $.data(i, colorbox);

						if (data && data.href) {
							src = data.href;
							if ($.isFunction(src)) {
								src = src.call(i);
							}
						} else {
							src = $(i).attr('href');
						}

						if (src && isImage(data, src)) {
							src = retinaUrl(data, src);
							img = new Image();
							img.src = src;
						}
					});
				}
			} else {
				$groupControls.hide();
			}
			
			if (settings.iframe) {
				iframe = $tag('iframe')[0];
				
				if (frameBorder in iframe) {
					iframe[frameBorder] = 0;
				}
				
				if (allowTransparency in iframe) {
					iframe[allowTransparency] = "true";
				}

				if (!settings.scrolling) {
					iframe.scrolling = "no";
				}
				
				$(iframe)
					.attr({
						src: settings.href,
						name: (new Date()).getTime(), // give the iframe a unique name to prevent caching
						'class': prefix + 'Iframe',
						allowFullScreen : true, // allow HTML5 video to go fullscreen
						webkitAllowFullScreen : true,
						mozallowfullscreen : true
					})
					.one('load', complete)
					.appendTo($loaded);
				
				$events.one(event_purge, function () {
					iframe.src = "//about:blank";
				});

				if (settings.fastIframe) {
					$(iframe).trigger('load');
				}
			} else {
				complete();
			}
			
			if (settings.transition === 'fade') {
				$box.fadeTo(speed, 1, removeFilter);
			} else {
				removeFilter();
			}
		};
		
		if (settings.transition === 'fade') {
			$box.fadeTo(speed, 0, function () {
				publicMethod.position(0, callback);
			});
		} else {
			publicMethod.position(speed, callback);
		}
	};

	function load () {
		var href, setResize, prep = publicMethod.prep, $inline, request = ++requests;
		
		active = true;
		
		photo = false;
		
		element = $related[index];
		
		makeSettings();
		
		trigger(event_purge);
		
		trigger(event_load, settings.onLoad);
		
		settings.h = settings.height ?
				setSize(settings.height, 'y') - loadedHeight - interfaceHeight :
				settings.innerHeight && setSize(settings.innerHeight, 'y');
		
		settings.w = settings.width ?
				setSize(settings.width, 'x') - loadedWidth - interfaceWidth :
				settings.innerWidth && setSize(settings.innerWidth, 'x');
		
		// Sets the minimum dimensions for use in image scaling
		settings.mw = settings.w;
		settings.mh = settings.h;
		
		// Re-evaluate the minimum width and height based on maxWidth and maxHeight values.
		// If the width or height exceed the maxWidth or maxHeight, use the maximum values instead.
		if (settings.maxWidth) {
			settings.mw = setSize(settings.maxWidth, 'x') - loadedWidth - interfaceWidth;
			settings.mw = settings.w && settings.w < settings.mw ? settings.w : settings.mw;
		}
		if (settings.maxHeight) {
			settings.mh = setSize(settings.maxHeight, 'y') - loadedHeight - interfaceHeight;
			settings.mh = settings.h && settings.h < settings.mh ? settings.h : settings.mh;
		}
		
		href = settings.href;
		
		loadingTimer = setTimeout(function () {
			$loadingOverlay.show();
		}, 100);
		
		if (settings.inline) {
			// Inserts an empty placeholder where inline content is being pulled from.
			// An event is bound to put inline content back when Colorbox closes or loads new content.
			$inline = $tag(div).hide().insertBefore($(href)[0]);

			$events.one(event_purge, function () {
				$inline.replaceWith($loaded.children());
			});

			prep($(href));
		} else if (settings.iframe) {
			// IFrame element won't be added to the DOM until it is ready to be displayed,
			// to avoid problems with DOM-ready JS that might be trying to run in that iframe.
			prep(" ");
		} else if (settings.html) {
			prep(settings.html);
		} else if (isImage(settings, href)) {

			href = retinaUrl(settings, href);

			$(photo = new Image())
			.addClass(prefix + 'Photo')
			.bind('error',function () {
				settings.title = false;
				prep($tag(div, 'Error').html(settings.imgError));
			})
			.one('load', function () {
				var percent;

				if (request !== requests) {
					return;
				}

				photo.alt = $(element).attr('alt') || $(element).attr('data-alt') || '';

				if (settings.retinaImage && window.devicePixelRatio > 1) {
					photo.height = photo.height / window.devicePixelRatio;
					photo.width = photo.width / window.devicePixelRatio;
				}

				if (settings.scalePhotos) {
					setResize = function () {
						photo.height -= photo.height * percent;
						photo.width -= photo.width * percent;
					};
					if (settings.mw && photo.width > settings.mw) {
						percent = (photo.width - settings.mw) / photo.width;
						setResize();
					}
					if (settings.mh && photo.height > settings.mh) {
						percent = (photo.height - settings.mh) / photo.height;
						setResize();
					}
				}
				
				if (settings.h) {
					photo.style.marginTop = Math.max(settings.mh - photo.height, 0) / 2 + 'px';
				}
				
				if ($related[1] && (settings.loop || $related[index + 1])) {
					photo.style.cursor = 'pointer';
					photo.onclick = function () {
						publicMethod.next();
					};
				}

				photo.style.width = photo.width + 'px';
				photo.style.height = photo.height + 'px';

				setTimeout(function () { // A pause because Chrome will sometimes report a 0 by 0 size otherwise.
					prep(photo);
				}, 1);
			});
			
			setTimeout(function () { // A pause because Opera 10.6+ will sometimes not run the onload function otherwise.
				photo.src = href;
			}, 1);
		} else if (href) {
			$loadingBay.load(href, settings.data, function (data, status) {
				if (request === requests) {
					prep(status === 'error' ? $tag(div, 'Error').html(settings.xhrError) : $(this).contents());
				}
			});
		}
	}
		
	// Navigates to the next page/image in a set.
	publicMethod.next = function () {
		if (!active && $related[1] && (settings.loop || $related[index + 1])) {
			index = getIndex(1);
			launch($related[index]);
		}
	};
	
	publicMethod.prev = function () {
		if (!active && $related[1] && (settings.loop || index)) {
			index = getIndex(-1);
			launch($related[index]);
		}
	};

	// Note: to use this within an iframe use the following format: parent.jQuery.colorbox.close();
	publicMethod.close = function () {
		if (open && !closing) {
			
			closing = true;
			
			open = false;
			
			trigger(event_cleanup, settings.onCleanup);
			
			$window.unbind('.' + prefix);
			
			$overlay.fadeTo(settings.fadeOut || 0, 0);
			
			$box.stop().fadeTo(settings.fadeOut || 0, 0, function () {
			
				$box.add($overlay).css({'opacity': 1, cursor: 'auto'}).hide();
				
				trigger(event_purge);
				
				$loaded.empty().remove(); // Using empty first may prevent some IE7 issues.
				
				setTimeout(function () {
					closing = false;
					trigger(event_closed, settings.onClosed);
				}, 1);
			});
		}
	};

	// Removes changes Colorbox made to the document, but does not remove the plugin.
	publicMethod.remove = function () {
		if (!$box) { return; }

		$box.stop();
		$.colorbox.close();
		$box.stop().remove();
		$overlay.remove();
		closing = false;
		$box = null;
		$('.' + boxElement)
			.removeData(colorbox)
			.removeClass(boxElement);

		$(document).unbind('click.'+prefix);
	};

	// A method for fetching the current element Colorbox is referencing.
	// returns a jQuery object.
	publicMethod.element = function () {
		return $(element);
	};

	publicMethod.settings = defaults;

}(jQuery, document, window));
/**
 * Copyright (c)2005-2009 Matt Kruse (javascripttoolbox.com)
 * 
 * Dual licensed under the MIT and GPL licenses. 
 * This basically means you can use this code however you want for
 * free, but don't claim to have written it yourself!
 * Donations always accepted: http://www.JavascriptToolbox.com/donate/
 * 
 * Please do not link to the .js files on javascripttoolbox.com from
 * your site. Copy the files locally to your server instead.
 * 
 */
/**
 * jquery.contextmenu.js
 * jQuery Plugin for Context Menus
 * http://www.JavascriptToolbox.com/lib/contextmenu/
 *
 * Copyright (c) 2008 Matt Kruse (javascripttoolbox.com)
 * Dual licensed under the MIT and GPL licenses. 
 *
 * @version 1.1
 * @history 1.1 2010-01-25 Fixed a problem with 1.4 which caused undesired show/hide animations
 * @history 1.0 2008-10-20 Initial Release
 * @todo slideUp doesn't work in IE - because of iframe?
 * @todo Hide all other menus when contextmenu is shown?
 * @todo More themes
 * @todo Nested context menus
 */
;(function($){
	$.contextMenu = {
		shadow:true,
		shadowOffset:0,
		shadowOffsetX:5,
		shadowOffsetY:5,
		shadowWidthAdjust:-3,
		shadowHeightAdjust:-3,
		shadowOpacity:.2,
		shadowClass:'context-menu-shadow',
		shadowColor:'black',

		offsetX:0,
		offsetY:0,
		appendTo:'body',
		direction:'down',
		constrainToScreen:true,
				
		showTransition:'show',
		hideTransition:'hide',
		showSpeed:null,
		hideSpeed:null,
		showCallback:null,
		hideCallback:null,
		
		className:'context-menu',
		itemClassName:'context-menu-item',
		itemHoverClassName:'context-menu-item-hover',
		disabledItemClassName:'context-menu-item-disabled',
		disabledItemHoverClassName:'context-menu-item-disabled-hover',
		separatorClassName:'context-menu-separator',
		innerDivClassName:'context-menu-item-inner',
		themePrefix:'context-menu-theme-',
		theme:'default',

		separator:'context-menu-separator', // A specific key to identify a separator
		target:null, // The target of the context click, to be populated when triggered
		menu:null, // The jQuery object containing the HTML object that is the menu itself
		shadowObj:null, // Shadow object
		bgiframe:null, // The iframe object for IE6
		shown:false, // Currently being shown?
		useIframe:/*@cc_on @*//*@if (@_win32) true, @else @*/false,/*@end @*/ // This is a better check than looking at userAgent!
		
		// Create the menu instance
		create: function(menu,opts) {
			var cmenu = $.extend({},this,opts); // Clone all default properties to created object
			
			// If a selector has been passed in, then use that as the menu
			if (typeof menu=="string") {
				cmenu.menu = $(menu);
			} 
			// If a function has been passed in, call it each time the menu is shown to create the menu
			else if (typeof menu=="function") {
				cmenu.menuFunction = menu;
			}
			// Otherwise parse the Array passed in
			else {
				cmenu.menu = cmenu.createMenu(menu,cmenu);
			}
			if (cmenu.menu) {
				cmenu.menu.css({display:'none'});
				$(cmenu.appendTo).append(cmenu.menu);
			}
			
			// Create the shadow object if shadow is enabled
			if (cmenu.shadow) {
				cmenu.createShadow(cmenu); // Extracted to method for extensibility
				if (cmenu.shadowOffset) { cmenu.shadowOffsetX = cmenu.shadowOffsetY = cmenu.shadowOffset; }
			}
			$('body').bind('contextmenu',function(){cmenu.hide();}); // If right-clicked somewhere else in the document, hide this menu
			return cmenu;
		},
		
		// Create an iframe object to go behind the menu
		createIframe: function() {
		    return $('<iframe frameborder="0" tabindex="-1" src="javascript:false" style="display:block;position:absolute;z-index:-1;filter:Alpha(Opacity=0);"/>');
		},
		
		// Accept an Array representing a menu structure and turn it into HTML
		createMenu: function(menu,cmenu) {
			var className = cmenu.className;
			$.each(cmenu.theme.split(","),function(i,n){className+=' '+cmenu.themePrefix+n});
			var $t = $('<table cellspacing=0 cellpadding=0></table>').click(function(){cmenu.hide(); return false;}); // We wrap a table around it so width can be flexible
			var $tr = $('<tr></tr>');
			var $td = $('<td></td>');
			var $div = $('<div class="'+className+'"></div>');
			
			// Each menu item is specified as either:
			//     title:function
			// or  title: { property:value ... }
			for (var i=0; i<menu.length; i++) {
				var m = menu[i];
				if (m==$.contextMenu.separator) {
					$div.append(cmenu.createSeparator());
				}
				else {
					for (var opt in menu[i]) {
						$div.append(cmenu.createMenuItem(opt,menu[i][opt])); // Extracted to method for extensibility
					}
				}
			}
			if ( cmenu.useIframe ) {
				$td.append(cmenu.createIframe());
			}
			$t.append($tr.append($td.append($div)))
			return $t;
		},
		
		// Create an individual menu item
		createMenuItem: function(label,obj) {
			var cmenu = this;
			if (typeof obj=="function") { obj={onclick:obj}; } // If passed a simple function, turn it into a property of an object
			// Default properties, extended in case properties are passed
			var o = $.extend({
				onclick:function() { },
				className:'',
				hoverClassName:cmenu.itemHoverClassName,
				icon:'',
				disabled:false,
				title:'',
				hoverItem:cmenu.hoverItem,
				hoverItemOut:cmenu.hoverItemOut,
				name:'',
				rel:''
			},obj);
			// If an icon is specified, hard-code the background-image style. Themes that don't show images should take this into account in their CSS
			var iconStyle = (o.icon)?'background-image:url('+o.icon+');':'';
			var $div = $('<a class="'+cmenu.itemClassName+' '+o.className+((o.disabled)?' '+cmenu.disabledItemClassName:'')+'" title="'+o.title+'" name="'+o.name+'" rel="'+o.rel+'"></a>')
							// If the item is disabled, don't do anything when it is clicked
							.click(function(e){if(cmenu.isItemDisabled(this)){return false;}else{return o.onclick.call(cmenu.target,this,cmenu,e)}})
							// Change the class of the item when hovered over
							.hover( function(){ o.hoverItem.call(this,(cmenu.isItemDisabled(this))?cmenu.disabledItemHoverClassName:o.hoverClassName); }
									,function(){ o.hoverItemOut.call(this,(cmenu.isItemDisabled(this))?cmenu.disabledItemHoverClassName:o.hoverClassName); }
							);
			var $idiv = $('<div class="'+cmenu.innerDivClassName+'" style="'+iconStyle+'">'+label+'</div>');
			$div.append($idiv);
			return $div;
		},
		
		// Create a separator row
		createSeparator: function() {
			return $('<div class="'+this.separatorClassName+'"></div>');
		},
		
		// Determine if an individual item is currently disabled. This is called each time the item is hovered or clicked because the disabled status may change at any time
		isItemDisabled: function(item) { return $(item).is('.'+this.disabledItemClassName); },
		
		// Functions to fire on hover. Extracted to methods for extensibility
		hoverItem: function(c) { $(this).addClass(c); },
		hoverItemOut: function(c) { $(this).removeClass(c); },
		
		// Create the shadow object
		createShadow: function(cmenu) {
			cmenu.shadowObj = $('<div class="'+cmenu.shadowClass+'"></div>').css( {display:'none',position:"absolute", zIndex:9998, opacity:cmenu.shadowOpacity, backgroundColor:cmenu.shadowColor } );
			$(cmenu.appendTo).append(cmenu.shadowObj);
		},
		
		// Display the shadow object, given the position of the menu itself
		showShadow: function(x,y,e) {
			var cmenu = this;
			if (cmenu.shadow) {
				cmenu.shadowObj.css( {
					width:(cmenu.menu.width()+cmenu.shadowWidthAdjust)+"px", 
					height:(cmenu.menu.height()+cmenu.shadowHeightAdjust)+"px", 
					top:(y+cmenu.shadowOffsetY)+"px", 
					left:(x+cmenu.shadowOffsetX)+"px"
				}).addClass(cmenu.shadowClass)[cmenu.showTransition](cmenu.showSpeed);
			}
		},
		
		// A hook to call before the menu is shown, in case special processing needs to be done.
		// Return false to cancel the default show operation
		beforeShow: function() { return true; },
		
		// Show the context menu
		show: function(t,e) {
			var cmenu=this, x=e.pageX, y=e.pageY;
			cmenu.target = t; // Preserve the object that triggered this context menu so menu item click methods can see it
			if (cmenu.beforeShow()!==false) {
				// If the menu content is a function, call it to populate the menu each time it is displayed
				if (cmenu.menuFunction) {
					if (cmenu.menu) { $(cmenu.menu).remove(); }
					cmenu.menu = cmenu.createMenu(cmenu.menuFunction(cmenu,t),cmenu);
					cmenu.menu.css({display:'none'});
					$(cmenu.appendTo).append(cmenu.menu);
				}
				var $c = cmenu.menu;
				x+=cmenu.offsetX; y+=cmenu.offsetY;
				var pos = cmenu.getPosition(x,y,cmenu,e); // Extracted to method for extensibility
				cmenu.showShadow(pos.x,pos.y,e);
				// Resize the iframe if needed
				if (cmenu.useIframe) {
					$c.find('iframe').css({width:$c.width()+cmenu.shadowOffsetX+cmenu.shadowWidthAdjust,height:$c.height()+cmenu.shadowOffsetY+cmenu.shadowHeightAdjust});
				}
				$c.css( {top:pos.y+"px", left:pos.x+"px", position:"absolute",zIndex:9999} )[cmenu.showTransition](cmenu.showSpeed,((cmenu.showCallback)?function(){cmenu.showCallback.call(cmenu);}:null));
				cmenu.shown=true;
				$(document).one('click',null,function(){cmenu.hide()}); // Handle a single click to the document to hide the menu
			}
		},
		
		// Find the position where the menu should appear, given an x,y of the click event
		getPosition: function(clickX,clickY,cmenu,e) {
			var x = clickX+cmenu.offsetX;
			var y = clickY+cmenu.offsetY
			var h = $(cmenu.menu).height();
			var w = $(cmenu.menu).width();
			var dir = cmenu.direction;
			if (cmenu.constrainToScreen) {
				var $w = $(window);
				var wh = $w.height();
				var ww = $w.width();
				if (dir=="down" && (y+h-$w.scrollTop() > wh)) { dir = "up"; }
				var maxRight = x+w-$w.scrollLeft();
				if (maxRight > ww) { x -= (maxRight-ww); }
			}
			if (dir=="up") { y -= h; }
			return {'x':x,'y':y};
		},
		
		// Hide the menu, of course
		hide: function() {
			var cmenu=this;
			if (cmenu.shown) {
				if (cmenu.iframe) { $(cmenu.iframe).hide(); }
				if (cmenu.menu) { cmenu.menu[cmenu.hideTransition](cmenu.hideSpeed,((cmenu.hideCallback)?function(){cmenu.hideCallback.call(cmenu);}:null)); }
				if (cmenu.shadow) { cmenu.shadowObj[cmenu.hideTransition](cmenu.hideSpeed); }
			}
			cmenu.shown = false;
		}
	};
	
	// This actually adds the .contextMenu() function to the jQuery namespace
	$.fn.contextMenu = function(menu,options) {
		var cmenu = $.contextMenu.create(menu,options);
		return this.each(function(){
			$(this).bind('contextmenu',function(e){cmenu.show(this,e);return false;});
		});
	};
})(jQuery);
/*!
 * jQuery corner plugin: simple corner rounding
 * Examples and documentation at: http://jquery.malsup.com/corner/
 * version 2.11 (15-JUN-2010)
 * Requires jQuery v1.3.2 or later
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Authors: Dave Methvin and Mike Alsup
 */

/**
 *  corner() takes a single string argument:  $('#myDiv').corner("effect corners width")
 *
 *  effect:  name of the effect to apply, such as round, bevel, notch, bite, etc (default is round). 
 *  corners: one or more of: top, bottom, tr, tl, br, or bl.  (default is all corners)
 *  width:   width of the effect; in the case of rounded corners this is the radius. 
 *           specify this value using the px suffix such as 10px (yes, it must be pixels).
 */
;(function($) { 

var style = document.createElement('div').style;
var moz = style['MozBorderRadius'] !== undefined;
var webkit = style['WebkitBorderRadius'] !== undefined;
var radius = style['borderRadius'] !== undefined || style['BorderRadius'] !== undefined;
var mode = document.documentMode || 0;
var noBottomFold = $.browser.msie && (($.browser.version < 8 && !mode) || mode < 8);

$.support = $.support || {};
$.support.borderRadius = moz || webkit || radius; // so you can do:  if (!$.support.borderRadius) $('#myDiv').corner();

var expr = $.browser.msie && (function() {
    var div = document.createElement('div');
    try { div.style.setExpression('width','0+0'); div.style.removeExpression('width'); }
    catch(e) { return false; }
    return true;
})();
    
function sz(el, p) { 
    return parseInt($.css(el,p))||0; 
};
function hex2(s) {
    var s = parseInt(s).toString(16);
    return ( s.length < 2 ) ? '0'+s : s;
};
function gpc(node) {
    while(node) {
        var v = $.css(node,'backgroundColor');
        if (v && v != 'transparent' && v != 'rgba(0, 0, 0, 0)') {
	        if (v.indexOf('rgb') >= 0) { 
	            var rgb = v.match(/\d+/g); 
	            return '#'+ hex2(rgb[0]) + hex2(rgb[1]) + hex2(rgb[2]);
	        }
            return v;
		}
		if (node.nodeName.toLowerCase() == 'html')
		    break;
		node = node.parentNode; // keep walking if transparent
    }
    return '#ffffff';
};

function getWidth(fx, i, width) {
    switch(fx) {
    case 'round':  return Math.round(width*(1-Math.cos(Math.asin(i/width))));
    case 'cool':   return Math.round(width*(1+Math.cos(Math.asin(i/width))));
    case 'sharp':  return Math.round(width*(1-Math.cos(Math.acos(i/width))));
    case 'bite':   return Math.round(width*(Math.cos(Math.asin((width-i-1)/width))));
    case 'slide':  return Math.round(width*(Math.atan2(i,width/i)));
    case 'jut':    return Math.round(width*(Math.atan2(width,(width-i-1))));
    case 'curl':   return Math.round(width*(Math.atan(i)));
    case 'tear':   return Math.round(width*(Math.cos(i)));
    case 'wicked': return Math.round(width*(Math.tan(i)));
    case 'long':   return Math.round(width*(Math.sqrt(i)));
    case 'sculpt': return Math.round(width*(Math.log((width-i-1),width)));
	case 'dogfold':
    case 'dog':    return (i&1) ? (i+1) : width;
    case 'dog2':   return (i&2) ? (i+1) : width;
    case 'dog3':   return (i&3) ? (i+1) : width;
    case 'fray':   return (i%2)*width;
    case 'notch':  return width; 
	case 'bevelfold':
    case 'bevel':  return i+1;
    }
};

$.fn.corner = function(options) {
    // in 1.3+ we can fix mistakes with the ready state
	if (this.length == 0) {
        if (!$.isReady && this.selector) {
            var s = this.selector, c = this.context;
            $(function() {
                $(s,c).corner(options);
            });
        }
        return this;
	}

    return this.each(function(index){
		var $this = $(this);
		// meta values override options
		var o = [$this.attr($.fn.corner.defaults.metaAttr) || '', options || ''].join(' ').toLowerCase();
		var keep = /keep/.test(o);                       // keep borders?
		var cc = ((o.match(/cc:(#[0-9a-f]+)/)||[])[1]);  // corner color
		var sc = ((o.match(/sc:(#[0-9a-f]+)/)||[])[1]);  // strip color
		var width = parseInt((o.match(/(\d+)px/)||[])[1]) || 10; // corner width
		var re = /round|bevelfold|bevel|notch|bite|cool|sharp|slide|jut|curl|tear|fray|wicked|sculpt|long|dog3|dog2|dogfold|dog/;
		var fx = ((o.match(re)||['round'])[0]);
		var fold = /dogfold|bevelfold/.test(o);
		var edges = { T:0, B:1 };
		var opts = {
			TL:  /top|tl|left/.test(o),       TR:  /top|tr|right/.test(o),
			BL:  /bottom|bl|left/.test(o),    BR:  /bottom|br|right/.test(o)
		};
		if ( !opts.TL && !opts.TR && !opts.BL && !opts.BR )
			opts = { TL:1, TR:1, BL:1, BR:1 };
			
		// support native rounding
		if ($.fn.corner.defaults.useNative && fx == 'round' && (radius || moz || webkit) && !cc && !sc) {
			if (opts.TL)
				$this.css(radius ? 'border-top-left-radius' : moz ? '-moz-border-radius-topleft' : '-webkit-border-top-left-radius', width + 'px');
			if (opts.TR)
				$this.css(radius ? 'border-top-right-radius' : moz ? '-moz-border-radius-topright' : '-webkit-border-top-right-radius', width + 'px');
			if (opts.BL)
				$this.css(radius ? 'border-bottom-left-radius' : moz ? '-moz-border-radius-bottomleft' : '-webkit-border-bottom-left-radius', width + 'px');
			if (opts.BR)
				$this.css(radius ? 'border-bottom-right-radius' : moz ? '-moz-border-radius-bottomright' : '-webkit-border-bottom-right-radius', width + 'px');
			return;
		}
			
		var strip = document.createElement('div');
		$(strip).css({
			overflow: 'hidden',
			height: '1px',
			minHeight: '1px',
			fontSize: '1px',
			backgroundColor: sc || 'transparent',
			borderStyle: 'solid'
		});
	
        var pad = {
            T: parseInt($.css(this,'paddingTop'))||0,     R: parseInt($.css(this,'paddingRight'))||0,
            B: parseInt($.css(this,'paddingBottom'))||0,  L: parseInt($.css(this,'paddingLeft'))||0
        };

        if (typeof this.style.zoom != undefined) this.style.zoom = 1; // force 'hasLayout' in IE
        if (!keep) this.style.border = 'none';
        strip.style.borderColor = cc || gpc(this.parentNode);
        var cssHeight = $(this).outerHeight();

        for (var j in edges) {
            var bot = edges[j];
            // only add stips if needed
            if ((bot && (opts.BL || opts.BR)) || (!bot && (opts.TL || opts.TR))) {
                strip.style.borderStyle = 'none '+(opts[j+'R']?'solid':'none')+' none '+(opts[j+'L']?'solid':'none');
                var d = document.createElement('div');
                $(d).addClass('jquery-corner');
                var ds = d.style;

                bot ? this.appendChild(d) : this.insertBefore(d, this.firstChild);

                if (bot && cssHeight != 'auto') {
                    if ($.css(this,'position') == 'static')
                        this.style.position = 'relative';
                    ds.position = 'absolute';
                    ds.bottom = ds.left = ds.padding = ds.margin = '0';
                    if (expr)
                        ds.setExpression('width', 'this.parentNode.offsetWidth');
                    else
                        ds.width = '100%';
                }
                else if (!bot && $.browser.msie) {
                    if ($.css(this,'position') == 'static')
                        this.style.position = 'relative';
                    ds.position = 'absolute';
                    ds.top = ds.left = ds.right = ds.padding = ds.margin = '0';
                    
                    // fix ie6 problem when blocked element has a border width
                    if (expr) {
                        var bw = sz(this,'borderLeftWidth') + sz(this,'borderRightWidth');
                        ds.setExpression('width', 'this.parentNode.offsetWidth - '+bw+'+ "px"');
                    }
                    else
                        ds.width = '100%';
                }
                else {
                	ds.position = 'relative';
                    ds.margin = !bot ? '-'+pad.T+'px -'+pad.R+'px '+(pad.T-width)+'px -'+pad.L+'px' : 
                                        (pad.B-width)+'px -'+pad.R+'px -'+pad.B+'px -'+pad.L+'px';                
                }

                for (var i=0; i < width; i++) {
                    var w = Math.max(0,getWidth(fx,i, width));
                    var e = strip.cloneNode(false);
                    e.style.borderWidth = '0 '+(opts[j+'R']?w:0)+'px 0 '+(opts[j+'L']?w:0)+'px';
                    bot ? d.appendChild(e) : d.insertBefore(e, d.firstChild);
                }
				
				if (fold && $.support.boxModel) {
					if (bot && noBottomFold) continue;
					for (var c in opts) {
						if (!opts[c]) continue;
						if (bot && (c == 'TL' || c == 'TR')) continue;
						if (!bot && (c == 'BL' || c == 'BR')) continue;
						
						var common = { position: 'absolute', border: 'none', margin: 0, padding: 0, overflow: 'hidden', backgroundColor: strip.style.borderColor };
						var $horz = $('<div/>').css(common).css({ width: width + 'px', height: '1px' });
						switch(c) {
						case 'TL': $horz.css({ bottom: 0, left: 0 }); break;
						case 'TR': $horz.css({ bottom: 0, right: 0 }); break;
						case 'BL': $horz.css({ top: 0, left: 0 }); break;
						case 'BR': $horz.css({ top: 0, right: 0 }); break;
						}
						d.appendChild($horz[0]);
						
						var $vert = $('<div/>').css(common).css({ top: 0, bottom: 0, width: '1px', height: width + 'px' });
						switch(c) {
						case 'TL': $vert.css({ left: width }); break;
						case 'TR': $vert.css({ right: width }); break;
						case 'BL': $vert.css({ left: width }); break;
						case 'BR': $vert.css({ right: width }); break;
						}
						d.appendChild($vert[0]);
					}
				}
            }
        }
    });
};

$.fn.uncorner = function() { 
	if (radius || moz || webkit)
		this.css(radius ? 'border-radius' : moz ? '-moz-border-radius' : '-webkit-border-radius', 0);
	$('div.jquery-corner', this).remove();
	return this;
};

// expose options
$.fn.corner.defaults = {
	useNative: true, // true if plugin should attempt to use native browser support for border radius rounding
	metaAttr:  'data-corner' // name of meta attribute to use for options
};
    
})(jQuery);
/*
 * jQuery delegate plug-in v1.0
 *
 * Copyright (c) 2007 J�rn Zaefferer
 *
 * $Id$
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

// provides cross-browser focusin and focusout events
// IE has native support, in other browsers, use event caputuring (neither bubbles)

// provides delegate(type: String, delegate: Selector, handler: Callback) plugin for easier event delegation
// handler is only called when $(event.target).is(delegate), in the scope of the jQuery-object for event.target

// provides triggerEvent(type: String, target: Element) to trigger delegated events
;(function($) {
        $.extend($.event.special, {
                focusin: {
                        setup: function() {
                                if ($.browser.msie)
                                        return false;
                                this.addEventListener("focus", $.event.special.focusin.handler, true);
                        },
                        teardown: function() {
                                if ($.browser.msie)
                                        return false;
                                this.removeEventListener("focus", $.event.special.focusin.handler, true);
                        },
                        handler: function(event) {
                                var args = Array.prototype.slice.call( arguments, 1 );
                                args.unshift($.extend($.event.fix(event), { type: "focusin" }));
                                return $.event.handle.apply(this, args);
                        }
                },
                focusout: {
                        setup: function() {
                                if ($.browser.msie)
                                        return false;
                                this.addEventListener("blur", $.event.special.focusout.handler, true);
                        },
                        teardown: function() {
                                if ($.browser.msie)
                                        return false;
                                this.removeEventListener("blur", $.event.special.focusout.handler, true);
                        },
                        handler: function(event) {
                                var args = Array.prototype.slice.call( arguments, 1 );
                                args.unshift($.extend($.event.fix(event), { type: "focusout" }));
                                return $.event.handle.apply(this, args);
                        }
                }
        });
        $.extend($.fn, {
                delegate: function(type, delegate, handler) {
                        return this.bind(type, function(event) {
                                var target = $(event.target);
                                if (target.is(delegate)) {
                                        return handler.apply(target, arguments);
                                }
                        });
                },
                triggerEvent: function(type, target) {
                        return this.triggerHandler(type, [jQuery.event.fix({ type: type, target: target })]);
                }
        })
})(jQuery);

(function(c){var b={init:function(e){var f={set_width:false,set_height:false,horizontalScroll:false,scrollInertia:950,mouseWheel:true,mouseWheelPixels:"auto",autoDraggerLength:true,autoHideScrollbar:false,scrollButtons:{enable:false,scrollType:"continuous",scrollSpeed:"auto",scrollAmount:40},advanced:{updateOnBrowserResize:true,updateOnContentResize:false,autoExpandHorizontalScroll:false,autoScrollOnFocus:true,normalizeMouseWheelDelta:false},contentTouchScroll:true,callbacks:{onScrollStart:function(){},onScroll:function(){},onTotalScroll:function(){},onTotalScrollBack:function(){},onTotalScrollOffset:0,onTotalScrollBackOffset:0,whileScrolling:function(){}},theme:"light"},e=c.extend(true,f,e);return this.each(function(){var m=c(this);if(e.set_width){m.css("width",e.set_width)}if(e.set_height){m.css("height",e.set_height)}if(!c(document).data("mCustomScrollbar-index")){c(document).data("mCustomScrollbar-index","1")}else{var t=parseInt(c(document).data("mCustomScrollbar-index"));c(document).data("mCustomScrollbar-index",t+1)}m.wrapInner("<div class='mCustomScrollBox mCS-"+e.theme+"' id='mCSB_"+c(document).data("mCustomScrollbar-index")+"' style='position:relative; height:100%; overflow:hidden; max-width:100%;' />").addClass("mCustomScrollbar _mCS_"+c(document).data("mCustomScrollbar-index"));var g=m.children(".mCustomScrollBox");if(e.horizontalScroll){g.addClass("mCSB_horizontal").wrapInner("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />");var k=g.children(".mCSB_h_wrapper");k.wrapInner("<div class='mCSB_container' style='position:absolute; left:0;' />").children(".mCSB_container").css({width:k.children().outerWidth(),position:"relative"}).unwrap()}else{g.wrapInner("<div class='mCSB_container' style='position:relative; top:0;' />")}var o=g.children(".mCSB_container");if(c.support.touch){o.addClass("mCS_touch")}o.after("<div class='mCSB_scrollTools' style='position:absolute;'><div class='mCSB_draggerContainer'><div class='mCSB_dragger' style='position:absolute;' oncontextmenu='return false;'><div class='mCSB_dragger_bar' style='position:relative;'></div></div><div class='mCSB_draggerRail'></div></div></div>");var l=g.children(".mCSB_scrollTools"),h=l.children(".mCSB_draggerContainer"),q=h.children(".mCSB_dragger");if(e.horizontalScroll){q.data("minDraggerWidth",q.width())}else{q.data("minDraggerHeight",q.height())}if(e.scrollButtons.enable){if(e.horizontalScroll){l.prepend("<a class='mCSB_buttonLeft' oncontextmenu='return false;'></a>").append("<a class='mCSB_buttonRight' oncontextmenu='return false;'></a>")}else{l.prepend("<a class='mCSB_buttonUp' oncontextmenu='return false;'></a>").append("<a class='mCSB_buttonDown' oncontextmenu='return false;'></a>")}}g.bind("scroll",function(){if(!m.is(".mCS_disabled")){g.scrollTop(0).scrollLeft(0)}});m.data({mCS_Init:true,mCustomScrollbarIndex:c(document).data("mCustomScrollbar-index"),horizontalScroll:e.horizontalScroll,scrollInertia:e.scrollInertia,scrollEasing:"mcsEaseOut",mouseWheel:e.mouseWheel,mouseWheelPixels:e.mouseWheelPixels,autoDraggerLength:e.autoDraggerLength,autoHideScrollbar:e.autoHideScrollbar,scrollButtons_enable:e.scrollButtons.enable,scrollButtons_scrollType:e.scrollButtons.scrollType,scrollButtons_scrollSpeed:e.scrollButtons.scrollSpeed,scrollButtons_scrollAmount:e.scrollButtons.scrollAmount,autoExpandHorizontalScroll:e.advanced.autoExpandHorizontalScroll,autoScrollOnFocus:e.advanced.autoScrollOnFocus,normalizeMouseWheelDelta:e.advanced.normalizeMouseWheelDelta,contentTouchScroll:e.contentTouchScroll,onScrollStart_Callback:e.callbacks.onScrollStart,onScroll_Callback:e.callbacks.onScroll,onTotalScroll_Callback:e.callbacks.onTotalScroll,onTotalScrollBack_Callback:e.callbacks.onTotalScrollBack,onTotalScroll_Offset:e.callbacks.onTotalScrollOffset,onTotalScrollBack_Offset:e.callbacks.onTotalScrollBackOffset,whileScrolling_Callback:e.callbacks.whileScrolling,bindEvent_scrollbar_drag:false,bindEvent_content_touch:false,bindEvent_scrollbar_click:false,bindEvent_mousewheel:false,bindEvent_buttonsContinuous_y:false,bindEvent_buttonsContinuous_x:false,bindEvent_buttonsPixels_y:false,bindEvent_buttonsPixels_x:false,bindEvent_focusin:false,bindEvent_autoHideScrollbar:false,mCSB_buttonScrollRight:false,mCSB_buttonScrollLeft:false,mCSB_buttonScrollDown:false,mCSB_buttonScrollUp:false});if(e.horizontalScroll){if(m.css("max-width")!=="none"){if(!e.advanced.updateOnContentResize){e.advanced.updateOnContentResize=true}}}else{if(m.css("max-height")!=="none"){var s=false,r=parseInt(m.css("max-height"));if(m.css("max-height").indexOf("%")>=0){s=r,r=m.parent().height()*s/100}m.css("overflow","hidden");g.css("max-height",r)}}m.mCustomScrollbar("update");if(e.advanced.updateOnBrowserResize){var i,j=c(window).width(),u=c(window).height();c(window).bind("resize."+m.data("mCustomScrollbarIndex"),function(){if(i){clearTimeout(i)}i=setTimeout(function(){if(!m.is(".mCS_disabled")&&!m.is(".mCS_destroyed")){var w=c(window).width(),v=c(window).height();if(j!==w||u!==v){if(m.css("max-height")!=="none"&&s){g.css("max-height",m.parent().height()*s/100)}m.mCustomScrollbar("update");j=w;u=v}}},150)})}if(e.advanced.updateOnContentResize){var p;if(e.horizontalScroll){var n=o.outerWidth()}else{var n=o.outerHeight()}p=setInterval(function(){if(e.horizontalScroll){if(e.advanced.autoExpandHorizontalScroll){o.css({position:"absolute",width:"auto"}).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({width:o.outerWidth(),position:"relative"}).unwrap()}var v=o.outerWidth()}else{var v=o.outerHeight()}if(v!=n){m.mCustomScrollbar("update");n=v}},300)}})},update:function(){var n=c(this),k=n.children(".mCustomScrollBox"),q=k.children(".mCSB_container");q.removeClass("mCS_no_scrollbar");n.removeClass("mCS_disabled mCS_destroyed");k.scrollTop(0).scrollLeft(0);var y=k.children(".mCSB_scrollTools"),o=y.children(".mCSB_draggerContainer"),m=o.children(".mCSB_dragger");if(n.data("horizontalScroll")){var A=y.children(".mCSB_buttonLeft"),t=y.children(".mCSB_buttonRight"),f=k.width();if(n.data("autoExpandHorizontalScroll")){q.css({position:"absolute",width:"auto"}).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({width:q.outerWidth(),position:"relative"}).unwrap()}var z=q.outerWidth()}else{var w=y.children(".mCSB_buttonUp"),g=y.children(".mCSB_buttonDown"),r=k.height(),i=q.outerHeight()}if(i>r&&!n.data("horizontalScroll")){y.css("display","block");var s=o.height();if(n.data("autoDraggerLength")){var u=Math.round(r/i*s),l=m.data("minDraggerHeight");if(u<=l){m.css({height:l})}else{if(u>=s-10){var p=s-10;m.css({height:p})}else{m.css({height:u})}}m.children(".mCSB_dragger_bar").css({"line-height":m.height()+"px"})}var B=m.height(),x=(i-r)/(s-B);n.data("scrollAmount",x).mCustomScrollbar("scrolling",k,q,o,m,w,g,A,t);var D=Math.abs(q.position().top);n.mCustomScrollbar("scrollTo",D,{scrollInertia:0,trigger:"internal"})}else{if(z>f&&n.data("horizontalScroll")){y.css("display","block");var h=o.width();if(n.data("autoDraggerLength")){var j=Math.round(f/z*h),C=m.data("minDraggerWidth");if(j<=C){m.css({width:C})}else{if(j>=h-10){var e=h-10;m.css({width:e})}else{m.css({width:j})}}}var v=m.width(),x=(z-f)/(h-v);n.data("scrollAmount",x).mCustomScrollbar("scrolling",k,q,o,m,w,g,A,t);var D=Math.abs(q.position().left);n.mCustomScrollbar("scrollTo",D,{scrollInertia:0,trigger:"internal"})}else{k.unbind("mousewheel focusin");if(n.data("horizontalScroll")){m.add(q).css("left",0)}else{m.add(q).css("top",0)}y.css("display","none");q.addClass("mCS_no_scrollbar");n.data({bindEvent_mousewheel:false,bindEvent_focusin:false})}}},scrolling:function(h,p,m,j,w,e,A,v){var k=c(this);if(!k.data("bindEvent_scrollbar_drag")){var n,o;if(c.support.msPointer){j.bind("MSPointerDown",function(H){H.preventDefault();k.data({on_drag:true});j.addClass("mCSB_dragger_onDrag");var G=c(this),J=G.offset(),F=H.originalEvent.pageX-J.left,I=H.originalEvent.pageY-J.top;if(F<G.width()&&F>0&&I<G.height()&&I>0){n=I;o=F}});c(document).bind("MSPointerMove."+k.data("mCustomScrollbarIndex"),function(H){H.preventDefault();if(k.data("on_drag")){var G=j,J=G.offset(),F=H.originalEvent.pageX-J.left,I=H.originalEvent.pageY-J.top;D(n,o,I,F)}}).bind("MSPointerUp."+k.data("mCustomScrollbarIndex"),function(x){k.data({on_drag:false});j.removeClass("mCSB_dragger_onDrag")})}else{j.bind("mousedown touchstart",function(H){H.preventDefault();H.stopImmediatePropagation();var G=c(this),K=G.offset(),F,J;if(H.type==="touchstart"){var I=H.originalEvent.touches[0]||H.originalEvent.changedTouches[0];F=I.pageX-K.left;J=I.pageY-K.top}else{k.data({on_drag:true});j.addClass("mCSB_dragger_onDrag");F=H.pageX-K.left;J=H.pageY-K.top}if(F<G.width()&&F>0&&J<G.height()&&J>0){n=J;o=F}}).bind("touchmove",function(H){H.preventDefault();H.stopImmediatePropagation();var K=H.originalEvent.touches[0]||H.originalEvent.changedTouches[0],G=c(this),J=G.offset(),F=K.pageX-J.left,I=K.pageY-J.top;D(n,o,I,F)});c(document).bind("mousemove."+k.data("mCustomScrollbarIndex"),function(H){if(k.data("on_drag")){var G=j,J=G.offset(),F=H.pageX-J.left,I=H.pageY-J.top;D(n,o,I,F)}}).bind("mouseup."+k.data("mCustomScrollbarIndex"),function(x){k.data({on_drag:false});j.removeClass("mCSB_dragger_onDrag")})}k.data({bindEvent_scrollbar_drag:true})}function D(G,H,I,F){if(k.data("horizontalScroll")){k.mCustomScrollbar("scrollTo",(j.position().left-(H))+F,{moveDragger:true,trigger:"internal"})}else{k.mCustomScrollbar("scrollTo",(j.position().top-(G))+I,{moveDragger:true,trigger:"internal"})}}if(c.support.touch&&k.data("contentTouchScroll")){if(!k.data("bindEvent_content_touch")){var l,B,r,s,u,C,E;p.bind("touchstart",function(x){x.stopImmediatePropagation();l=x.originalEvent.touches[0]||x.originalEvent.changedTouches[0];B=c(this);r=B.offset();u=l.pageX-r.left;s=l.pageY-r.top;C=s;E=u});p.bind("touchmove",function(x){x.preventDefault();x.stopImmediatePropagation();l=x.originalEvent.touches[0]||x.originalEvent.changedTouches[0];B=c(this).parent();r=B.offset();u=l.pageX-r.left;s=l.pageY-r.top;if(k.data("horizontalScroll")){k.mCustomScrollbar("scrollTo",E-u,{trigger:"internal"})}else{k.mCustomScrollbar("scrollTo",C-s,{trigger:"internal"})}})}}if(!k.data("bindEvent_scrollbar_click")){m.bind("click",function(F){var x=(F.pageY-m.offset().top)*k.data("scrollAmount"),y=c(F.target);if(k.data("horizontalScroll")){x=(F.pageX-m.offset().left)*k.data("scrollAmount")}if(y.hasClass("mCSB_draggerContainer")||y.hasClass("mCSB_draggerRail")){k.mCustomScrollbar("scrollTo",x,{trigger:"internal",scrollEasing:"draggerRailEase"})}});k.data({bindEvent_scrollbar_click:true})}if(k.data("mouseWheel")){if(!k.data("bindEvent_mousewheel")){h.bind("mousewheel",function(H,J){var G,F=k.data("mouseWheelPixels"),x=Math.abs(p.position().top),I=j.position().top,y=m.height()-j.height();if(k.data("normalizeMouseWheelDelta")){if(J<0){J=-1}else{J=1}}if(F==="auto"){F=100+Math.round(k.data("scrollAmount")/2)}if(k.data("horizontalScroll")){I=j.position().left;y=m.width()-j.width();x=Math.abs(p.position().left)}if((J>0&&I!==0)||(J<0&&I!==y)){H.preventDefault();H.stopImmediatePropagation()}G=x-(J*F);k.mCustomScrollbar("scrollTo",G,{trigger:"internal"})});k.data({bindEvent_mousewheel:true})}}if(k.data("scrollButtons_enable")){if(k.data("scrollButtons_scrollType")==="pixels"){if(k.data("horizontalScroll")){v.add(A).unbind("mousedown touchstart MSPointerDown mouseup MSPointerUp mouseout MSPointerOut touchend",i,g);k.data({bindEvent_buttonsContinuous_x:false});if(!k.data("bindEvent_buttonsPixels_x")){v.bind("click",function(x){x.preventDefault();q(Math.abs(p.position().left)+k.data("scrollButtons_scrollAmount"))});A.bind("click",function(x){x.preventDefault();q(Math.abs(p.position().left)-k.data("scrollButtons_scrollAmount"))});k.data({bindEvent_buttonsPixels_x:true})}}else{e.add(w).unbind("mousedown touchstart MSPointerDown mouseup MSPointerUp mouseout MSPointerOut touchend",i,g);k.data({bindEvent_buttonsContinuous_y:false});if(!k.data("bindEvent_buttonsPixels_y")){e.bind("click",function(x){x.preventDefault();q(Math.abs(p.position().top)+k.data("scrollButtons_scrollAmount"))});w.bind("click",function(x){x.preventDefault();q(Math.abs(p.position().top)-k.data("scrollButtons_scrollAmount"))});k.data({bindEvent_buttonsPixels_y:true})}}function q(x){if(!j.data("preventAction")){j.data("preventAction",true);k.mCustomScrollbar("scrollTo",x,{trigger:"internal"})}}}else{if(k.data("horizontalScroll")){v.add(A).unbind("click");k.data({bindEvent_buttonsPixels_x:false});if(!k.data("bindEvent_buttonsContinuous_x")){v.bind("mousedown touchstart MSPointerDown",function(y){y.preventDefault();var x=z();k.data({mCSB_buttonScrollRight:setInterval(function(){k.mCustomScrollbar("scrollTo",Math.abs(p.position().left)+x,{trigger:"internal",scrollEasing:"easeOutCirc"})},17)})});var i=function(x){x.preventDefault();clearInterval(k.data("mCSB_buttonScrollRight"))};v.bind("mouseup touchend MSPointerUp mouseout MSPointerOut",i);A.bind("mousedown touchstart MSPointerDown",function(y){y.preventDefault();var x=z();k.data({mCSB_buttonScrollLeft:setInterval(function(){k.mCustomScrollbar("scrollTo",Math.abs(p.position().left)-x,{trigger:"internal",scrollEasing:"easeOutCirc"})},17)})});var g=function(x){x.preventDefault();clearInterval(k.data("mCSB_buttonScrollLeft"))};A.bind("mouseup touchend MSPointerUp mouseout MSPointerOut",g);k.data({bindEvent_buttonsContinuous_x:true})}}else{e.add(w).unbind("click");k.data({bindEvent_buttonsPixels_y:false});if(!k.data("bindEvent_buttonsContinuous_y")){e.bind("mousedown touchstart MSPointerDown",function(y){y.preventDefault();var x=z();k.data({mCSB_buttonScrollDown:setInterval(function(){k.mCustomScrollbar("scrollTo",Math.abs(p.position().top)+x,{trigger:"internal",scrollEasing:"easeOutCirc"})},17)})});var t=function(x){x.preventDefault();clearInterval(k.data("mCSB_buttonScrollDown"))};e.bind("mouseup touchend MSPointerUp mouseout MSPointerOut",t);w.bind("mousedown touchstart MSPointerDown",function(y){y.preventDefault();var x=z();k.data({mCSB_buttonScrollUp:setInterval(function(){k.mCustomScrollbar("scrollTo",Math.abs(p.position().top)-x,{trigger:"internal",scrollEasing:"easeOutCirc"})},17)})});var f=function(x){x.preventDefault();clearInterval(k.data("mCSB_buttonScrollUp"))};w.bind("mouseup touchend MSPointerUp mouseout MSPointerOut",f);k.data({bindEvent_buttonsContinuous_y:true})}}function z(){var x=k.data("scrollButtons_scrollSpeed");if(k.data("scrollButtons_scrollSpeed")==="auto"){x=Math.round((k.data("scrollInertia")+100)/40)}return x}}}if(k.data("autoScrollOnFocus")){if(!k.data("bindEvent_focusin")){h.bind("focusin",function(){h.scrollTop(0).scrollLeft(0);var x=c(document.activeElement);if(x.is("input,textarea,select,button,a[tabindex],area,object")){var G=p.position().top,y=x.position().top,F=h.height()-x.outerHeight();if(k.data("horizontalScroll")){G=p.position().left;y=x.position().left;F=h.width()-x.outerWidth()}if(G+y<0||G+y>F){k.mCustomScrollbar("scrollTo",y,{trigger:"internal"})}}});k.data({bindEvent_focusin:true})}}if(k.data("autoHideScrollbar")){if(!k.data("bindEvent_autoHideScrollbar")){h.bind("mouseenter",function(x){h.addClass("mCS-mouse-over");d.showScrollbar.call(h.children(".mCSB_scrollTools"))}).bind("mouseleave touchend",function(x){h.removeClass("mCS-mouse-over");if(x.type==="mouseleave"){d.hideScrollbar.call(h.children(".mCSB_scrollTools"))}});k.data({bindEvent_autoHideScrollbar:true})}}},scrollTo:function(n,u){var r=c(this),k={moveDragger:false,trigger:"external",callbacks:true,scrollInertia:r.data("scrollInertia"),scrollEasing:r.data("scrollEasing")},u=c.extend(k,u),j,i=r.children(".mCustomScrollBox"),s=i.children(".mCSB_container"),q=i.children(".mCSB_scrollTools"),h=q.children(".mCSB_draggerContainer"),t=h.children(".mCSB_dragger"),g=draggerSpeed=u.scrollInertia,m,f,l,e;if(!s.hasClass("mCS_no_scrollbar")){r.data({mCS_trigger:u.trigger});if(r.data("mCS_Init")){u.callbacks=false}if(n||n===0){if(typeof(n)==="number"){if(u.moveDragger){j=n;if(r.data("horizontalScroll")){n=t.position().left*r.data("scrollAmount")}else{n=t.position().top*r.data("scrollAmount")}draggerSpeed=0}else{j=n/r.data("scrollAmount")}}else{if(typeof(n)==="string"){var p;if(n==="top"){p=0}else{if(n==="bottom"&&!r.data("horizontalScroll")){p=s.outerHeight()-i.height()}else{if(n==="left"){p=0}else{if(n==="right"&&r.data("horizontalScroll")){p=s.outerWidth()-i.width()}else{if(n==="first"){p=r.find(".mCSB_container").find(":first")}else{if(n==="last"){p=r.find(".mCSB_container").find(":last")}else{p=r.find(n)}}}}}}if(p.length===1){if(r.data("horizontalScroll")){n=p.position().left}else{n=p.position().top}j=n/r.data("scrollAmount")}else{j=n=p}}}if(r.data("horizontalScroll")){if(r.data("onTotalScrollBack_Offset")){f=-r.data("onTotalScrollBack_Offset")}if(r.data("onTotalScroll_Offset")){e=i.width()-s.outerWidth()+r.data("onTotalScroll_Offset")}if(j<0){j=n=0;clearInterval(r.data("mCSB_buttonScrollLeft"));if(!f){m=true}}else{if(j>=h.width()-t.width()){j=h.width()-t.width();n=i.width()-s.outerWidth();clearInterval(r.data("mCSB_buttonScrollRight"));if(!e){l=true}}else{n=-n}}d.mTweenAxis.call(this,t[0],"left",Math.round(j),draggerSpeed,u.scrollEasing);d.mTweenAxis.call(this,s[0],"left",Math.round(n),g,u.scrollEasing,{onStart:function(){if(u.callbacks&&!r.data("mCS_tweenRunning")){o("onScrollStart")}if(r.data("autoHideScrollbar")){d.showScrollbar.call(q)}},onUpdate:function(){if(u.callbacks){o("whileScrolling")}},onComplete:function(){if(u.callbacks){o("onScroll");if(m||(f&&s.position().left>=f)){o("onTotalScrollBack")}if(l||(e&&s.position().left<=e)){o("onTotalScroll")}}t.data("preventAction",false);r.data("mCS_tweenRunning",false);if(r.data("autoHideScrollbar")){if(!i.hasClass("mCS-mouse-over")){d.hideScrollbar.call(q)}}}})}else{if(r.data("onTotalScrollBack_Offset")){f=-r.data("onTotalScrollBack_Offset")}if(r.data("onTotalScroll_Offset")){e=i.height()-s.outerHeight()+r.data("onTotalScroll_Offset")}if(j<0){j=n=0;clearInterval(r.data("mCSB_buttonScrollUp"));if(!f){m=true}}else{if(j>=h.height()-t.height()){j=h.height()-t.height();n=i.height()-s.outerHeight();clearInterval(r.data("mCSB_buttonScrollDown"));if(!e){l=true}}else{n=-n}}d.mTweenAxis.call(this,t[0],"top",Math.round(j),draggerSpeed,u.scrollEasing);d.mTweenAxis.call(this,s[0],"top",Math.round(n),g,u.scrollEasing,{onStart:function(){if(u.callbacks&&!r.data("mCS_tweenRunning")){o("onScrollStart")}if(r.data("autoHideScrollbar")){d.showScrollbar.call(q)}},onUpdate:function(){if(u.callbacks){o("whileScrolling")}},onComplete:function(){if(u.callbacks){o("onScroll");if(m||(f&&s.position().top>=f)){o("onTotalScrollBack")}if(l||(e&&s.position().top<=e)){o("onTotalScroll")}}t.data("preventAction",false);r.data("mCS_tweenRunning",false);if(r.data("autoHideScrollbar")){if(!i.hasClass("mCS-mouse-over")){d.hideScrollbar.call(q)}}}})}if(r.data("mCS_Init")){r.data({mCS_Init:false})}}}function o(v){this.mcs={top:s.position().top,left:s.position().left,draggerTop:t.position().top,draggerLeft:t.position().left,topPct:Math.round((100*Math.abs(s.position().top))/Math.abs(s.outerHeight()-i.height())),leftPct:Math.round((100*Math.abs(s.position().left))/Math.abs(s.outerWidth()-i.width()))};switch(v){case"onScrollStart":r.data("mCS_tweenRunning",true).data("onScrollStart_Callback").call(r,this.mcs);break;case"whileScrolling":r.data("whileScrolling_Callback").call(r,this.mcs);break;case"onScroll":r.data("onScroll_Callback").call(r,this.mcs);break;case"onTotalScrollBack":r.data("onTotalScrollBack_Callback").call(r,this.mcs);break;case"onTotalScroll":r.data("onTotalScroll_Callback").call(r,this.mcs);break}}},stop:function(){var g=c(this),e=g.children().children(".mCSB_container"),f=g.children().children().children().children(".mCSB_dragger");d.mTweenAxisStop.call(this,e[0]);d.mTweenAxisStop.call(this,f[0])},disable:function(e){var j=c(this),f=j.children(".mCustomScrollBox"),h=f.children(".mCSB_container"),g=f.children(".mCSB_scrollTools"),i=g.children().children(".mCSB_dragger");f.unbind("mousewheel focusin mouseenter mouseleave touchend");h.unbind("touchstart touchmove");if(e){if(j.data("horizontalScroll")){i.add(h).css("left",0)}else{i.add(h).css("top",0)}}g.css("display","none");h.addClass("mCS_no_scrollbar");j.data({bindEvent_mousewheel:false,bindEvent_focusin:false,bindEvent_content_touch:false,bindEvent_autoHideScrollbar:false}).addClass("mCS_disabled")},destroy:function(){var e=c(this);e.removeClass("mCustomScrollbar _mCS_"+e.data("mCustomScrollbarIndex")).addClass("mCS_destroyed").children().children(".mCSB_container").unwrap().children().unwrap().siblings(".mCSB_scrollTools").remove();c(document).unbind("mousemove."+e.data("mCustomScrollbarIndex")+" mouseup."+e.data("mCustomScrollbarIndex")+" MSPointerMove."+e.data("mCustomScrollbarIndex")+" MSPointerUp."+e.data("mCustomScrollbarIndex"));c(window).unbind("resize."+e.data("mCustomScrollbarIndex"))}},d={showScrollbar:function(){this.stop().animate({opacity:1},"fast")},hideScrollbar:function(){this.stop().animate({opacity:0},"fast")},mTweenAxis:function(g,i,h,f,o,y){var y=y||{},v=y.onStart||function(){},p=y.onUpdate||function(){},w=y.onComplete||function(){};var n=t(),l,j=0,r=g.offsetTop,s=g.style;if(i==="left"){r=g.offsetLeft}var m=h-r;q();e();function t(){if(window.performance&&window.performance.now){return window.performance.now()}else{if(window.performance&&window.performance.webkitNow){return window.performance.webkitNow()}else{if(Date.now){return Date.now()}else{return new Date().getTime()}}}}function x(){if(!j){v.call()}j=t()-n;u();if(j>=g._time){g._time=(j>g._time)?j+l-(j-g._time):j+l-1;if(g._time<j+1){g._time=j+1}}if(g._time<f){g._id=_request(x)}else{w.call()}}function u(){if(f>0){g.currVal=k(g._time,r,m,f,o);s[i]=Math.round(g.currVal)+"px"}else{s[i]=h+"px"}p.call()}function e(){l=1000/60;g._time=j+l;_request=(!window.requestAnimationFrame)?function(z){u();return setTimeout(z,0.01)}:window.requestAnimationFrame;g._id=_request(x)}function q(){if(g._id==null){return}if(!window.requestAnimationFrame){clearTimeout(g._id)}else{window.cancelAnimationFrame(g._id)}g._id=null}function k(B,A,F,E,C){switch(C){case"linear":return F*B/E+A;break;case"easeOutQuad":B/=E;return -F*B*(B-2)+A;break;case"easeInOutQuad":B/=E/2;if(B<1){return F/2*B*B+A}B--;return -F/2*(B*(B-2)-1)+A;break;case"easeOutCubic":B/=E;B--;return F*(B*B*B+1)+A;break;case"easeOutQuart":B/=E;B--;return -F*(B*B*B*B-1)+A;break;case"easeOutQuint":B/=E;B--;return F*(B*B*B*B*B+1)+A;break;case"easeOutCirc":B/=E;B--;return F*Math.sqrt(1-B*B)+A;break;case"easeOutSine":return F*Math.sin(B/E*(Math.PI/2))+A;break;case"easeOutExpo":return F*(-Math.pow(2,-10*B/E)+1)+A;break;case"mcsEaseOut":var D=(B/=E)*B,z=D*B;return A+F*(0.499999999999997*z*D+-2.5*D*D+5.5*z+-6.5*D+4*B);break;case"draggerRailEase":B/=E/2;if(B<1){return F/2*B*B*B+A}B-=2;return F/2*(B*B*B+2)+A;break}}},mTweenAxisStop:function(e){if(e._id==null){return}if(!window.requestAnimationFrame){clearTimeout(e._id)}else{window.cancelAnimationFrame(e._id)}e._id=null},rafPolyfill:function(){var f=["ms","moz","webkit","o"],e=f.length;while(--e>-1&&!window.requestAnimationFrame){window.requestAnimationFrame=window[f[e]+"RequestAnimationFrame"];window.cancelAnimationFrame=window[f[e]+"CancelAnimationFrame"]||window[f[e]+"CancelRequestAnimationFrame"]}}};d.rafPolyfill.call();c.support.touch=!!("ontouchstart" in window);c.support.msPointer=window.navigator.msPointerEnabled;var a=("https:"==document.location.protocol)?"https:":"http:";
//c.event.special.mousewheel||document.write('<script src="/'+jsLibPath+'/jquery/ui/jquery.mousewheel.min.js"><\/script>');
c.fn.mCustomScrollbar=function(e){if(b[e]){return b[e].apply(this,Array.prototype.slice.call(arguments,1))}else{if(typeof e==="object"||!e){return b.init.apply(this,arguments)}else{c.error("Method "+e+" does not exist")}}}})(jQuery);/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 * 
 * Requires: 1.2.2+
 */
(function(a){function d(b){var c=b||window.event,d=[].slice.call(arguments,1),e=0,f=!0,g=0,h=0;return b=a.event.fix(c),b.type="mousewheel",c.wheelDelta&&(e=c.wheelDelta/120),c.detail&&(e=-c.detail/3),h=e,c.axis!==undefined&&c.axis===c.HORIZONTAL_AXIS&&(h=0,g=-1*e),c.wheelDeltaY!==undefined&&(h=c.wheelDeltaY/120),c.wheelDeltaX!==undefined&&(g=-1*c.wheelDeltaX/120),d.unshift(b,e,g,h),(a.event.dispatch||a.event.handle).apply(this,d)}var b=["DOMMouseScroll","mousewheel"];if(a.event.fixHooks)for(var c=b.length;c;)a.event.fixHooks[b[--c]]=a.event.mouseHooks;a.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=b.length;a;)this.addEventListener(b[--a],d,!1);else this.onmousewheel=d},teardown:function(){if(this.removeEventListener)for(var a=b.length;a;)this.removeEventListener(b[--a],d,!1);else this.onmousewheel=null}},a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery)
/*!!
 * Title Alert 0.7
 * 
 * Copyright (c) 2009 ESN | http://esn.me
 * Jonatan Heyman | http://heyman.info
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 */
 
/* 
 * @name jQuery.titleAlert
 * @projectDescription Show alert message in the browser title bar
 * @author Jonatan Heyman | http://heyman.info
 * @version 0.7.0
 * @license MIT License
 * 
 * @id jQuery.titleAlert
 * @param {String} text The text that should be flashed in the browser title
 * @param {Object} settings Optional set of settings.
 *	 @option {Number} interval The flashing interval in milliseconds (default: 500).
 *	 @option {Number} originalTitleInterval Time in milliseconds that the original title is diplayed for. If null the time is the same as interval (default: null).
 *	 @option {Number} duration The total lenght of the flashing before it is automatically stopped. Zero means infinite (default: 0).
 *	 @option {Boolean} stopOnFocus If true, the flashing will stop when the window gets focus (default: true).
 *   @option {Boolean} stopOnMouseMove If true, the flashing will stop when the browser window recieves a mousemove event. (default:false).
 *	 @option {Boolean} requireBlur Experimental. If true, the call will be ignored unless the window is out of focus (default: false).
 *                                 Known issues: Firefox doesn't recognize tab switching as blur, and there are some minor IE problems as well.
 *
 * @example $.titleAlert("Hello World!", {requireBlur:true, stopOnFocus:true, duration:10000, interval:500});
 * @desc Flash title bar with text "Hello World!", if the window doesn't have focus, for 10 seconds or until window gets focused, with an interval of 500ms
 */
;(function($){	
	$.titleAlert = function(text, settings) {
		// check if it currently flashing something, if so reset it
		if ($.titleAlert._running)
			$.titleAlert.stop();
		
		// override default settings with specified settings
		$.titleAlert._settings = settings = $.extend( {}, $.titleAlert.defaults, settings);
		
		// if it's required that the window doesn't have focus, and it has, just return
		if (settings.requireBlur && $.titleAlert.hasFocus)
			return;
		
		// originalTitleInterval defaults to interval if not set
		settings.originalTitleInterval = settings.originalTitleInterval || settings.interval;
		
		$.titleAlert._running = true;
		$.titleAlert._initialText = document.title;
		document.title = text;
		var showingAlertTitle = true;
		var switchTitle = function() {
			// WTF! Sometimes Internet Explorer 6 calls the interval function an extra time!
			if (!$.titleAlert._running)
				return;
			
		    showingAlertTitle = !showingAlertTitle;
		    document.title = (showingAlertTitle ? text : $.titleAlert._initialText);
		    $.titleAlert._intervalToken = setTimeout(switchTitle, (showingAlertTitle ? settings.interval : settings.originalTitleInterval));
		}
		$.titleAlert._intervalToken = setTimeout(switchTitle, settings.interval);
		
		if (settings.stopOnMouseMove) {
			$(document).mousemove(function(event) {
				$(this).unbind(event);
				$.titleAlert.stop();
			});
		}
		
		// check if a duration is specified
		if (settings.duration > 0) {
			$.titleAlert._timeoutToken = setTimeout(function() {
				$.titleAlert.stop();
			}, settings.duration);
		}
	};
	
	// default settings
	$.titleAlert.defaults = {
		interval: 500,
		originalTitleInterval: null,
		duration:0,
		stopOnFocus: true,
		requireBlur: false,
		stopOnMouseMove: false
	};
	
	// stop current title flash
	$.titleAlert.stop = function() {
		clearTimeout($.titleAlert._intervalToken);
		clearTimeout($.titleAlert._timeoutToken);
		document.title = $.titleAlert._initialText;
		
		$.titleAlert._timeoutToken = null;
		$.titleAlert._intervalToken = null;
		$.titleAlert._initialText = null;
		$.titleAlert._running = false;
		$.titleAlert._settings = null;
	}
	
	$.titleAlert.hasFocus = true;
	$.titleAlert._running = false;
	$.titleAlert._intervalToken = null;
	$.titleAlert._timeoutToken = null;
	$.titleAlert._initialText = null;
	$.titleAlert._settings = null;
	
	
	$.titleAlert._focus = function () {
		$.titleAlert.hasFocus = true;
		
		if ($.titleAlert._running && $.titleAlert._settings.stopOnFocus) {
			var initialText = $.titleAlert._initialText;
			$.titleAlert.stop();
			
			// ugly hack because of a bug in Chrome which causes a change of document.title immediately after tab switch
			// to have no effect on the browser title
			setTimeout(function() {
				if ($.titleAlert._running)
					return;
				document.title = ".";
				document.title = initialText;
			}, 1000);
		}
	};
	$.titleAlert._blur = function () {
		$.titleAlert.hasFocus = false;
	};
	
	// bind focus and blur event handlers
	$(window).bind("focus", $.titleAlert._focus);
	$(window).bind("blur", $.titleAlert._blur);
})(jQuery);
/*
 *	TinyRichEditor - jQuery plugin v.1.0.0
 *	Copyright 2011 Sergey Smelov
*/

(function($) {
	
	$.treditor = {
	
		defaults : {
			width: 400,
			height: 250,
			buttonbar: "bold italic strikethrough | unorderedlist orderedlist | alignleft aligncenter alignright | link image | source",
			bodyStyle: "margin:4px; font:10pt Arial,Verdana; cursor:text"
		},
		buttons :
		{
			bold : { name : "bold", title: "Bold", command: "bold", imgIndex : 0 },
			italic : { name: "italic", title: "Italic", command: "italic", imgIndex : 1 },
			strikethrough : { name: "strikethrough", title:"Strikethrough", command: "strikethrough", imgIndex : 2 },
			unorderedlist : { name : "unorderedlist", title: "Unordered list", command: "insertunorderedlist", imgIndex : 3 },
			orderedlist : { name: "orderedlist", title: "Ordered list", command: "insertorderedlist", imgIndex : 4 },
			alignleft : { name: "alignleft", title: "Align left", command: "justifyleft", imgIndex : 5 },
			aligncenter: { name: "aligncenter", title: "Align center", command: "justifycenter", imgIndex : 6},
			alignright : { name: "alignright", title: "Align right", command: "justifyright", imgIndex : 7 },
			link : { name: "link", title: "Hyperlink", command: "link", imgIndex : 10 },
			image : { name: "image", title: "Image", command: "image", imgIndex : 9 },
			source : { name: "source", title: "Show source", command: "source", imgIndex : 12 }
		}
	}
	
	$.fn.treditor = function(options) {
		
		return this.each(function(idx, elem) {
			if (elem.tagName == "TEXTAREA")
			{
				new treditor(elem, options);
			}			
		});		
	}
	
	///////////////////////
	//Private Variables	
	///////////////////////
	var isIE = $.support.boxModel;//$.browser.msie;
	
	
	///////////////////////
	//Constructor		
	///////////////////////
	treditor = function(textarea, options) {
	
		var editor = this;
		
		//merge defaults and options without modifying defaults
		editor.settings = $.extend({}, $.treditor.defaults, options);
		
		editor.disabled=false;
		
		//hide textarea
		editor.area = $(textarea)
						.hide()
						.blur(function() { 
							updateIFrame(editor);
						});
	
		//create main container
		editor.main = $("<div>").addClass("tre-main");
								
								
		
		//create button bar
		createButtonBar(editor);
		
		//create iframe
		createIFrame(editor);
	}
		
	/////////////////////
	//Event Handlers	
	/////////////////////
	function buttonClick(e) {
			
		var editor = this,
		    li = e.target,
			buttonName = $.data(li, 'buttonName'),
			button = $.treditor.buttons[buttonName];
		
		if (buttonName == "source") {
							
			if (editor.disabled)
			{				
				editor.disabled=false;
				editor.frame.css("display","block");
				editor.area.css("display","none");
				enableButtons(editor);											
				$(li).css('backgroundPosition', 12 * -24);
				$(li).removeAttr('title');
				$(li).attr('title', 'Show source');
			}
			else
			{
				editor.disabled=true;
				editor.frame.css("display","none");
				editor.area.css("display","block");
				disableButtons(editor);
				$(li).css('backgroundPosition', 13 * -24);
				$(li).removeAttr('title');
				$(li).attr('title', 'Show rich text');				
			}			
		}
		else if (!execCommand(editor, button.command, li))
			return false;

		focus(editor);
	}

	function hoverIn(e) {
		$(e.target).addClass("tre-hover");
	}
	
	function hoverOut(e) {
		$(e.target).removeClass("tre-hover");
	}

	//////////////////////
	//Private Functions
	//////////////////////
	
	function createButtonBar(editor) {
	
		//create the button-bar
		var buttonbar = editor.buttonbar = $("<div>").addClass("tre-button-bar").appendTo(editor.main);
		
		//add the first group to toolbar
		var ul = $("<ul></ul>").appendTo(buttonbar);
		$("<br /><br />").appendTo(buttonbar);
		//add buttons to the toolbar
		$.each(editor.settings.buttonbar.split(" "), function(idx, buttonName) {
		
			if (buttonName == "") return true;
		
			if (buttonName == "|")
			{
				$("<li>|</li>").addClass("tre-separator").appendTo(ul);
			}
			else
			{
				var button = $.treditor.buttons[buttonName];
				
				var li = $("<li>")
					.data("buttonName", button.name)
					.addClass('tre-button')
					.attr("title", button.title)
					.bind("click", $.proxy(buttonClick, editor))
					.hover(hoverIn, hoverOut)
					.appendTo(ul);
								
				//prepare button image
				li.css('backgroundPosition', button.imgIndex * -24);
				
			    if (isIE)
					li.attr("unselectable", "on");				
			}
		
		});
			
		editor.main.insertBefore(editor.area).append(editor.area);	
	};
		
	function createIFrame(editor) {
			
		if (editor.frame) 
			editor.frame.remove();
			
		editor.frame = $('<iframe id="tre-iframe" frameborder="0">').hide().appendTo(editor.main);	
		editor.doc = editor.frame[0].contentWindow.document;
		
		editor.doc.open();
		editor.doc.write('<html><head><body onblur="" style="' + editor.settings.bodyStyle + '"></body></html>');
		editor.doc.close();
				
		//load content
		var code = editor.area.val();		
		code = code.replace(/<(?=\/?script)/ig, "&lt;");
		$(editor.doc.body).html(code);
		

		$(editor.frame[0].contentWindow)
					.blur( function() {
							updateTextArea(editor);
							
					});		
		
		editor.frame.show();
		
		var wid = editor.main.width();	
		//editor.buttonbar.height(25);
		editor.frame.width(editor.settings.width).height(editor.settings.height);
		editor.area.width(editor.settings.width).height(editor.settings.height);


		try {
			if (isIE) editor.doc.body.contentEditable = true;
			else editor.doc.designMode = 'on';
		}
		catch(e) {
		}
		
	}
	
	function execCommand(editor, command, button) {	
		return editor.doc.execCommand(command, 0, null);				
	}
		
	function enableButtons(editor) {
		
		$.each(editor.buttonbar.find(".tre-button"), function(idx, elem) {
						
			var button = $.treditor.buttons[$.data(elem, "buttonName")];
			
			if (button.name != "source") {					
				$(elem).removeClass("tre-disabled");			
				$(elem).removeAttr("disabled");		
				$(elem).hover(hoverIn, hoverOut)
			}				
		});	
	}
	
	function disableButtons(editor) {
		
		$.each(editor.buttonbar.find(".tre-button"), function(idx, elem) {
						
			var button = $.treditor.buttons[$.data(elem, "buttonName")];
			
			if (button.name !="source") {			
				$(elem).addClass("tre-disabled");
				$(elem).attr("disabled", "disabled");
				$(elem).unbind('mouseenter mouseleave');
			}
		});
	}
	
	function updateTextArea(editor) {
		
		var html = $(editor.doc.body).html();					
		editor.area.html(html);		
		editor.area.val(html);		
	}
	
	function updateIFrame(editor) {
	
		var html = editor.area.val();		
		//html = html.replace();
		$(editor.doc.body).html(html);	
	}
	
	function focus(editor) {
		
		if (editor.disabled) editor.area.focus();
			else editor.frame[0].contentWindow.focus();      

	}
		
}) (jQuery);/*
 * jQuery validation plug-in pre-1.5.2
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright (c) 2006 - 2008 JÃ¶rn Zaefferer
 *
 * $Id: jquery.validate.js 6243 2009-02-19 11:40:49Z joern.zaefferer $
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

(function($) {

$.extend($.fn, {
	// http://docs.jquery.com/Plugins/Validation/validate
	validate: function( options ) {
		
		// if nothing is selected, return nothing; can't chain anyway
		if (!this.length) {
			options && options.debug && window.console && console.warn( "nothing selected, can't validate, returning nothing" );
			return;
		}
		
		// check if a validator for this form was already created
		var validator = $.data(this[0], 'validator');
		if ( validator ) {
			return validator;
		}
		
		validator = new $.validator( options, this[0] );
		$.data(this[0], 'validator', validator); 
		
		if ( validator.settings.onsubmit ) {
		
			// allow suppresing validation by adding a cancel class to the submit button
			this.find("input, button").filter(".cancel").click(function() {
				validator.cancelSubmit = true;
			});
		
			// validate the form on submit
			this.submit( function( event ) {
				if ( validator.settings.debug )
					// prevent form submit to be able to see console output
					event.preventDefault();
					
				function handle() {
					if ( validator.settings.submitHandler ) {
						validator.settings.submitHandler.call( validator, validator.currentForm );
						return false;
					}
					return true;
				}
					
				// prevent submit for invalid forms or custom submit handlers
				if ( validator.cancelSubmit ) {
					validator.cancelSubmit = false;
					return handle();
				}
				if ( validator.form() ) {
					if ( validator.pendingRequest ) {
						validator.formSubmitted = true;
						return false;
					}
					return handle();
				} else {
					validator.focusInvalid();
					return false;
				}
			});
		}
		
		return validator;
	},
	// http://docs.jquery.com/Plugins/Validation/valid
	valid: function() {
        if ( $(this[0]).is('form')) {
            return this.validate().form();
        } else {
            var valid = false;
            var validator = $(this[0].form).validate();
            this.each(function() {
				valid |= validator.element(this);
            });
            return valid;
        }
    },
	// attributes: space seperated list of attributes to retrieve and remove
	removeAttrs: function(attributes) {
		var result = {},
			$element = this;
		$.each(attributes.split(/\s/), function(index, value) {
			result[value] = $element.attr(value);
			$element.removeAttr(value);
		});
		return result;
	},
	// http://docs.jquery.com/Plugins/Validation/rules
	rules: function(command, argument) {
		var element = this[0];
		
		if (command) {
			var settings = $.data(element.form, 'validator').settings;
			var staticRules = settings.rules;
			var existingRules = $.validator.staticRules(element);
			switch(command) {
			case "add":
				$.extend(existingRules, $.validator.normalizeRule(argument));
				staticRules[element.name] = existingRules;
				if (argument.messages)
					settings.messages[element.name] = $.extend( settings.messages[element.name], argument.messages );
				break;
			case "remove":
				if (!argument) {
					delete staticRules[element.name];
					return existingRules;
				}
				var filtered = {};
				$.each(argument.split(/\s/), function(index, method) {
					filtered[method] = existingRules[method];
					delete existingRules[method];
				});
				return filtered;
			}
		}
		
		var data = $.validator.normalizeRules(
		$.extend(
			{},
			$.validator.metadataRules(element),
			$.validator.classRules(element),
			$.validator.attributeRules(element),
			$.validator.staticRules(element)
		), element);
		
		// make sure required is at front
		if (data.required) {
			var param = data.required;
			delete data.required;
			data = $.extend({required: param}, data);
		}
		
		return data;
	}
});

// Custom selectors
$.extend($.expr[":"], {
	// http://docs.jquery.com/Plugins/Validation/blank
	blank: function(a) {return !$.trim(a.value);},
	// http://docs.jquery.com/Plugins/Validation/filled
	filled: function(a) {return !!$.trim(a.value);},
	// http://docs.jquery.com/Plugins/Validation/unchecked
	unchecked: function(a) {return !a.checked;}
});


$.format = function(source, params) {
	if ( arguments.length == 1 ) 
		return function() {
			var args = $.makeArray(arguments);
			args.unshift(source);
			return $.format.apply( this, args );
		};
	if ( arguments.length > 2 && params.constructor != Array  ) {
		params = $.makeArray(arguments).slice(1);
	}
	if ( params.constructor != Array ) {
		params = [ params ];
	}
	$.each(params, function(i, n) {
		source = source.replace(new RegExp("\\{" + i + "\\}", "g"), n);
	});
	return source;
};

// constructor for validator
$.validator = function( options, form ) {
	this.settings = $.extend( {}, $.validator.defaults, options );
	this.currentForm = form;
	this.init();
};

$.extend($.validator, {

	defaults: {
		messages: {},
		groups: {},
		rules: {},
		errorClass: "error",
		errorElement: "label",
		focusInvalid: true,
		errorContainer: $( [] ),
		errorLabelContainer: $( [] ),
		onsubmit: true,
		ignore: [],
		ignoreTitle: false,
		onfocusin: function(element) {
			this.lastActive = element;
				
			// hide error label and remove error class on focus if enabled
			if ( this.settings.focusCleanup && !this.blockFocusCleanup ) {
				this.settings.unhighlight && this.settings.unhighlight.call( this, element, this.settings.errorClass );
				this.errorsFor(element).hide();
			}
		},
		onfocusout: function(element) {
			if ( !this.checkable(element) && (element.name in this.submitted || !this.optional(element)) ) {
				this.element(element);
			}
		},
		onkeyup: function(element) {
			if ( element.name in this.submitted || element == this.lastElement ) {
				this.element(element);
			}
		},
		onclick: function(element) {
			if ( element.name in this.submitted )
				this.element(element);
		},
		highlight: function( element, errorClass ) {
			$( element ).addClass( errorClass );
		},
		unhighlight: function( element, errorClass ) {
			$( element ).removeClass( errorClass );
		}
	},

	// http://docs.jquery.com/Plugins/Validation/Validator/setDefaults
	setDefaults: function(settings) {
		$.extend( $.validator.defaults, settings );
	},

	messages: {
		required: "This field is required.",
		remote: "Please fix this field.",
		email: "Please enter a valid email address.",
		url: "Please enter a valid URL.",
		date: "Please enter a valid date.",
		dateISO: "Please enter a valid date (ISO).",
		dateDE: "Bitte geben Sie ein gÃ¼ltiges Datum ein.",
		number: "Please enter a valid number.",
		numberDE: "Bitte geben Sie eine Nummer ein.",
		digits: "Please enter only digits",
		creditcard: "Please enter a valid credit card number.",
		equalTo: "Please enter the same value again.",
		accept: "Please enter a value with a valid extension.",
		maxlength: $.format("Please enter no more than {0} characters."),
		minlength: $.format("Please enter at least {0} characters."),
		rangelength: $.format("Please enter a value between {0} and {1} characters long."),
		range: $.format("Please enter a value between {0} and {1}."),
		max: $.format("Please enter a value less than or equal to {0}."),
		min: $.format("Please enter a value greater than or equal to {0}.")
	},
	
	autoCreateRanges: false,
	
	prototype: {
		
		init: function() {
			this.labelContainer = $(this.settings.errorLabelContainer);
			this.errorContext = this.labelContainer.length && this.labelContainer || $(this.currentForm);
			this.containers = $(this.settings.errorContainer).add( this.settings.errorLabelContainer );
			this.submitted = {};
			this.valueCache = {};
			this.pendingRequest = 0;
			this.pending = {};
			this.invalid = {};
			this.reset();
			
			var groups = (this.groups = {});
			$.each(this.settings.groups, function(key, value) {
				$.each(value.split(/\s/), function(index, name) {
					groups[name] = key;
				});
			});
			var rules = this.settings.rules;
			$.each(rules, function(key, value) {
				rules[key] = $.validator.normalizeRule(value);
			});
			
			function delegate(event) {
				var validator = $.data(this[0].form, "validator");
				validator.settings["on" + event.type] && validator.settings["on" + event.type].call(validator, this[0] );
			}
			$(this.currentForm)
				.delegate("focusin focusout keyup", ":text, :password, :file, select, textarea", delegate)
				.delegate("click", ":radio, :checkbox", delegate);

			if (this.settings.invalidHandler)
				$(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler);
		},

		// http://docs.jquery.com/Plugins/Validation/Validator/form
		form: function() {
			this.checkForm();
			$.extend(this.submitted, this.errorMap);
			this.invalid = $.extend({}, this.errorMap);
			if (!this.valid())
				$(this.currentForm).triggerHandler("invalid-form", [this]);
			this.showErrors();
			return this.valid();
		},
		
		checkForm: function() {
			this.prepareForm();
			for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
				this.check( elements[i] );
			}
			return this.valid(); 
		},
		
		// http://docs.jquery.com/Plugins/Validation/Validator/element
		element: function( element ) {
			element = this.clean( element );
			this.lastElement = element;
			this.prepareElement( element );
			this.currentElements = $(element);
			var result = this.check( element );
			if ( result ) {
				delete this.invalid[element.name];
			} else {
				this.invalid[element.name] = true;
			}
			if ( !this.numberOfInvalids() ) {
				// Hide error containers on last error
				this.toHide = this.toHide.add( this.containers );
			}
			this.showErrors();
			return result;
		},

		// http://docs.jquery.com/Plugins/Validation/Validator/showErrors
		showErrors: function(errors) {
			if(errors) {
				// add items to error list and map
				$.extend( this.errorMap, errors );
				this.errorList = [];
				for ( var name in errors ) {
					this.errorList.push({
						message: errors[name],
						element: this.findByName(name)[0]
					});
				}
				// remove items from success list
				this.successList = $.grep( this.successList, function(element) {
					return !(element.name in errors);
				});
			}
			this.settings.showErrors
				? this.settings.showErrors.call( this, this.errorMap, this.errorList )
				: this.defaultShowErrors();
		},
		
		// http://docs.jquery.com/Plugins/Validation/Validator/resetForm
		resetForm: function() {
			if ( $.fn.resetForm )
				$( this.currentForm ).resetForm();
			this.submitted = {};
			this.prepareForm();
			this.hideErrors();
			this.elements().removeClass( this.settings.errorClass );
		},
		
		numberOfInvalids: function() {
			return this.objectLength(this.invalid);
		},
		
		objectLength: function( obj ) {
			var count = 0;
			for ( var i in obj )
				count++;
			return count;
		},
		
		hideErrors: function() {
			this.addWrapper( this.toHide ).hide();
		},
		
		valid: function() {
			return this.size() == 0;
		},
		
		size: function() {
			return this.errorList.length;
		},
		
		focusInvalid: function() {
			if( this.settings.focusInvalid ) {
				try {
					$(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").focus();
				} catch(e) {
					// ignore IE throwing errors when focusing hidden elements
				}
			}
		},
		
		findLastActive: function() {
			var lastActive = this.lastActive;
			return lastActive && $.grep(this.errorList, function(n) {
				return n.element.name == lastActive.name;
			}).length == 1 && lastActive;
		},
		
		elements: function() {
			var validator = this,
				rulesCache = {};
			
			// select all valid inputs inside the form (no submit or reset buttons)
			// workaround $Query([]).add until http://dev.jquery.com/ticket/2114 is solved
			return $([]).add(this.currentForm.elements)
			.filter(":input")
			.not(":submit, :reset, :image, [disabled]")
			.not( this.settings.ignore )
			.filter(function() {
				!this.name && validator.settings.debug && window.console && console.error( "%o has no name assigned", this);
			
				// select only the first element for each name, and only those with rules specified
				if ( this.name in rulesCache || !validator.objectLength($(this).rules()) )
					return false;
				
				rulesCache[this.name] = true;
				return true;
			});
		},
		
		clean: function( selector ) {
			return $( selector )[0];
		},
		
		errors: function() {
			return $( this.settings.errorElement + "." + this.settings.errorClass, this.errorContext );
		},
		
		reset: function() {
			this.successList = [];
			this.errorList = [];
			this.errorMap = {};
			this.toShow = $([]);
			this.toHide = $([]);
			this.formSubmitted = false;
			this.currentElements = $([]);
		},
		
		prepareForm: function() {
			this.reset();
			this.toHide = this.errors().add( this.containers );
		},
		
		prepareElement: function( element ) {
			this.reset();
			this.toHide = this.errorsFor(element);
		},
	
		check: function( element ) {
			element = this.clean( element );
			
			// if radio/checkbox, validate first element in group instead
			if (this.checkable(element)) {
				element = this.findByName( element.name )[0];
			}
			
			var rules = $(element).rules();
			var dependencyMismatch = false;
			for( method in rules ) {
				var rule = { method: method, parameters: rules[method] };
				try {
					var result = $.validator.methods[method].call( this, element.value.replace(/\r/g, ""), element, rule.parameters );
					
					// if a method indicates that the field is optional and therefore valid,
					// don't mark it as valid when there are no other rules
					if ( result == "dependency-mismatch" ) {
						dependencyMismatch = true;
						continue;
					}
					dependencyMismatch = false;
					
					if ( result == "pending" ) {
						this.toHide = this.toHide.not( this.errorsFor(element) );
						return;
					}
					
					if( !result ) {
						this.formatAndAdd( element, rule );
						return false;
					}
				} catch(e) {
					this.settings.debug && window.console && console.log("exception occured when checking element " + element.id
						 + ", check the '" + rule.method + "' method");
					throw e;
				}
			}
			if (dependencyMismatch)
				return;
			if ( this.objectLength(rules) )
				this.successList.push(element);
			return true;
		},
		
		// return the custom message for the given element and validation method
		// specified in the element's "messages" metadata
		customMetaMessage: function(element, method) {
			if (!$.metadata)
				return;
			
			var meta = this.settings.meta
				? $(element).metadata()[this.settings.meta]
				: $(element).metadata();
			
			return meta && meta.messages && meta.messages[method];
		},
		
		// return the custom message for the given element name and validation method
		customMessage: function( name, method ) {
			var m = this.settings.messages[name];
			return m && (m.constructor == String
				? m
				: m[method]);
		},
		
		// return the first defined argument, allowing empty strings
		findDefined: function() {
			for(var i = 0; i < arguments.length; i++) {
				if (arguments[i] !== undefined)
					return arguments[i];
			}
			return undefined;
		},
		
		defaultMessage: function( element, method) {
			return this.findDefined(
				this.customMessage( element.name, method ),
				this.customMetaMessage( element, method ),
				// title is never undefined, so handle empty string as undefined
				!this.settings.ignoreTitle && element.title || undefined,
				$.validator.messages[method],
				"<strong>Warning: No message defined for " + element.name + "</strong>"
			);
		},
		
		formatAndAdd: function( element, rule ) {
			var message = this.defaultMessage( element, rule.method );
			if ( typeof message == "function" ) 
				message = message.call(this, rule.parameters, element);
			this.errorList.push({
				message: message,
				element: element
			});
			this.errorMap[element.name] = message;
			this.submitted[element.name] = message;
		},
		
		addWrapper: function(toToggle) {
			if ( this.settings.wrapper )
				toToggle = toToggle.add( toToggle.parents( this.settings.wrapper ) );
			return toToggle;
		},
		
		defaultShowErrors: function() {
			for ( var i = 0; this.errorList[i]; i++ ) {
				var error = this.errorList[i];
				this.settings.highlight && this.settings.highlight.call( this, error.element, this.settings.errorClass );
				this.showLabel( error.element, error.message );
			}
			if( this.errorList.length ) {
				this.toShow = this.toShow.add( this.containers );
			}
			if (this.settings.success) {
				for ( var i = 0; this.successList[i]; i++ ) {
					this.showLabel( this.successList[i] );
				}
			}
			if (this.settings.unhighlight) {
				for ( var i = 0, elements = this.validElements(); elements[i]; i++ ) {
					this.settings.unhighlight.call( this, elements[i], this.settings.errorClass );
				}
			}
			this.toHide = this.toHide.not( this.toShow );
			this.hideErrors();
			this.addWrapper( this.toShow ).show();
		},
		
		validElements: function() {
			return this.currentElements.not(this.invalidElements());
		},
		
		invalidElements: function() {
			return $(this.errorList).map(function() {
				return this.element;
			});
		},
		
		showLabel: function(element, message) {
			var label = this.errorsFor( element );
			if ( label.length ) {
				// refresh error/success class
				label.removeClass().addClass( this.settings.errorClass );
			
				// check if we have a generated label, replace the message then
				label.attr("generated") && label.html(message);
			} else {
				// create label
				label = $("<" + this.settings.errorElement + "/>")
					.attr({"for":  this.idOrName(element), generated: true})
					.addClass(this.settings.errorClass)
					.html(message || "");
				if ( this.settings.wrapper ) {
					// make sure the element is visible, even in IE
					// actually showing the wrapped element is handled elsewhere
					label = label.hide().show().wrap("<" + this.settings.wrapper + "/>").parent();
				}
				if ( !this.labelContainer.append(label).length )
					this.settings.errorPlacement
						? this.settings.errorPlacement(label, $(element) )
						: label.insertAfter(element);
			}
			if ( !message && this.settings.success ) {
				label.text("");
				typeof this.settings.success == "string"
					? label.addClass( this.settings.success )
					: this.settings.success( label );
			}
			this.toShow = this.toShow.add(label);
		},
		
		errorsFor: function(element) {
			return this.errors().filter("[for='" + this.idOrName(element) + "']");
		},
		
		idOrName: function(element) {
			return this.groups[element.name] || (this.checkable(element) ? element.name : element.id || element.name);
		},

		checkable: function( element ) {
			return /radio|checkbox/i.test(element.type);
		},
		
		findByName: function( name ) {
			// select by name and filter by form for performance over form.find("[name=...]")
			var form = this.currentForm;
			return $(document.getElementsByName(name)).map(function(index, element) {
				return element.form == form && element.name == name && element  || null;
			});
		},
		
		getLength: function(value, element) {
			switch( element.nodeName.toLowerCase() ) {
			case 'select':
				return $("option:selected", element).length;
			case 'input':
				if( this.checkable( element) )
					return this.findByName(element.name).filter(':checked').length;
			}
			return value.length;
		},
	
		depend: function(param, element) {
			return this.dependTypes[typeof param]
				? this.dependTypes[typeof param](param, element)
				: true;
		},
	
		dependTypes: {
			"boolean": function(param, element) {
				return param;
			},
			"string": function(param, element) {
				return !!$(param, element.form).length;
			},
			"function": function(param, element) {
				return param(element);
			}
		},
		
		optional: function(element) {
			return !$.validator.methods.required.call(this, $.trim(element.value), element) && "dependency-mismatch";
		},
		
		startRequest: function(element) {
			if (!this.pending[element.name]) {
				this.pendingRequest++;
				this.pending[element.name] = true;
			}
		},
		
		stopRequest: function(element, valid) {
			this.pendingRequest--;
			// sometimes synchronization fails, make sure pendingRequest is never < 0
			if (this.pendingRequest < 0)
				this.pendingRequest = 0;
			delete this.pending[element.name];
			if ( valid && this.pendingRequest == 0 && this.formSubmitted && this.form() ) {
				$(this.currentForm).submit();
			} else if (!valid && this.pendingRequest == 0 && this.formSubmitted) {
				$(this.currentForm).triggerHandler("invalid-form", [this]);
			}
		},
		
		previousValue: function(element) {
			return $.data(element, "previousValue") || $.data(element, "previousValue", previous = {
				old: null,
				valid: true,
				message: this.defaultMessage( element, "remote" )
			});
		}
		
	},
	
	classRuleSettings: {
		required: {required: true},
		email: {email: true},
		url: {url: true},
		date: {date: true},
		dateISO: {dateISO: true},
		dateDE: {dateDE: true},
		number: {number: true},
		numberDE: {numberDE: true},
		digits: {digits: true},
		creditcard: {creditcard: true}
	},
	
	addClassRules: function(className, rules) {
		className.constructor == String ?
			this.classRuleSettings[className] = rules :
			$.extend(this.classRuleSettings, className);
	},
	
	classRules: function(element) {
		var rules = {};
		var classes = $(element).attr('class');
		classes && $.each(classes.split(' '), function() {
			if (this in $.validator.classRuleSettings) {
				$.extend(rules, $.validator.classRuleSettings[this]);
			}
		});
		return rules;
	},
	
	attributeRules: function(element) {
		var rules = {};
		var $element = $(element);
		
		for (method in $.validator.methods) {
			var value = $element.attr(method);
			if (value) {
				rules[method] = value;
			}
		}
		
		// maxlength may be returned as -1, 2147483647 (IE) and 524288 (safari) for text inputs
		if (rules.maxlength && /-1|2147483647|524288/.test(rules.maxlength)) {
			delete rules.maxlength;
		}
		
		return rules;
	},
	
	metadataRules: function(element) {
		if (!$.metadata) return {};
		
		var meta = $.data(element.form, 'validator').settings.meta;
		return meta ?
			$(element).metadata()[meta] :
			$(element).metadata();
	},
	
	staticRules: function(element) {
		var rules = {};
		var validator = $.data(element.form, 'validator');
		if (validator.settings.rules) {
			rules = $.validator.normalizeRule(validator.settings.rules[element.name]) || {};
		}
		return rules;
	},
	
	normalizeRules: function(rules, element) {
		// handle dependency check
		$.each(rules, function(prop, val) {
			// ignore rule when param is explicitly false, eg. required:false
			if (val === false) {
				delete rules[prop];
				return;
			}
			if (val.param || val.depends) {
				var keepRule = true;
				switch (typeof val.depends) {
					case "string":
						keepRule = !!$(val.depends, element.form).length;
						break;
					case "function":
						keepRule = val.depends.call(element, element);
						break;
				}
				if (keepRule) {
					rules[prop] = val.param !== undefined ? val.param : true;
				} else {
					delete rules[prop];
				}
			}
		});
		
		// evaluate parameters
		$.each(rules, function(rule, parameter) {
			rules[rule] = $.isFunction(parameter) ? parameter(element) : parameter;
		});
		
		// clean number parameters
		$.each(['minlength', 'maxlength', 'min', 'max'], function() {
			if (rules[this]) {
				rules[this] = Number(rules[this]);
			}
		});
		$.each(['rangelength', 'range'], function() {
			if (rules[this]) {
				rules[this] = [Number(rules[this][0]), Number(rules[this][1])];
			}
		});
		
		if ($.validator.autoCreateRanges) {
			// auto-create ranges
			if (rules.min && rules.max) {
				rules.range = [rules.min, rules.max];
				delete rules.min;
				delete rules.max;
			}
			if (rules.minlength && rules.maxlength) {
				rules.rangelength = [rules.minlength, rules.maxlength];
				delete rules.minlength;
				delete rules.maxlength;
			}
		}
		
		// To support custom messages in metadata ignore rule methods titled "messages"
		if (rules.messages) {
			delete rules.messages
		}
		
		return rules;
	},
	
	// Converts a simple string to a {string: true} rule, e.g., "required" to {required:true}
	normalizeRule: function(data) {
		if( typeof data == "string" ) {
			var transformed = {};
			$.each(data.split(/\s/), function() {
				transformed[this] = true;
			});
			data = transformed;
		}
		return data;
	},
	
	// http://docs.jquery.com/Plugins/Validation/Validator/addMethod
	addMethod: function(name, method, message) {
		$.validator.methods[name] = method;
		$.validator.messages[name] = message;
		if (method.length < 3) {
			$.validator.addClassRules(name, $.validator.normalizeRule(name));
		}
	},

	methods: {

		// http://docs.jquery.com/Plugins/Validation/Methods/required
		required: function(value, element, param) {
			// check if dependency is met
			if ( !this.depend(param, element) )
				return "dependency-mismatch";
			switch( element.nodeName.toLowerCase() ) {
			case 'select':
				var options = $("option:selected", element);
				return options.length > 0 && ( element.type == "select-multiple" || ($.browser.msie && !(options[0].attributes['value'].specified) ? options[0].text : options[0].value).length > 0);
			case 'input':
				if ( this.checkable(element) )
					return this.getLength(value, element) > 0;
			default:
				return $.trim(value).length > 0;
			}
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/remote
		remote: function(value, element, param) {
			if ( this.optional(element) )
				return "dependency-mismatch";
			
			var previous = this.previousValue(element);
			
			if (!this.settings.messages[element.name] )
				this.settings.messages[element.name] = {};
			this.settings.messages[element.name].remote = typeof previous.message == "function" ? previous.message(value) : previous.message;
			
			param = typeof param == "string" && {url:param} || param; 
			
			if ( previous.old !== value ) {
				previous.old = value;
				var validator = this;
				this.startRequest(element);
				var data = {};
				data[element.name] = value;
				$.ajax($.extend(true, {
					url: param,
					mode: "abort",
					port: "validate" + element.name,
					dataType: "json",
					data: data,
					success: function(response) {
						if ( response ) {
							var submitted = validator.formSubmitted;
							validator.prepareElement(element);
							validator.formSubmitted = submitted;
							validator.successList.push(element);
							validator.showErrors();
						} else {
							var errors = {};
							errors[element.name] =  response || validator.defaultMessage( element, "remote" );
							validator.showErrors(errors);
						}
						previous.valid = response;
						validator.stopRequest(element, response);
					}
				}, param));
				return "pending";
			} else if( this.pending[element.name] ) {
				return "pending";
			}
			return previous.valid;
		},

		// http://docs.jquery.com/Plugins/Validation/Methods/minlength
		minlength: function(value, element, param) {
			return this.optional(element) || this.getLength($.trim(value), element) >= param;
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/maxlength
		maxlength: function(value, element, param) {
			return this.optional(element) || this.getLength($.trim(value), element) <= param;
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/rangelength
		rangelength: function(value, element, param) {
			var length = this.getLength($.trim(value), element);
			return this.optional(element) || ( length >= param[0] && length <= param[1] );
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/min
		min: function( value, element, param ) {
			return this.optional(element) || value >= param;
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/max
		max: function( value, element, param ) {
			return this.optional(element) || value <= param;
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/range
		range: function( value, element, param ) {
			return this.optional(element) || ( value >= param[0] && value <= param[1] );
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/email
		email: function(value, element) {
			// contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
			return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
		},
	
		// http://docs.jquery.com/Plugins/Validation/Methods/url
		url: function(value, element) {
			// contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
			return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
		},
        
		// http://docs.jquery.com/Plugins/Validation/Methods/date
		date: function(value, element) {
			return this.optional(element) || !/Invalid|NaN/.test(new Date(value));
		},
	
		// http://docs.jquery.com/Plugins/Validation/Methods/dateISO
		dateISO: function(value, element) {
			return this.optional(element) || /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(value);
		},
	
		// http://docs.jquery.com/Plugins/Validation/Methods/dateDE
		dateDE: function(value, element) {
			return this.optional(element) || /^\d\d?\.\d\d?\.\d\d\d?\d?$/.test(value);
		},
	
		// http://docs.jquery.com/Plugins/Validation/Methods/number
		number: function(value, element) {
			return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
		},
	
		// http://docs.jquery.com/Plugins/Validation/Methods/numberDE
		numberDE: function(value, element) {
			return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d+)?$/.test(value);
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/digits
		digits: function(value, element) {
			return this.optional(element) || /^\d+$/.test(value);
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/creditcard
		// based on http://en.wikipedia.org/wiki/Luhn
		creditcard: function(value, element) {
			if ( this.optional(element) )
				return "dependency-mismatch";
			// accept only digits and dashes
			if (/[^0-9-]+/.test(value))
				return false;
			var nCheck = 0,
				nDigit = 0,
				bEven = false;

			value = value.replace(/\D/g, "");

			for (n = value.length - 1; n >= 0; n--) {
				var cDigit = value.charAt(n);
				var nDigit = parseInt(cDigit, 10);
				if (bEven) {
					if ((nDigit *= 2) > 9)
						nDigit -= 9;
				}
				nCheck += nDigit;
				bEven = !bEven;
			}

			return (nCheck % 10) == 0;
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/accept
		accept: function(value, element, param) {
			param = typeof param == "string" ? param : "png|jpe?g|gif";
			return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i")); 
		},
		
		// http://docs.jquery.com/Plugins/Validation/Methods/equalTo
		equalTo: function(value, element, param) {
			return value == $(param).val();
		}
		
	}
	
});

})(jQuery);

// ajax mode: abort
// usage: $.ajax({ mode: "abort"[, port: "uniqueport"]});
// if mode:"abort" is used, the previous request on that port (port can be undefined) is aborted via XMLHttpRequest.abort() 
;(function($) {
	var ajax = $.ajax;
	var pendingRequests = {};
	$.ajax = function(settings) {
		// create settings for compatibility with ajaxSetup
		settings = $.extend(settings, $.extend({}, $.ajaxSettings, settings));
		var port = settings.port;
		if (settings.mode == "abort") {
			if ( pendingRequests[port] ) {
				pendingRequests[port].abort();
			}
			return (pendingRequests[port] = ajax.apply(this, arguments));
		}
		return ajax.apply(this, arguments);
	};
})(jQuery);

// provides cross-browser focusin and focusout events
// IE has native support, in other browsers, use event caputuring (neither bubbles)

// provides delegate(type: String, delegate: Selector, handler: Callback) plugin for easier event delegation
// handler is only called when $(event.target).is(delegate), in the scope of the jquery-object for event.target 

// provides triggerEvent(type: String, target: Element) to trigger delegated events
;(function($) {
	$.each({
		focus: 'focusin',
		blur: 'focusout'	
	}, function( original, fix ){
		$.event.special[fix] = {
			setup:function() {
				if ( $.browser.msie ) return false;
				this.addEventListener( original, $.event.special[fix].handler, true );
			},
			teardown:function() {
				if ( $.browser.msie ) return false;
				this.removeEventListener( original,
				$.event.special[fix].handler, true );
			},
			handler: function(e) {
				arguments[0] = $.event.fix(e);
				arguments[0].type = fix;
				return $.event.handle.apply(this, arguments);
			}
		};
	});
	$.extend($.fn, {
		delegate: function(type, delegate, handler) {
			return this.bind(type, function(event) {
				var target = $(event.target);
				if (target.is(delegate)) {
					return handler.apply(target, arguments);
				}
			});
		},
		triggerEvent: function(type, target) {
			return this.triggerHandler(type, [$.event.fix({ type: type, target: target })]);
		}
	})
})(jQuery);

/**
 * jQuery Window Plugin - To Popup A Beautiful Window-like Dialog
 * http://fstoke.me/jquery/window/
 * Copyright(c) 2010 David Hung
 * Dual licensed under the MIT and GPL licenses
 * Version: 5.01
 * Last Revision: 2010-11-13
 *
 * The window status is defined as: cascade(default), minimized, maxmized
 * 
 * The code style is reference from javascript Singleton design pattern. Please see:
 * http://fstoke.me/blog/?page_id=1610
 * 
 * Join the facebook fans page to discuss there and get latest information.
 * http://www.facebook.com/pages/jQuery-Window-Plugin/116769961667138
 *
 * This jQuery plugin has been tested in the following browsers:
 * - IE 7, 8
 * - Firefox 3.5+
 * - Opera 9, 10+
 * - Safari 4.0+
 * - Chrome 2.0+
 *
 * Required jQuery Libraries:
 * jquery.js         (v1.3.2)
 * jquery-ui.js      (v1.7.2)
 *
 * Customized Button JSON Array Sample:
	var myButtons = [
		// facebook button
		{
		id: "btn_facebook",           // required, it must be unique in this array data
		title: "share to facebook",   // optional, it will popup a tooltip by browser while mouse cursor over it
		clazz: "my_button",           // optional, don't set border, padding, margin or any style which will change element position or size
		style: "",                    // optional, don't set border, padding, margin or any style which will change element position or size
		image: "img/facebook.gif",    // optional, the image url of button icon(16x16 pixels)
		callback:                     // required, the callback function while click it
			function(btn, wnd) {
				wnd.getContainer().find("#demo_text").text("Share to facebook!");
				wnd.getContainer().find("#demo_logo").attr("src", "img/facebook_300x100.png");
			}
		},
		// twitter button
		{
		id: "btn_twitter",
		title: "share to twitter",
		clazz: "my_button",
		style: "background:#eee;",
		image: "img/twitter.png",
		callback:
			function(btn, wnd) {
				wnd.getContainer().find("#demo_text").text("Share to twitter!");
				wnd.getContainer().find("#demo_logo").attr("src", "img/twitter_300x100.jpg");
			}
		}
	];
 */
	
// Get window instance via jQuery call
// create window on html body
$.window = function(options) {
	return $.Window.getInstance(null, options);
};

// create window on caller element
$.fn.window = function(options) {
	return $.Window.getInstance($(this), options);
}

// Creating Window Dialog Module
$.Window = (function()  {
	// static private methods
	// static constants
	var VERSION = "5.01";           // the version of current plugin
	var ICON_WH = 16;               // window icon button width/height, in pixels. check "window_icon_button" style in css
	var ICON_MARGIN = 4;            // window icon button margin, in pixels. check "window_icon_button" style in css
	var ICON_OFFSET = ICON_WH + ICON_MARGIN; // window icon button offset for decide function bar width in header panel
	var OPACITY_MINIMIZED = 0.7;    // css opacity while window minimized or doing animation
	var MINIMIZED_NARROW = 24;
	var MINIMIZED_LONG = 120;
	var RESIZE_EVENT_DELAY = 200;
	var ua = navigator.userAgent.toLowerCase(); // browser useragent
	
	// static variables
	var windowIndex = 0;            // index to create window instance id
	var lastSelectedWindow = null;  // to remember last selected window instance
	var windowStorage = [];         // a array to store created window instance
	var initialized = false;        // a boolean flag to check is it initialized?
	var resizeTimer = null;         // a timer to avoid doing duplicated routine while receiving browser window resize event
	var parentCallers = [];
	var minWinData = {
		long: MINIMIZED_LONG,
		storage: []                 // a array to store minimized window instance
	};
	
	// the static setting
	var setting = {
		dock: 'left',                   // [string:"left"] the direction of minimized window dock at. the available values are [left, right, top, bottom]
		animationSpeed: 400,            // [number:400] the speed of animations: maximize, minimize, restore, shift, in milliseconds
		minWinNarrow: MINIMIZED_NARROW, // [number:24] the narrow dimension of minimized window
		minWinLong: MINIMIZED_LONG,     // [number:120] the long dimension of minimized window
		handleScrollbar: true,          // [boolean:true] to handle browser scrollbar when window status changed(maximize, minimize, cascade)
		showLog: false                  // [boolean:false] to decide show log in firebug, IE8, chrome console
	};
	
	// select the current clicked window instance, concurrently, unselect last selected window instance
	function selectWindow(parent, wnd) {
		if( parent == null ) {
			if( lastSelectedWindow != null && lastSelectedWindow != wnd ) {
				lastSelectedWindow.unselect();
				wnd.select();
			} else if( lastSelectedWindow == null ) {
				wnd.select();
			}
			lastSelectedWindow = wnd;
		} else if( parent != null ) {
			if( parent.get(0)._lastSelectedWindow != null && parent.get(0)._lastSelectedWindow != wnd ) {
				parent.get(0)._lastSelectedWindow.unselect();
				wnd.select();
			} else if( parent.get(0)._lastSelectedWindow == null ) {
				wnd.select();
			}
			parent.get(0)._lastSelectedWindow = wnd;
		}
	}
	
	// get the window instance
	function getWindow(windowId) {
		for( var i=0; i<windowStorage.length; i++ ) {
			var wnd = windowStorage[i];
			if( wnd.getWindowId() == windowId ) {
				return wnd;
			}
		}
	}
	
	// push the window instance into storage
	function pushWindow(wnd) {
		windowStorage.push(wnd);
	}
	
	// pop the window instance from storage out
	function popWindow(wnd) {
		for( var i=0; i<windowStorage.length; i++ ) {
			var w = windowStorage[i];
			if( w == wnd ) {
				windowStorage.splice(i--,1); // remove array element
				break;
			}
		}
	}
	
	// push the window instance into minimized storage
	function pushMinWindow(parent, wnd) {
		if( parent != null ) {
			parent.get(0)._minWinData.storage.push(wnd);
		} else {
			minWinData.storage.push(wnd);
		}
	}
	
	// pop the window instance from minimized storage out
	function popMinWindow(parent, wnd) {
		var doAdjust = false;
		var storage = (parent != null)? parent.get(0)._minWinData.storage:minWinData.storage;
		for( var i=0; i<storage.length; i++ ) {
			var w = storage[i];
			if( w == wnd ) {
				storage.splice(i--,1); // remove array element
				doAdjust = true;
				continue;
			}
			if( doAdjust ) {
				w._decreaseMiniIndex();
			}
		}
	}
	
	// get the minimized window count by parent elemen
	function getMinWindowLength(parent) {
		var storage = (parent != null)? parent.get(0)._minWinData.storage:minWinData.storage;
		return storage.length;
	}
	
	function checkMinWindowSize(parent, bPush) {
		var bAdjust = false;
		var rect = null;
		var mwdata = minWinData;
		if( parent != null ) {
			rect = {width:parent.innerWidth(), height:parent.innerHeight()};
			mwdata = parent.get(0)._minWinData;
		} else {
			rect = getBrowserScreenWH();
		}
		
		var count = getMinWindowLength(parent);
		if( setting.dock == 'left' || setting.dock == 'right' ) {
			if( bPush ) {
				if( ((count+1) * mwdata.long) > rect.height ) {
					mwdata.long = rect.height/(count+1);
					adjustAllMinWindows(parent);
				}
			} else if( mwdata.long < setting.minWinLong ) {
				if( (count * setting.minWinLong) < rect.height ) {
					mwdata.long = setting.minWinLong;
				} else {
					mwdata.long = rect.height/count;
				}
			}
		} else if( setting.dock == 'top' || setting.dock == 'bottom' ) {
			if( bPush ) {
				if( ((count+1) * mwdata.long) > rect.width ) {
					mwdata.long = rect.width/(count+1);
					adjustAllMinWindows(parent);
				}				
			} else if( mwdata.long < setting.minWinLong ) {
				if( (count * setting.minWinLong) < rect.width ) {
					mwdata.long = setting.minWinLong;
				} else {
					mwdata.long = rect.width/count;
				}
			}
		}
	}
	
	function adjustAllMinWindows(parent) {
		var storage = (parent != null)? parent.get(0)._minWinData.storage:minWinData.storage;
		for( var i=0; i<storage.length; i++ ) {
			storage[i]._adjustMinimizedPos(false);
		}
	}
	
	// hide browser scroll bar
	function hideBrowserScrollbar() {
		if( setting.handleScrollbar ) {
			if( ua.indexOf("msie 7") >= 0 ) { // fix IE7
				$("body").attr("scroll", "no");
			} else {
				document.body.style.overflow = "hidden";
			}
		}
	}
	
	// show browser scroll bar
	function showBrowserScrollbar() {
		if( setting.handleScrollbar ) {
			if( ua.indexOf("msie 7") >= 0 ) { // fix IE7
				$("body").removeAttr("scroll");
			} else {
				document.body.style.overflow = "auto";
			}
		}
	}
	
	function getBrowserScreenWH() {
		var width = document.documentElement.clientWidth;
		var height = document.documentElement.clientHeight;
		return {width:width, height:height};
	}

	function getBrowserScrollXY() {
		var scrOfX = 0, scrOfY = 0;
		if( typeof( window.pageYOffset ) == 'number' ) {
			//Netscape compliant
			scrOfY = window.pageYOffset;
			scrOfX = window.pageXOffset;
		} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
			//DOM compliant
			scrOfY = document.body.scrollTop;
			scrOfX = document.body.scrollLeft;
		} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
			//IE6 standards compliant mode
			scrOfY = document.documentElement.scrollTop;
			scrOfX = document.documentElement.scrollLeft;
		}
		return {left:scrOfX, top:scrOfY};
	}
	
	function getCssStyleByDock(parent, miniIndex) {
		var targetCss = {};
		var screenWH = getBrowserScreenWH();
		var cpos = null;
		var bTop = 0;
		var bLeft = 0;
		var narrow = setting.minWinNarrow;
		var long = minWinData.long;
		if( parent != null ) {
			cpos = parent.offset();
			bTop = parseInt( parent.css('borderTopWidth') );
			bLeft = parseInt( parent.css('borderLeftWidth') );
			long = parent.get(0)._minWinData.long;
		}
		if( setting.dock == 'left' || setting.dock == 'right' ) {
			targetCss.width = narrow;
			targetCss.height = long - 1;
			targetCss.top = miniIndex * long;
			if( setting.dock == 'left' ) {
				if( parent != null ) {
					targetCss.top += cpos.top + bTop;
					targetCss.left = cpos.left + bLeft;
				} else {
					targetCss.left = 0;
				}
			} else if( setting.dock == 'right' ) {
				if( parent != null ) {
					targetCss.top += cpos.top + bTop;
					targetCss.left = cpos.left + parent.width() + bLeft - narrow - 2;
				} else {
					targetCss.left = screenWH.width - narrow;
				}
			}
		} else if( setting.dock == 'top' || setting.dock == 'bottom' ) {
			targetCss.width = long - 1;
			targetCss.height = narrow;
			targetCss.left = miniIndex * long;
			if( setting.dock == 'top' ) {
				if( parent != null ) {
					targetCss.top = cpos.top + bTop;
					targetCss.left += cpos.left + bLeft;
				} else {
					targetCss.top = 0;	
				}
			} else if( setting.dock == 'bottom' ) {
				if( parent != null ) {
					targetCss.top = cpos.top + parent.height() + bTop - narrow - 2;
					targetCss.left += cpos.left + bLeft;
				} else {
					targetCss.top = screenWH.height - narrow;
				}
			}
		}
		//log( 'getCssStyleByDock: '+ screenWH.height );
		return targetCss;
	}
	
	function log(msg) {
		if(setting.showLog && window.console != null) {
			console.log(msg);
		}
	}
	
	function constructor(caller, options) {
		// instance private methods
		// flag & variables
		var _this = null;                 // to remember current window instance
		var windowId = "window_" + (windowIndex++); // the window's id
		var minimized = false;            // a boolean flag to tell the window is minimized
		var maximized = false;            // a boolean flag to tell the window is maximized
		var redirectCheck = false;        // a boolean flag to control popup message while browser is going to leave this page
		var pos = new Object();           // to save cascade mode current position
		var wh = new Object();            // to save cascade mode current width & height
		var orgPos = new Object();        // to save position before minimize
		var orgWh = new Object();         // to save width & height before minimize
		var targetCssStyle = {};          // to save target css style json object
		var headerFuncPanel = null;       // header function bar element object
		var funcBarWidth = 0;             // the width of header function bar
		var miniStackIndex = -1;          // the index of window in minimized stack
		var animating = false;            // a boolean flag to indicate the window is doing animate
		
		// element
		var container = null;             // whole window container element
		var header = null;                // the header panel of window. it includes title text and buttons
		var frame = null;                 // the content panel of window. it could be a iframe or a div element, depending on which way you create it
		var footer = null;                // the footer panel of window. currently, it got nothing, but maybe a status bar or something will be added in the future 
		
		// the instance options
		var options = $.extend({
			title: "",                    // [string:""] the title text of window
			url: "",                      // [string:""] the target url of iframe ready to load.
			content: "",                  // [html string, jquery object, element:""] this attribute only works when url is null. when passing a jquery object or a element, it will clone the original one to append.
			footerContent: "",            // [html string, jquery object, element:""] same as content attribute, but it's put on footer panel.
			containerClass: "",           // [string:""] container extra class
			headerClass: "",              // [string:""] header extra class
			frameClass: "",               // [string:""] frame extra class
			footerClass: "",              // [string:""] footer extra class
			selectedHeaderClass: "",      // [string:""] selected header extra class
			x: -1,                        // [number:-1] the x-axis value on screen(or caller element), if -1 means put on screen(or caller element) center
			y: -1,                        // [number:-1] the y-axis value on screen(or caller element), if -1 means put on screen(or caller element) center
			z: 2000,                      // [number:2000] the css z-index value
			width: 400,                   // [number:400] window width
			height: 300,                  // [number:300] window height
			minWidth: 200,                // [number:200] the minimum width, if -1 means no checking
			minHeight: 150,               // [number:150] the minimum height, if -1 means no checking
			maxWidth: 800,                // [number:800] the maximum width, if -1 means no checking
			maxHeight: 600,               // [number:600] the maximum height, if -1 means no checking
			showFooter: true,             // [boolean:true] to control show footer panel
			showRoundCorner: false,       // [boolean:true] to control display window as round corner
			closable: true,               // [boolean:true] to control window closable
			minimizable: true,            // [boolean:true] to control window minimizable
			maximizable: true,            // [boolean:true] to control window maximizable
			bookmarkable: true,           // [boolean:true] to control window with remote url could be bookmarked
			draggable: true,              // [boolean:true] to control window draggable
			resizable: true,              // [boolean:true] to control window resizable
			scrollable: true,             // [boolean:true] to control show scroll bar or not
			checkBoundary: false,         // [boolean:false] to check window dialog overflow html body or caller element
			custBtns: null,               // [json array:null] to describe the customized button display & callback function
			onOpen: null,                 // [function:null] a callback function while container is added into body
			onShow: null,                 // [function:null] a callback function while whole window display routine is finished
			onClose: null,                // [function:null] a callback function while user click close button
			onSelect: null,               // [function:null] a callback function while user select the window
			onUnselect: null,             // [function:null] a callback function while window unselected
			onDrag: null,                 // [function:null] a callback function while window is going to drag
			afterDrag: null,              // [function:null] a callback function after window dragged
			onResize: null,               // [function:null] a callback function while window is going to resize
			afterResize: null,            // [function:null] a callback function after window resized
			onMinimize: null,             // [function:null] a callback function while window is going to minimize
			afterMinimize: null,          // [function:null] a callback function after window minimized
			onMaximize: null,             // [function:null] a callback function while window is going to maximize
			afterMaximize: null,          // [function:null] a callback function after window maximized
			onCascade: null,              // [function:null] a callback function while window is going to cascade
			afterCascade: null,           // [function:null] a callback function after window cascaded
			onIframeStart: null,          // [function:null] a callback function while iframe ready to connect remoting url. this attribute only works while url attribute is given
			onIframeEnd: null,            // [function:null] a callback function while iframe load finished. this attribute only works while url attribute is given
			iframeRedirectCheckMsg: null, // [string:null] if null means no check, or pass a string to show warning message while iframe is going to redirect
			createRandomOffset: {x:0, y:0} // [json object:{x:0, y:0}] random the new created window position, it only works when options x,y value both are -1
		}, options);
		
		function initialize(instance) {
			_this = instance;
			// build html
			var realCaller = caller != null? caller:$("body");
			var cornerClass = options.showRoundCorner? "ui-corner-all ":"";
			realCaller.append("<div id='"+windowId+"' class='window_panel "+cornerClass+options.containerClass+"'></div>");
			container = realCaller.children("div#"+windowId);
	
			// onOpen call back
			if( $.isFunction(options.onOpen) ) {
				options.onOpen(_this);
			}
			
			wh.w = options.width;
			wh.h = options.height;
			container.width(options.width);
			container.height(options.height);
			container.css("z-index", options.z);
			if( $.browser.msie ) { // To fix the right or bottom edge of window can't be trigger to resize while scrollbar appears on IE browser
				container.css({
					paddingRight: 1,
					paddingBottom: 1
				});
			}
	
			if( options.x >= 0 || options.y >= 0 ) {
				var scrollPos = getBrowserScrollXY();
				// set position x
				if( options.x >= 0 ) {
					var pLeft = 0;
					if( caller != null ) {
						pLeft = options.x + caller.offset().left;
					} else {
						pLeft = options.x + scrollPos.left;
					}
					container.css("left", pLeft);
				} else { // put on center
					alignHorizontalCenter();
				}
	
				// set position y
				if( options.y >= 0 ) {
					var pTop = 0;
					if( caller != null ) {
						pTop = options.y + caller.offset().top;
					} else {
						pTop = options.y + scrollPos.top;
					}
					container.css("top", pTop);
				} else { // put on middle
					alignVerticalCenter();
				}
			} else {
				alignCenter();
			}
			// feed x,y with real pixel value(not a percentage), to avoid "JUMPING" while window restore from minized status
			var currPos = container.position();
			container.css({
				left: currPos.left,
				top: currPos.top
			});
	
			// build header html
			cornerClass = options.showRoundCorner? "ui-corner-top ":"";
			var headerHtml = "<div class='window_header window_header_normal ui-widget-header "+cornerClass+"no-resizable "+options.headerClass+"'>"+
				"<div class='window_title_text'>"+options.title+"</div>"+
				"<div class='window_function_bar'></div>"+
				"</div>";
			container.append(headerHtml);
			header = container.children("div.window_header");
			
			// bind double click event with doing maximize action
			if( options.maximizable ) {
				header.dblclick(function() {
					if( maximized ) {
						restore();
					} else {
						maximize();
					}
				});
			}
			
			headerFuncPanel = header.children("div.window_function_bar");
			// add close button
			if( options.closable ) {
				headerFuncPanel.append( "<div title='close window' class='closeImg window_icon_button no-draggable'></div>" );
				headerFuncPanel.children(".closeImg").click(function() {
					close();
				});
				funcBarWidth += ICON_OFFSET;
			}
	
			// add maximize button
			if( options.maximizable ) {
				headerFuncPanel.append( "<div title='maximize window' class='maximizeImg window_icon_button no-draggable'></div>" );
				headerFuncPanel.append( "<div title='cascade window' class='cascadeImg window_icon_button no-draggable' style='display:none;'></div>" );
				headerFuncPanel.children(".maximizeImg").click(function() {
					maximize();
				});
				headerFuncPanel.children(".cascadeImg").click(function() {
					restore();
				});
				funcBarWidth += ICON_OFFSET;
			}
	
			// add minimize button
			if( options.minimizable ) {
				headerFuncPanel.append( "<div title='minimize window' class='minimizeImg window_icon_button no-draggable'></div>" );
				headerFuncPanel.children(".minimizeImg").click(function() {
					minimize();
				});
				funcBarWidth += ICON_OFFSET;
			}
	
			// add bookmark button
			if( options.bookmarkable && options.url != null && $.trim(options.url) != "" ) {
				headerFuncPanel.append( "<div title='bookmark this' class='bookmarkImg window_icon_button no-draggable'></div>" );
				headerFuncPanel.children(".bookmarkImg").click(function() {
					doBookmark(options.title, options.url);
				});
				funcBarWidth += ICON_OFFSET;
			}
	
			// add customized buttons
			addCustomizedButtns(headerFuncPanel);
			
			// make buttons don't pass dblclick event to header panel 
			$(".window_icon_button").dblclick(function() {
				return false;
			});
	
			// set text & function bar width
			adjustHeaderTextPanelWidth();
			headerFuncPanel.width( funcBarWidth );
	
			// build iframe html
			var frameHeight = getFrameHeight(wh.h);
			if( options.url != null && $.trim(options.url) != "" ) {
				// iframe starting call back
				if( $.isFunction(options.onIframeStart) ) {
					log("start connecting iframe: "+options.url);
					options.onIframeStart(_this, options.url);
				}
	
				// add iframe redirect checking
				if( options.iframeRedirectCheckMsg ) {
					redirectCheck = true;
					window.onbeforeunload = function() {
						if( redirectCheck ) {
							var msg = options.iframeRedirectCheckMsg.replace("{url}", options.url);
							return msg;
						}
					}
				}
	
				// show loading image
				container.append("<div class='frame_loading'>Loading...</div>");
				var loading = container.children(".frame_loading");
				loading.css("marginLeft",	'-' + (loading.outerWidth() / 2) - 20 + 'px');
				loading.click(function() {
					loading.remove();
				});
	
				// append iframe html
				var scrollingHtml = options.scrollable? "yes":"no";
				container.append("<iframe style='display:none;' class='window_frame ui-widget-content no-draggable no-resizable "+options.frameClass+"' scrolling='"+scrollingHtml+"' src='"+options.url+"' width='100%' height='"+frameHeight+"px' frameborder='0'></iframe>");
				frame = container.children(".window_frame");
	
				// iframe load finished call back
				frame.ready(function() {
					frame.show();
				});
	
				frame.load(function() {
					redirectCheck = false;
					loading.remove();
					log("load iframe finished: "+options.url);
					if( $.isFunction(options.onIframeEnd) ) {
						options.onIframeEnd(_this, options.url);
					}
				});
			} else {
				container.append("<div class='window_frame ui-widget-content no-draggable no-resizable "+options.frameClass+"' style='width:100%; height:"+frameHeight+"px;'></div>");
				frame = container.children(".window_frame");
				if( options.content != null ) {
					setContent(options.content);
					frame.children().show();
				}
				frame.css({
					overflow: options.scrollable? "auto":"hidden"
				});
			}
	
			// build footer html
			if( options.showFooter ) {
				cornerClass = options.showRoundCorner? "ui-corner-bottom ":"";
				container.append("<div class='window_footer ui-widget-content "+cornerClass+"no-draggable no-resizable "+options.footerClass+"'><div></div></div>");
				footer = container.children("div.window_footer");
				if( options.footerContent != null ) {
					setFooterContent(options.footerContent);
					footer.children("div").children().show();
				}
			} else {
				cornerClass = options.showRoundCorner? "ui-corner-bottom ":"";
				frame.addClass(cornerClass);
			}
	
			// bind container handle mousedown event
			container.mousedown(function() {
				selectWindow(caller, _this);
			});
	
			// make window draggable
			if( options.draggable ) {
				container.draggable({
					cancel: ".no-draggable",
					start: function() {
						log( "drag start" );
						if( minimized || maximized ) { // if window is minimized or maximized, reset the css style
							container.css("position", "fixed");
							container.css(targetCssStyle);
						}
						showOverlay();
						hideContent();
						// callback
						if( options.onDrag ) {
							options.onDrag(_this);
						}
					},
					stop: function() {
						log( "drag stop" );
						if( minimized || maximized ) { // if window is minimized or maximized, reset the css style
							container.css("position", "fixed");
							container.css(targetCssStyle);
						}
						hideOverlay();
						showContent();
						// callback
						if( options.afterDrag ) {
							options.afterDrag(_this);
						}
					}
				});
				// set boundary if got opotions
				if( options.checkBoundary ) {
					container.draggable('option', 'containment', 'parent');
				}
			}
	
			// make window resizable
			if( options.resizable ) {
				container.resizable({
					cancel: ".no-resizable",
					alsoResize: frame,
					start: function() { // this will be triggered when window is going to drag in minimized or maximized mode
						log( "resize start" );
						if( minimized || maximized ) { // if window is minimized or maximized, reset the css style
							return false;
						}
						showOverlay();
						hideContent();
						// callback
						if( options.onResize ) {
							options.onResize(_this);
						}
					},
					stop: function() {
						log( "resize stop" );
						if( minimized || maximized ) { // if window is minimized or maximized, reset the css style
							return false;
						}
						hideOverlay();
						adjustHeaderTextPanelWidth();
						showContent();
						// callback
						if( options.afterResize ) {
							options.afterResize(_this);
						}
					}
				});
				// set boundary if got opotions
				if( options.checkBoundary ) {
					// this got bug, so mark it temporarily
					//container.resizable('option', 'containment', "parent");
				}
	
				// set resize min, max width & height
				if( options.maxWidth >= 0 ) {
					container.resizable('option', 'maxWidth', options.maxWidth);
				}
				if( options.maxHeight >= 0 ) {
					container.resizable('option', 'maxHeight', options.maxHeight);
				}
				if( options.minWidth >= 0 ) {
					container.resizable('option', 'minWidth', options.minWidth);
				}
				if( options.minHeight >= 0 ) {
					container.resizable('option', 'minHeight', options.minHeight);
				}
			}

			// onShow call back
			if( $.isFunction(options.onShow) ) {
				options.onShow(_this);
			}
		}
		
		function setTitle(title) {
			options.title = title;
			header.children(".window_title_text").text(title);
			if( minimized ) {
				_transformTitleText();
			}
		}
		
		function getTitle() {
			return options.title;
		}
		
		function setUrl(url) {
			options.url = url;
			frame.attr("src", url);
		}
		
		function getUrl() {
			return options.url;
		}
		
		function setContent(content) {
			options.content = content;
			if( typeof content == 'object' ) {
				content = $(content).clone(true);
			} else if( typeof content == 'string' ) {
				// using original content
			}
			frame.empty();
			frame.append(content);
		}
		
		function getContent() {
			return frame.html();
		}
		
		function setFooterContent(content) {
			if( options.showFooter ) {
				options.footerContent = content;
				if( typeof content == 'object' ) {
					content = $(content).clone(true);
				} else if( typeof content == 'string' ) {
					// using original content
				}
				footer.children("div").empty();
				footer.children("div").append(content);
			}
		}
		
		function getFooterContent() {
			return footer.children("div").html();
		}
		
		// popup a overlay panel block whole screen while window dragging or resizing
		// to avoid lost event while mouse cursor over iframe region. [ISU_003]
		function showOverlay() {
			var overlay = $("#window_overlay");
			if( overlay.get(0) == null ) {
				$("body").append("<div id='window_overlay'>&nbsp;</div>");
				overlay = $("#window_overlay");
				overlay.css('z-index', options.z+1);
			}
			overlay.show();
		}
		
		function hideOverlay() {
			$("#window_overlay").hide();
		}
		
		function transferToFixed() {
			var currPos = container.offset();
			var scrollPos = getBrowserScrollXY();
			container.css({
				position: "fixed", // this will cause IE brwoser UI error, See ISU_004
				left: currPos.left - scrollPos.left,
				top: currPos.top - scrollPos.top
			});
		}
	
		function transferToAbsolute() {
			var currPos = container.offset();
			container.css({
				position: "absolute",
				left: currPos.left,
				top: currPos.top
			});
		}
	
		function addCustomizedButtns(headerFuncPanel) {
			if( options.custBtns != null && typeof options.custBtns == 'object' ) {
				for( var i=0; i<options.custBtns.length; i++ ) {
					var btnData = options.custBtns[i];
					if( btnData != null && typeof btnData == 'object' ) {
						if( btnData.id != null && btnData.callback != null ) { // it's a JSON object
							var id = btnData.id != null? btnData.id:"";
							var clazz = btnData.clazz != null? btnData.clazz:"";
							var title = btnData.title != null? btnData.title:"";
							var style = btnData.style != null? btnData.style:"";
							var image = btnData.image != null? btnData.image:"";
							var callback = btnData.callback != null? btnData.callback:"";
							if( btnData.image != null && btnData.image != "" ) {
								headerFuncPanel.append( "<img id='"+id+"' src='"+image+"' title='"+title+"' class='"+clazz+" window_icon_button no-draggable' style='"+style+"'/>" );
							} else {
								headerFuncPanel.append( "<div id='"+id+"' src='"+image+"' title='"+title+"' class='"+clazz+" window_icon_button no-draggable' style='"+style+"'></div>" );
							}
							var btn = headerFuncPanel.children("[id="+id+"]");
							btn.get(0).clickCb = callback;
							if( $.isFunction(callback)) {
								btn.click(function() {
									this.clickCb($(this), _this);
								});
							}
						} else { // it's a html element(or wrapped with jQuery)
							var btn = $(btnData).clone(true);
							btn.addClass("window_icon_button no-draggable cust_button");
							headerFuncPanel.append( btn );
							btn.show();
						}
					}
					funcBarWidth += ICON_OFFSET;
				}
			}
		}
		
		function _adjustMinimizedPos(bImmediate, callback) {
			animating = true;
			targetCssStyle = getCssStyleByDock(caller, miniStackIndex);
			if( bImmediate ) {
				container.css(targetCssStyle);
				animating = false;
				if( $.isFunction(callback) ) {
					callback();
				}
			} else {
				container.animate(targetCssStyle, setting.animationSpeed, 'swing', function() {
					animating = false;
					if( $.isFunction(callback) ) {
						callback();
					}
				});
			}
		}
		
		function adjustHeaderTextPanelWidth() {
			header.children("div.window_title_text").width( header.width() - funcBarWidth - 10 );
		}
	
		function adjustFrameWH() {
			var width = container.width();
			var height = container.height();
			var frameHeight = getFrameHeight(height);
			frame.width( width );
			frame.height( frameHeight );
		}
	
		function doBookmark(title, url) {
			if ( $.browser.mozilla && window.sidebar ) { // Mozilla Firefox Bookmark
				window.sidebar.addPanel(title, url, "");
			} else if( $.browser.msie && window.external ) { // IE Favorite
				window.external.AddFavorite( url, title);
			} else if( ua.indexOf("chrome") >= 0 ) { // Chrome
				alert("Sorry! Chrome doesn't support bookmark function currently.");
				//alert("Press [Ctrl + D] to bookmark in Chrome");
			} else if($.browser.safari || ua.indexOf("safari") >= 0 ) { // Safari
				alert("Sorry! Safari doesn't support bookmark function currently.");
				//alert("Press [Ctrl + D] to bookmark in Safari");
			} else if($.browser.opera || ua.indexOf("opera") >= 0 ) { // Opera Hotlist
				alert("Sorry! Opera doesn't support bookmark function currently.");
				//alert("Press [Ctrl + D] to bookmark in Opera");
			}
		}
	
		function hideContent() {
			//log("hideContent");
			var bgColor = frame.css("backgroundColor");
			if( bgColor != null && bgColor != "transparent" ) {
				container.css("backgroundColor", bgColor);
			}
			frame.hide();
			if( options.showFooter ) {
				footer.hide();
			}
			container.css("opacity", OPACITY_MINIMIZED);
		}
	
		function showContent() {
			//log("showContent");
			frame.show();
			if( options.showFooter ) {
				footer.show();
			}
			container.css("opacity", 1);
		}
	
		function getFrameHeight(windowHeight) {
			var footerHeight = options.showFooter? 16:0;
			return windowHeight - 20 - footerHeight - 4; // minus header & footer & iframe's padding height
		}
	
		// modify title text as vertical presentation
		function _transformTitleText() {
			if( setting.dock == 'top' || setting.dock == 'bottom' ) {
				return;
			}
			
			var textBlock = header.children("div.window_title_text");
			//var text = textBlock.text();
			var text = options.title;
			var buf = "";
			
			for( var i=0; i<text.length; i++ ) {
				var c = text.charAt(i);
				if( c == "-" || c == "_" ) {
					c = "|";
				}
				if( c == " " ) {
					c = "<div style='height:5px; line-height:5px;'>&nbsp;</div>";
					buf += c;
				} else {
					buf += c+"<br>";
				}
			}
			textBlock.html(buf);
		}
	
		function restoreTitleText() {
			var textBlock = header.children("div.window_title_text");
			textBlock.text(options.title);
		}
		
		// public
		function getCaller() {
			return caller;
		}
	
		function getContainer() {
			return container;
		}
	
		function getHeader() {
			return header;
		}
	
		function getFrame() {
			return frame;
		}
	
		function getFooter() {
			return footer;
		}
		
		function getTargetCssStyle() {
			return targetCssStyle;
		}
		
		function alignCenter() {
			var pLeft = 0, pTop = 0;
			if( caller != null ) {
				var cpos = caller.offset();
				pLeft = cpos.left + (caller.width() - container.width()) / 2;
				pTop = cpos.top + (caller.height() - container.height()) / 2;
			} else {			
				var scrollPos = getBrowserScrollXY();
				var screenWH = getBrowserScreenWH();
				pLeft = scrollPos.left + (screenWH.width - container.width()) / 2;
				pTop = scrollPos.top + (screenWH.height - container.height()) / 2;
			};
			
			// random new created window position
			if( options.createRandomOffset.x > 0 ) {
				pLeft += ((Math.random() - 0.5) * options.createRandomOffset.x); 
			}
			if( options.createRandomOffset.y > 0 ) {
				pTop += ((Math.random() - 0.5) * options.createRandomOffset.y);					
			}
			container.css({
				left: pLeft,
				top: pTop
			});
		}
	
		function alignHorizontalCenter() {
			var pLeft = 0;
			if( caller != null ) {
				pLeft = caller.offset().left + (caller.width() - container.width()) / 2;
			} else {
				var scrollPos = getBrowserScrollXY();
				var screenWH = getBrowserScreenWH();
				pLeft = scrollPos.left + (screenWH.width - container.width()) / 2;
			}
			container.css({
				left: pLeft
			});
		}
	
		function alignVerticalCenter() {
			var pTop = 0;
			if( caller != null ) {
				pTop = caller.offset().top + (caller.height() - container.height()) / 2;
			} else {
				var scrollPos = getBrowserScrollXY();
				var screenWH = getBrowserScreenWH();
				pTop = scrollPos.top + (screenWH.height - container.height()) / 2;
			}
			container.css({
				top: pTop
			});
		}
		
		function select() {
			if( maximized == false ) {
				container.css('z-index', options.z+2);
				if( options.selectedHeaderClass ) {
					header.addClass(options.selectedHeaderClass); // add selected header class
				}
			}
			if( $.isFunction(options.onSelect) ) {
				options.onSelect();
			}
		}
	
		function unselect() {
			if( maximized == false ) {
				container.css('z-index', options.z);
				if( options.selectedHeaderClass ) {
					header.removeClass(options.selectedHeaderClass);
				}
			}
			if( $.isFunction(options.onUnselect) ) {
				options.onUnselect();
			}
		}
		
		function move(x, y, bShift) {
			if( !maximized && !minimized ) {
				var styleObj = {};
				if( typeof x == 'number' ) {
					if( bShift ) {
						var currPos = container.offset();
						x += currPos.left;
					}
					styleObj.left = x;
				}
				if( typeof y == 'number' ) {
					styleObj.top = y;
					if( bShift ) {
						var currPos = container.offset();
						y += currPos.top;
					}
					styleObj.top = y;
				}
				container.css(styleObj);
			}
		}
		
		function resize(w, h) {
			if( !maximized && !minimized ) {
				var styleObj = {};
				if( w > 0 ) {
					styleObj.width = w;
				}
				if( h > 0 ) {
					styleObj.height = h;
				}
				container.css(styleObj);
				adjustHeaderTextPanelWidth();
			}
		}
	
		function maximize(bImmediately, bNoSaveDisplay) {
			if( !$.browser.msie && caller == null ) { // in IE, must do hide scrollbar routine after animation finished
				hideBrowserScrollbar();
			}
			maximized = true;
			container.draggable( 'disable' );
			container.resizable( 'disable' );
	
			// save current display
			if( bNoSaveDisplay != true ) {
				pos.left = container.css("left");
				pos.top = container.css("top");
				wh.w = container.width();
				wh.h = container.height();
			}
			// must add this, or it will get a bug when user mouse down the border of window panel if it is resized.
			container.addClass('no-resizable');
			
			var scrollPos = getBrowserScrollXY();
			var screenWH = getBrowserScreenWH();
			if( caller != null ) {
				var cpos = caller.offset();
				var bTop = parseInt( caller.css('borderTopWidth') );
				var bLeft = parseInt( caller.css('borderLeftWidth') );
				targetCssStyle = {
					left: cpos.left + bLeft,
					top: cpos.top + bTop,
					width: caller.width(),
					height: caller.height(),
					opacity: 1
				};
			} else {
				targetCssStyle = {
					left: scrollPos.left,
					top: scrollPos.top,
					width: screenWH.width,
					height: screenWH.height,
					opacity: 1
				};
			}
	
			if( bImmediately ) {
				container.css(targetCssStyle);
				adjustHeaderTextPanelWidth();
				adjustFrameWH();
				header.removeClass('window_header_normal');
				header.addClass('window_header_maximize');
				// switch maximize, cascade button
				headerFuncPanel.children(".maximizeImg").hide();
				headerFuncPanel.children(".cascadeImg").show();
			} else {
				hideContent();
				container.animate(targetCssStyle, setting.animationSpeed, 'swing', function() {
					if( $.browser.msie && caller == null ) { // in IE, must do hide scrollbar routine after animation finished
						hideBrowserScrollbar();
					}
					showContent();
					adjustHeaderTextPanelWidth();
					adjustFrameWH();
					header.removeClass('window_header_normal');
					header.addClass('window_header_maximize');
					// switch maximize, cascade button
					headerFuncPanel.children(".maximizeImg").hide();
					headerFuncPanel.children(".cascadeImg").show();
					
					// after callback
					if( $.isFunction(options.afterMaximize) ) {
						options.afterMaximize(_this);
					}
				});
				container.css('z-index', options.z+3);
			}
			
			// before callback
			if( $.isFunction(options.onMaximize) ) {
				options.onMaximize(_this);
			}
		}
	
		function minimize() {
			showBrowserScrollbar();
			minimized = true;
			container.draggable( 'disable' );
			container.resizable( 'disable' );
			
			// save current display
			orgPos.left = container.css("left");
			orgPos.top = container.css("top");
			orgWh.w = container.width();
			orgWh.h = container.height();

			miniStackIndex = getMinWindowLength(caller);
			targetCssStyle = {
				opacity: OPACITY_MINIMIZED
			};
			// must add this, or it will get a bug when user mouse down the border of window panel if it is resized.
			container.addClass('no-resizable');
			
			if( caller == null ) {
				transferToFixed(); // transfer position to fixed first
			}
			headerFuncPanel.hide();
			hideContent();
			
			// check minimized windows' size
			checkMinWindowSize(caller, true);
			_adjustMinimizedPos(false, function() {
				container.css('z-index', options.z);
				header.children("div.window_title_text").width( "100%" );
				header.attr("title", options.title);
				header.removeClass('window_header_normal');
				header.removeClass('window_header_maximize');
				header.addClass('window_header_minimize');
				if( setting.dock == 'left' || setting.dock == 'right' ) {
					header.addClass('window_header_minimize_vertical');
				}
				
				if( options.showRoundCorner ) {
					header.removeClass('ui-corner-top');
					header.addClass('ui-corner-all');
				}
				_transformTitleText();
	
				// bind header click event
				header.click(function() {
					if( !animating ) {
						restore();
					}
				});
				
				// after callback
				if( $.isFunction(options.afterMinimize) ) {
					options.afterMinimize(_this);
				}
			});
			container.mouseover(function() {
				$(this).css("opacity", 1);
			});
			container.mouseout(function() {
				$(this).css("opacity", OPACITY_MINIMIZED);
			});
			
			// before callback
			if( $.isFunction(options.onMinimize) ) {
				options.onMinimize(_this);
			}
			
			// push into minimized window storage
			pushMinWindow(caller, _this);
		}
	
		function restore() {
			var rpos = null;
			var rwh = null;
			var zIndex = options.z+2;
			if( minimized ) { // from minimized status
				rpos = orgPos;
				rwh = orgWh;
				transferToAbsolute(); // transfer position to fixed first
				header.removeClass('window_header_minimize');
				if( setting.dock == 'left' || setting.dock == 'right' ) {
					header.removeClass('window_header_minimize_vertical');
				}
				if( maximized ) { // minimized -> maximized
					header.addClass('window_header_maximize');
					if( caller != null ) {
						var cpos = caller.offset();
						var bTop = parseInt( caller.css('borderTopWidth') );
						var bLeft = parseInt( caller.css('borderLeftWidth') );
						rpos = {
							left: cpos.left + bLeft,
							top: cpos.top + bTop
						};
					} else {
						var scrollPos = getBrowserScrollXY();
						rpos = {
							left: scrollPos.left,
							top: scrollPos.top
						};
					}
					zIndex = options.z+3;
					container.css('z-index', zIndex); // change z-index before animating
				} else { // minimized -> cascade
					header.addClass('window_header_normal');
					// must add this, or it will get a bug when user mouse down the border of window panel if it is resized.
					container.removeClass('no-resizable');
				}
			} else if( maximized ) { // maximized -> cascade
				maximized = false;
				rpos = pos;
				rwh = wh;
				header.removeClass('window_header_maximize');
				header.addClass('window_header_normal');
				// must add this, or it will get a bug when user mouse down the border of window panel if it is resized.
				container.removeClass('no-resizable');
			}
			restoreTitleText();
			header.removeAttr("title");
			header.removeClass('window_header_minimize');
			if( setting.dock == 'left' || setting.dock == 'right' ) {
				header.removeClass('window_header_minimize_vertical');
			}
			if( options.showRoundCorner ) {
				header.removeClass('ui-corner-all');
				header.addClass('ui-corner-top');
			}
			
			// unbind event
			container.unbind("mouseover");
			container.unbind("mouseout");

			targetCssStyle = {
				left: rpos.left,
				top: rpos.top,
				width: rwh.w,
				height: rwh.h,
				opacity: 1
			};
			
			hideContent();
			container.animate(targetCssStyle, setting.animationSpeed, 'swing', function() {
				container.css('z-index', zIndex);
				showContent();
				header.unbind('click');
				adjustHeaderTextPanelWidth();
				adjustFrameWH();
	
				// switch maximize, cacade icon
				if( maximized ) {
					if( caller == null ) {
						hideBrowserScrollbar();
					}
					headerFuncPanel.children(".maximizeImg").hide();
					headerFuncPanel.children(".cascadeImg").show();
				} else {
					showBrowserScrollbar();
					container.draggable( 'enable' );
					container.resizable( 'enable' );
					headerFuncPanel.children(".maximizeImg").show();
					headerFuncPanel.children(".cascadeImg").hide();
				}
				headerFuncPanel.show();
				
				// pop from minimized window storage
				if( minimized ) {
					minimized = false;
					popMinWindow(caller, _this);
					checkMinWindowSize(caller, false);
					adjustAllMinWindows(caller); // adjust minimized window position
				}
				
				// after callback
				if( $.isFunction(options.afterCascade) ) {
					options.afterCascade(_this);
				}
			});
			
			// before callback
			if( $.isFunction(options.onCascade) ) {
				options.onCascade(_this);
			}
		}
		
		function close(quiet) {
			// do callback
			if( !quiet && $.isFunction(options.onClose) ) {
				options.onClose(_this);
			}
			destroy();
		}
	
		function destroy() {
			redirectCheck = false;
			if( maximized ) {
				showBrowserScrollbar();
			}
			popWindow(_this);
			container.remove();
		}
		
		function _decreaseMiniIndex() {
			miniStackIndex--;
		}
	
		return { // instance public methods
			initialize: initialize,
			getTargetCssStyle: getTargetCssStyle,         // get the css ready to change
			getWindowId: function() {                     // get window id
				return windowId;
			},
			getCaller: getCaller,                         // get window container's parent panel, it's a jQuery object
			getContainer: getContainer,                   // get window container panel, it's a jQuery object
			getHeader: getHeader,                         // get window header panel,it's  a jQuery object
			getFrame: getFrame,                           // get window frame panel, it's a jQuery object
			getFooter: getFooter,                         // get window footer panel, it's a jQuery object
			alignCenter: alignCenter,                     // set current window as screen center
			alignHorizontalCenter: alignHorizontalCenter, // set current window as horizontal center
			alignVerticalCenter: alignVerticalCenter,     // set current window as vertical center
			select: select,                               // select current window, it will increase the original z-index value with 2
			unselect: unselect,                           // unselect current window, it will set the z-index as original options.z
			move: move,                                   // move current window to target position or shift it by passed distance
			resize: resize,                               // resize current window to target width/height
			maximize: maximize,                           // maximize current window
			minimize: minimize,                           // minimize current window
			restore: restore,                             // restore current window, it could be maximized or cascade status
			close: close,                                 // close current window. parameter: quiet - [boolean] to decide doing callback or not
			setTitle: setTitle,                           // change window title. parameter: title - [string] window title text
			setUrl: setUrl,                               // change iframe url. parameter: url - [string] iframe url
			setContent: setContent,                       // change frame content. parameter: content - [html string, jquery object, element] the content of frame
			setFooterContent: setFooterContent,           // change footer content. parameter: content - [html string, jquery object, element] the content of footer
			getTitle: getTitle,                           // get window title text
			getUrl: getUrl,                               // get url string
			getContent: getContent,                       // get frame html content
			getFooterContent: getFooterContent,           // get footer html content
			isMaximized: function() {                     // get window maximized status
				return maximized;
			},
			isMinimized: function() {                     // get window minmized status
				return minimized;
			},
			
			// for plugin private use
			_decreaseMiniIndex: _decreaseMiniIndex,
			_adjustMinimizedPos: _adjustMinimizedPos,
			_setOrgWH: function(wh) {
				orgWh.w = wh.width;
				orgWh.h = wh.height;
			},
			_transformTitleText: _transformTitleText
		};
	} // constructor end
	
	return { // static public methods
		getInstance: function(caller, options) { // create new window instance
			var instance = constructor(caller, options);
			instance.initialize(instance);
			selectWindow(caller, instance); // set new created window instance as selected
			pushWindow(instance);
			if( caller != null ) {
				if( caller.get(0)._minWinData == null ) {
					// create the minimized window relative data
					caller.get(0)._minWinData = {
						long: setting.minWinLong,
						storage: []
					};
				}
				parentCallers.push(caller);
			}
			
			// static initialzation
			if( !initialized ) {
				// handle window resize event
				$(window).resize(function() {
					if( resizeTimer != null ) {
						clearTimeout(resizeTimer);
					}
					resizeTimer = window.setTimeout(function() {
						var screenWH = getBrowserScreenWH();
						for( var i=0; i<windowStorage.length; i++ ) {
							var wnd = windowStorage[i];
							if( wnd.isMaximized() ) {
								if( wnd.isMinimized() ) {
									// reset the restore width/height
									wnd._setOrgWH(screenWH);
								} else {
									wnd.maximize(true, true);
								}
							}
							
							if( wnd.isMinimized() ) {
								wnd._adjustMinimizedPos(true);
							}
						}
					}, RESIZE_EVENT_DELAY);
				});
				initialized = true;
			}
			
			return instance;
		},
		getVersion: function() { // get current version of plugin
			return VERSION;
		},
		prepare: function(custSetting) { // initialize with customerized static setting attributes
			$.extend(setting, custSetting);
			minWinData.long = setting.minWinLong;
		},
		closeAll: function(quiet) { // close all created windows. it got a parameter - quiet, a boolean flag to decide doing callback or not
			var count = windowStorage.length;
			for( var i=0; i<count; i++ ) {
				var wnd = windowStorage[0];
				wnd.close(quiet);
			}
			windowStorage = [];
			// reset minimized window data object
			minWinData.storage = [];
			minWinData.long = setting.minWinLong;
			for( var i=0; i<parentCallers.length; i++ ) {
				var mwdata = parentCallers[i].get(0)._minWinData;
				mwdata.storage = [];
				mwdata.long = setting.minWinLong;
			}
		},
		hideAll: function() { // hide all created windows
			for( var i=0; i<windowStorage.length; i++ ) {
				windowStorage[i].getContainer().hide();
			}
		},
		showAll: function() { // show all created windows
			for( var i=0; i<windowStorage.length; i++ ) {
				windowStorage[i].getContainer().show();
			}
		},
		getAll: function() { // return all created windows instance
			return windowStorage;
		},
		getWindow: getWindow // get the window instance via window id
	}
})();

// alias methods
$.window.getVersion = $.Window.getVersion;
$.window.closeAll = $.Window.closeAll;
$.window.hideAll = $.Window.hideAll;
$.window.showAll = $.Window.showAll;
$.window.getAll = $.Window.getAll;
$.window.getWindow = $.Window.getWindow;
$.window.prepare = $.Window.prepare;