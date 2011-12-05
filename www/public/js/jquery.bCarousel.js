/**
 * jCarouselLite
 * 
 * @author shigeru.ashikawa
 * @copyright Copyright (c) 2011, infobahn inc.
 * 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * 
 * jcarousellite v1.0.1 へオプションの追加、アニメーションの調整、CSSの外部化
 * - オプション btnEvent の追加
 * 		btnGo をバインドするイベントを変更する
 * - アニメーションの調整
 * 		var running でのコントロールを止め、
 *		ul.stop() で、アニメーションキューを更新する
 * - CSSの外部化
 * 		div{overflow: hidden} のハードコーディングを削除
 * 		
 * 元ソース
 * 	http://gmarwaha.com/jquery/jcarousellite/
 */
(function($) {
$.fn.bCarouselLite = function(o) {
    o = $.extend({
        btnPrev: null,
        btnNext: null,
        btnEvent: "click",
        btnGo: null,
        mouseWheel: false,
        auto: null,

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
    
    return this.each(function() {
    	
        var animCss=o.vertical?"top":"left", sizeCss=o.vertical?"height":"width";
        var div = $(this), ul = $("ul", div), tLi = $("li", ul), tl = tLi.size(), v = o.visible;

        if(o.circular) {
            ul.prepend(tLi.slice(tl-v-1+1).clone())
              .append(tLi.slice(0,v).clone());
            o.start += v;
        }

        var li = $("li", ul), itemLength = li.size(), curr = o.start;
        div.css("visibility", "visible");
        
        li.css({overflow: "hidden", float: o.vertical ? "none" : "left"});
        ul.css({margin: "0", padding: "0", position: "relative", "list-style-type": "none", "z-index": "1"});
        div.css({position: "relative", "z-index": "2", left: "0px"});
        
        var liSize = o.vertical ? height(li) : width(li);   // Full li size(incl margin)-Used for animation
        var ulSize = liSize * itemLength;                   // size of full ul(total length, not just for the visible items)
        var divSize = liSize * v;                           // size of entire div(total length for just the visible items)

        li.css({width: li.width(), height: li.height()});
        ul.css(sizeCss, ulSize+"px").css(animCss, -(curr*liSize));

        div.css(sizeCss, divSize+"px");                     // Width of the DIV. length of visible images

        if(o.btnPrev)
            $(o.btnPrev).click(function() {
                return go(curr-o.scroll);
            });

        if(o.btnNext)
            $(o.btnNext).click(function() {
                return go(curr+o.scroll);
            });

        if(o.btnGo)
            $.each(o.btnGo, function(i, val) {
                $(val).bind(o.btnEvent,function() {
                    return go(o.circular ? o.visible+i : i);
                });
            });
        
        if(o.mouseWheel && div.mousewheel)
            div.mousewheel(function(e, d) {
                return d>0 ? go(curr-o.scroll) : go(curr+o.scroll);
            });

        if(o.auto)
            setInterval(function() {
                go(curr+o.scroll);
            }, o.auto+o.speed);

        function vis() {
            return li.slice(curr).slice(0,v);
        };

        function go(to) {
        	
                if(o.beforeStart)
                    o.beforeStart.call(this, vis(),curr);

                if(o.circular) {
                    if(to<=o.start-v-1) {
                        ul.css(animCss, -((itemLength-(v*2))*liSize)+"px");
                        // If "scroll" > 1, then the "to" might not be equal to the condition; it can be lesser depending on the number of elements.
                        curr = to==o.start-v-1 ? itemLength-(v*2)-1 : itemLength-(v*2)-o.scroll;
                    } else if(to>=itemLength-v+1) { // If last, then goto first
                        ul.css(animCss, -( (v) * liSize ) + "px" );
                        // If "scroll" > 1, then the "to" might not be equal to the condition; it can be greater depending on the number of elements.
                        curr = to==itemLength-v+1 ? v+1 : v+o.scroll;
                    } else curr = to;
                } else {
                    if(to<0 || to>itemLength-v) return;
                    else curr = to;
                }
                
                
                ul.stop();
                ul.animate(
                    animCss == "left" ? { left: -(curr*liSize) } : { top: -(curr*liSize) } , o.speed, o.easing
                );
                
                if(!o.circular) {
                	$(o.btnPrev + "," + o.btnNext).removeClass("disabled");
                	$( (curr-o.scroll<0 && o.btnPrev)
                			||
                			(curr+o.scroll > itemLength-v && o.btnNext)
                			||
                			[]
                	).addClass("disabled");
                }
                
                if(o.afterEnd)
                    o.afterEnd.call(this, vis(),curr);
            }
            return false;
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

})(jQuery);