function wSize(w, h) {
    this.w = w;
    this.h = h;

}

function wPosition(x, y) {
    this.x = x;
    this.y = y;

}

wGuiSizes = new Array();
wGuiPositions = new Array();
wGuiStatuses = new Array();


var EventHandlers = new Array();
var cEventHandlers = 0;
var topIndex = 0;

function wGuiWindowMinimize(obj) {


    content = document.getElementById(obj.parentNode.parentNode.id + '_content');
    mwindow = document.getElementById(obj.parentNode.parentNode.id);

    if (content.style.display != "none") {

        i = new wSize(mwindow.style.width, mwindow.style.height);
        wGuiSizes[mwindow.id] = i;
        i = new wPosition(mwindow.style.left, mwindow.style.top);
        wGuiPositions[mwindow.id] = i;

        content.style.display = "none";
        mwindow.style.height = "30px";
        wGuiStatuses[mwindow.id] = "collapsed";


    } else {
        content.style.display = "block";
        mwindow.style.height = wGuiSizes[mwindow.id].h;
        mwindow.style.width = wGuiSizes[mwindow.id].w;
        wGuiStatuses[mwindow.id] = "normal";
    }


}


function wGuiWindowMaximize(obj) {

    content = document.getElementById(obj.parentNode.parentNode.id + '_content');
    mwindow = document.getElementById(obj.parentNode.parentNode.id);
    if (wGuiStatuses[mwindow.id] == "collapsed") return;
    if (wGuiStatuses[mwindow.id] == "maximized") {
        mwindow.style.height = wGuiSizes[mwindow.id].h;
        mwindow.style.width = wGuiSizes[mwindow.id].w;
        mwindow.style.left = wGuiPositions[mwindow.id].x;
        mwindow.style.top = wGuiPositions[mwindow.id].y;
        wGuiStatuses[mwindow.id] = "normal";

    } else {
        i = new wSize(mwindow.style.width, mwindow.style.height);
        wGuiSizes[mwindow.id] = i;
        i = new wPosition(mwindow.style.left, mwindow.style.top);
        wGuiPositions[mwindow.id] = i;
        mwindow.style.left = "0px";
        mwindow.style.top = "0px";
        mwindow.style.width = "99%";
        mwindow.style.height = "99%";
        wGuiStatuses[mwindow.id] = "maximized";
    }


}

function wGuiWindowToUpperLayer(obj) {
    mwindow = document.getElementById(obj.parentNode.id);
    mwindow.style.zIndex = topIndex + 1;
    topIndex++;

}

var hackedSelectedValue = null;

function setSelectReadonly(selectElementId) {

    var selectElement = document.getElementById(selectElementId);
    if (selectElement) {
        selectElement.onfocus = function () {
            hackedSelectedValue = selectElement.value
        };
        selectElement.onblur = function () {
            selectElement.value = hackedSelectedValue
        };
    }
}


function TabPanShow(dEle) {
    pnode = document.getElementById(dEle).parentNode;

    for (var x = 0; x < pnode.childNodes.length; x++) {
        if (pnode.childNodes[x].className == "TabbedPanelsContentGroup") {
            pnode.childNodes[x].style.display = 'none';
            document.getElementById(pnode.childNodes[x].id + '_selector').style.backgroundColor = 'grey';
        }
    }
    document.getElementById(dEle).style.display = 'block';
    document.getElementById(dEle + '_selector').style.backgroundColor = 'white';

    CallAllHandlers();


}

function removeAllChilds(cell) {
    if (cell.hasChildNodes()) {
        while (cell.childNodes.length >= 1) {
            cell.removeChild(cell.firstChild);
        }
    }
}


function ChangeRowStyle(rowIdx) {
    var className = '';
    if (rowIdx % 2 == 0) {
        className = 'hightlight';
    }
    return className;
}


function CallAllHandlers() {
    for (var x = 0; x < cEventHandlers; x++) {
        EventHandlers[x]();
    }

}


window.onload = function () {

    CallAllHandlers();


}



function base64_decode(data) {
    // http://kevin.vanzonneveld.net
    // +   original by: Tyler Akins (http://rumkin.com)
    // +   improved by: Thunder.m
    // +      input by: Aman Gupta
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Pellentesque Malesuada
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: utf8_decode
    // *     example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
    // *     returns 1: 'Kevin van Zonneveld'

    // mozilla has this native
    // - but breaks in 2.0.0.12!
    //if (typeof this.window['btoa'] == 'function') {
    //    return btoa(data);
    //}

    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        dec = "",
        tmp_arr = [];

    if (!data) {
        return data;
    }

    data += '';

    do { // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));

        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

        o1 = bits >> 16 & 0xff;
        o2 = bits >> 8 & 0xff;
        o3 = bits & 0xff;

        if (h3 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1);
        } else if (h4 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1, o2);
        } else {
            tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
        }
    } while (i < data.length);

    dec = tmp_arr.join('');
    dec = this.utf8_decode(dec);

    return dec;
}


function utf8_decode(str_data) {
    // http://kevin.vanzonneveld.net
    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +      input by: Aman Gupta
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Norman "zEh" Fuchs
    // +   bugfixed by: hitwork
    // +   bugfixed by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: utf8_decode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'

    var tmp_arr = [],
        i = 0,
        ac = 0,
        c1 = 0,
        c2 = 0,
        c3 = 0;

    str_data += '';

    while (i < str_data.length) {
        c1 = str_data.charCodeAt(i);
        if (c1 < 128) {
            tmp_arr[ac++] = String.fromCharCode(c1);
            i++;
        } else if (c1 > 191 && c1 < 224) {
            c2 = str_data.charCodeAt(i + 1);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
            i += 2;
        } else {
            c2 = str_data.charCodeAt(i + 1);
            c3 = str_data.charCodeAt(i + 2);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }
    }

    return tmp_arr.join('');
}


function base64_encode(data) {
    // http://kevin.vanzonneveld.net
    // +   original by: Tyler Akins (http://rumkin.com)
    // +   improved by: Bayron Guevara
    // +   improved by: Thunder.m
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Pellentesque Malesuada
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: utf8_encode
    // *     example 1: base64_encode('Kevin van Zonneveld');
    // *     returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='

    // mozilla has this native
    // - but breaks in 2.0.0.12!
    //if (typeof this.window['atob'] == 'function') {
    //    return atob(data);
    //}

    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        enc = "",
        tmp_arr = [];

    if (!data) {
        return data;
    }

    data = this.utf8_encode(data + '');

    do { // pack three octets into four hexets
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1 << 16 | o2 << 8 | o3;

        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;

        // use hexets to index into b64, and append result to encoded string
        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);

    enc = tmp_arr.join('');

    switch (data.length % 3) {
        case 1:
            enc = enc.slice(0, -2) + '==';
            break;
        case 2:
            enc = enc.slice(0, -1) + '=';
            break;
    }

    return enc;
}



function utf8_encode(argString) {
    // http://kevin.vanzonneveld.net
    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: sowberry
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman
    // +   improved by: Yves Sucaet
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Ulrich
    // *     example 1: utf8_encode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'

    var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");

    var utftext = "",
        start, end,
        stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.slice(start, end);
            }
            utftext += enc;
            start = end = n + 1;
        }
    }

    if (end > start) {
        utftext += string.slice(start, stringl);
    }

    return utftext;
}



function appendOptionLast(element, label, value) {
    var elOptNew = document.createElement('option');
    elOptNew.text = label;
    elOptNew.value = value;
    var elSel = element;

    try {
        elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
    } catch (ex) {
        elSel.add(elOptNew); // IE only
    }

}

function openTextHelperClose() {



}

function openTextHelper(element, subelement) {
    if (element.style.position == "fixed") {
        element.style.position = "relative";
        element.style.height = "50px";
        subelement.style.position = "absolute";
        element.style.zIndex -= 50;
        subelement.style.zIndex -= 50;
    } else {
        element.style.position = "fixed";
        subelement.style.position = "fixed";
        element.style.top = "0px";
        element.style.left = "0px";
        element.style.height = "800px";
        element.style.zIndex += 50;
        subelement.style.zIndex += 50;
    }

    subelement.style.top = "1px";
    subelement.style.right = "1px";
}


if (window.webkitNotifications) {

    function requestingPopupPermission(callback) {
        window.webkitNotifications.requestPermission(callback);
    }

    function showPopup() {
	
        if (window.webkitNotifications.checkPermission() > 0) {
            requestingPopupPermission(showPopup);
        } else {
            var thumb = null;
            var title = "Fu";
            var body = "Fu";


            var popup = window.webkitNotifications.createNotification(thumb, title, body);

            //Show the popup
            popup.show();

            //set timeout to hide it
            setTimeout(function () {
                popup.cancel();
            }, '10000');

        }
    }

} 



function jsPrint(jsonData) {
	
	printCanvas=window.open('','');
	d=printCanvas.document;
	
	d.write('<head></head>');
	headHTML = d.getElementsByTagName('head')[0].innerHTML;
	headHTML    += '<link type="text/css" rel="stylesheet" href="'+DOCUMENTROOT+'/Framework/Extensions/wGui/printer.css">';
	d.getElementsByTagName('head')[0].innerHTML = headHTML;

	d.write("<table border='1'>");
	for (i=0;i<jsonData.length;i++) {
		d.write("<tr>");
		for (j=0;j<jsonData[i].length;j++) {
			d.write("<td>");
			printCanvas.document.write(jsonData[i][j]);
			d.write("</td>");
		}
		d.write("</tr>");
	}
	d.write("</table>");
	printCanvas.print();
}

function jsCsv(jsonData) {
	
	a="";
	for (i=0;i<jsonData.length;i++) {
		for (j=0;j<jsonData[i].length;j++) {
			a+=jsonData[i][j];
			a+=";";
		}
		a+="\n";
	}
	
	
	window.open("data:text/plain;content-disposition:attachment,"+escape(a));;
}

function ElementCenter(element) {
			element = $(element);
			var limitX=0;
			var limitY=0;
			var elementDims = element.getDimensions();
			var viewPort = document.viewport.getDimensions();
			var offsets = document.viewport.getScrollOffsets();
			var centerX = viewPort.width / 2 + offsets.left - elementDims.width / 2;
			var centerY = viewPort.height / 2 + offsets.top - elementDims.height / 2;
			if ( limitX && centerX < limitX )
			{
				centerX = parseInt(limitX);
			}
			if ( limitY && centerY < limitY )
			{
				centerY = parseInt(limitY);
			}
			
			element.setStyle( { position: 'absolute', top: Math.floor(centerY) + 'px', left: Math.floor(centerX) + 'px' } );
			
			return element;			
		}
	

var defaultWaitMilliSecs=500;
var autoCompleteWaitLock=null;

function delayedKeyUp(sourceObj,sourceId) {
	
	if (sourceObj.autoCompleteWaitLock!=null)
		clearTimeout(sourceObj.autoCompleteWaitLock);
	sourceObj.autoCompleteWaitLock=setTimeout(function() {
		console.log("Launching onchange on "+sourceId);
		$(sourceId).simulate("custom");

	},defaultWaitMilliSecs);

}

function autoCompleteShowOptions(opts,id,targetid,targettext) {

		
		
			var o="<ul style='margin-left:20px' >";
			c=0;
			if (opts==null)
				return;
			for(i in opts) {
				o+="<li style='padding:3px'><a tabindex="+c+" style='cursor:pointer' onclick=\"autoCompleteSelect('"+id+"','"+targetid+"','"+i+"')\">"+opts[i]+"</a></li>";
				c++;
			}
			o+="</ul>";
			$(id).update(o);
		


}

function autoCompleteShowOpts(opts,id,targetid,targettext) {

			var o="<ul style='margin-left:20px'>";
			c=0;
			if (opts!=null)  
				for(i=0;i<opts.length;i++) {
					o+="<li style='padding:3px'><a tabindex="+c+" style='cursor:pointer' onclick=\"autoCompleteSelect('"+id+"','"+targetid+"','"+opts[i].id+"')\">"+opts[i].label+"</a></li>";
					c++;
				}
			o+="</ul>";
			$(id).update(o);


}

function autoCompleteSelect(divid,targetid,selectedvalue) {
		if (selectedvalue>0) {
			$(targetid).value=selectedvalue;
			$(targetid).simulate("change");
		}
		$(divid).update("");

}


function changeComboModel(element,jdata) {
		var select = document.getElementById(element);
		select.options.length = 0; // clear out existing items
		for (var i in jdata) {
			select.options.add(new Option(jdata[i],i));
		}

}



/**
 * Event.simulate(@element, eventName[, options]) -> Element
 * 
 * - @element: element to fire event on
 * - eventName: name of event to fire (only MouseEvents and HTMLEvents interfaces are supported)
 * - options: optional object to fine-tune event properties - pointerX, pointerY, ctrlKey, etc.
 *
 *    $('foo').simulate('click'); // => fires "click" event on an element with id=foo
 *
 **/
(function(){
  
  var eventMatchers = {
    'HTMLEvents': /^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll|custom)$/,
    'MouseEvents': /^(?:click|mouse(?:down|up|over|move|out))$/
  }
  var defaultOptions = {
    pointerX: 0,
    pointerY: 0,
    button: 0,
    ctrlKey: false,
    altKey: false,
    shiftKey: false,
    metaKey: false,
    bubbles: true,
    cancelable: true
  }
  
  Event.simulate = function(element, eventName) {
    var options = Object.extend(defaultOptions, arguments[2] || { });
    var oEvent, eventType = null;
    
    element = $(element);
    
    for (var name in eventMatchers) {
      if (eventMatchers[name].test(eventName)) { eventType = name; break; }
    }

    if (!eventType)
      throw new SyntaxError('Only HTMLEvents and MouseEvents interfaces are supported');

    if (document.createEvent) {
      oEvent = document.createEvent(eventType);
      if (eventType == 'HTMLEvents') {
        oEvent.initEvent(eventName, options.bubbles, options.cancelable);
      }
      else {
        oEvent.initMouseEvent(eventName, options.bubbles, options.cancelable, document.defaultView, 
          options.button, options.pointerX, options.pointerY, options.pointerX, options.pointerY,
          options.ctrlKey, options.altKey, options.shiftKey, options.metaKey, options.button, element);
      }
      element.dispatchEvent(oEvent);
    }
    else {
      options.clientX = options.pointerX;
      options.clientY = options.pointerY;
      oEvent = Object.extend(document.createEventObject(), options);
      element.fireEvent('on' + eventName, oEvent);
    }
    return element;
  }
  
  Element.addMethods({ simulate: Event.simulate });
})()