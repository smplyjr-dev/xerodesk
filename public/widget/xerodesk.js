!function(e){var t={};function r(n){if(t[n])return t[n].exports;var o=t[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(n,o,function(t){return e[t]}.bind(null,o));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="/",r(r.s=34)}({141:function(e,t){},143:function(e,t){},34:function(e,t,r){r(35),r(141),e.exports=r(143)},35:function(e,t,r){!function(){var e="https://xerodesk.cloudx30.com",t=document.querySelector('script[src*="xerodesk.js"]').getAttribute("src").split("?")[1],r=JSON.parse('{"'+t.replace(/&/g,'","').replace(/=/g,'":"')+'"}');if("key"in r==0)throw new Error("The widget key is missing.");!function(){var t=document.createElement("link");t.setAttribute("rel","stylesheet"),t.setAttribute("href","".concat("${BASE_URL}/widget/xerodesk.css","/xerodesk.css")),document.body.appendChild(t);var n=document.createElement("div");n.setAttribute("id","xerodesk-wrapper"),document.body.appendChild(n);var o=document.createElement("div");o.setAttribute("id","xerodesk-content"),n.appendChild(o);var i=document.createElement("iframe");i.setAttribute("src","".concat(e,"/iframe?key=").concat(r.key)),i.setAttribute("height","100%"),i.setAttribute("width","100%"),i.setAttribute("frameborder","0"),o.appendChild(i);var c=document.createElement("div");c.setAttribute("id","xerodesk-toggler"),c.onclick=function(){n.classList.toggle("open"),n.classList.contains("open")?s.src="".concat(e,"/images/widget/toggler-close.svg"):s.src="".concat(e,"/images/widget/toggler-open.svg")},n.appendChild(c);var s=document.createElement("img");s.setAttribute("src","".concat(e,"/images/widget/toggler-").concat("open",".svg")),c.appendChild(s)}()}()}});
//# sourceMappingURL=xerodesk.js.map