var a,h,u=typeof Map=="function"?new Map:(a=[],h=[],{has:function(e){return a.indexOf(e)>-1},get:function(e){return h[a.indexOf(e)]},set:function(e,o){a.indexOf(e)===-1&&(a.push(e),h.push(o))},delete:function(e){var o=a.indexOf(e);o>-1&&(a.splice(o,1),h.splice(o,1))}}),z=function(e){return new Event(e,{bubbles:!0})};try{new Event("test")}catch{z=function(o){var c=document.createEvent("Event");return c.initEvent(o,!0,!1),c}}function b(e){var o=u.get(e);o&&o.destroy()}function x(e){var o=u.get(e);o&&o.update()}var s=null;typeof window>"u"||typeof window.getComputedStyle!="function"?((s=function(e){return e}).destroy=function(e){return e},s.update=function(e){return e}):((s=function(e,o){return e&&Array.prototype.forEach.call(e.length?e:[e],function(c){return function(t){if(t&&t.nodeName&&t.nodeName==="TEXTAREA"&&!u.has(t)){var d,p=null,w=null,g=null,E=function(){t.clientWidth!==w&&i()},v=function(l){window.removeEventListener("resize",E,!1),t.removeEventListener("input",i,!1),t.removeEventListener("keyup",i,!1),t.removeEventListener("autosize:destroy",v,!1),t.removeEventListener("autosize:update",i,!1),Object.keys(l).forEach(function(r){t.style[r]=l[r]}),u.delete(t)}.bind(t,{height:t.style.height,resize:t.style.resize,overflowY:t.style.overflowY,overflowX:t.style.overflowX,wordWrap:t.style.wordWrap});t.addEventListener("autosize:destroy",v,!1),"onpropertychange"in t&&"oninput"in t&&t.addEventListener("keyup",i,!1),window.addEventListener("resize",E,!1),t.addEventListener("input",i,!1),t.addEventListener("autosize:update",i,!1),t.style.overflowX="hidden",t.style.wordWrap="break-word",u.set(t,{destroy:v,update:i}),(d=window.getComputedStyle(t,null)).resize==="vertical"?t.style.resize="none":d.resize==="both"&&(t.style.resize="horizontal"),p=d.boxSizing==="content-box"?-(parseFloat(d.paddingTop)+parseFloat(d.paddingBottom)):parseFloat(d.borderTopWidth)+parseFloat(d.borderBottomWidth),isNaN(p)&&(p=0),i()}function m(l){var r=t.style.width;t.style.width="0px",t.style.width=r,t.style.overflowY=l}function y(){if(t.scrollHeight!==0){var l=function(n){for(var f=[];n&&n.parentNode&&n.parentNode instanceof Element;)n.parentNode.scrollTop&&f.push({node:n.parentNode,scrollTop:n.parentNode.scrollTop}),n=n.parentNode;return f}(t),r=document.documentElement&&document.documentElement.scrollTop;t.style.height="",t.style.height=t.scrollHeight+p+"px",w=t.clientWidth,l.forEach(function(n){n.node.scrollTop=n.scrollTop}),r&&(document.documentElement.scrollTop=r)}}function i(){y();var l=Math.round(parseFloat(t.style.height)),r=window.getComputedStyle(t,null),n=r.boxSizing==="content-box"?Math.round(parseFloat(r.height)):t.offsetHeight;if(n<l?r.overflowY==="hidden"&&(m("scroll"),y(),n=r.boxSizing==="content-box"?Math.round(parseFloat(window.getComputedStyle(t,null).height)):t.offsetHeight):r.overflowY!=="hidden"&&(m("hidden"),y(),n=r.boxSizing==="content-box"?Math.round(parseFloat(window.getComputedStyle(t,null).height)):t.offsetHeight),g!==n){g=n;var f=z("autosize:resized");try{t.dispatchEvent(f)}catch{}}}}(c)}),e}).destroy=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],b),e},s.update=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],x),e});var L=s;export{L as d};
//# sourceMappingURL=autosize.esm.js.map
