/*! For license information please see main.js.LICENSE.txt */
!function(){"use strict";var e={"./src/sass/main.scss":function(e,t,n){n.r(t)},"./src/img/test.png":function(e,t,n){e.exports=n.p+"img/31d6cfe0d16ae931b73c.png"}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var i=t[r]={exports:{}};return e[r](i,i.exports,n),i.exports}n.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},function(){var e;n.g.importScripts&&(e=n.g.location+"");var t=n.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var r=t.getElementsByTagName("script");r.length&&(e=r[r.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),n.p=e+"../"}();var r={};!function(){n.r(r);n("./src/img/test.png"),n("./src/sass/main.scss");var e=window.location.origin.concat("/wordpress/wp-json/wp/v2/"),t=window.location.pathname,o=t.substring(0,t.length-1);o=o.substring(o.lastIndexOf("/")+1);var i=t.substring(1,t.lastIndexOf("/"));"/"==(i=i.substring(i.indexOf("/")+1,i.lastIndexOf("/")))&&(i="post");document.querySelector(".main-row").getAttribute("id");for(var c=document.getElementsByClassName("volume-link"),s=0;s<c.length;s++)c[s].addEventListener("click",(function(){fetch(e+"volumes/"+this.id+"?_embed&_fields=title,excerpt,featured_media,_links").then((function(e){return e.json()})).then((function(e){document.querySelector(".novel-cover").srcset=e._embedded["wp:featuredmedia"][0].source_url,document.querySelector(".page-title").innerHTML=e.title.rendered,document.querySelector(".novel-info p").innerHTML=e.excerpt.rendered;for(var t=e._links["wp:term"],n=0;n<t.length;n++){var r=e._embedded["wp:term"][n],o=document.getElementById(t[n].taxonomy+"_info_value");if(null!=o){o.innerHTML="";for(var i=0;i<r.length;i++){var c=document.createElement("a");c.innerText=r[i].name,c.href=r[i].link,o.append(c),o.append(document.createElement("br"))}}}console.log(e),window.scrollTo(0,0)}))}));var l=document.getElementById("volume-list").children.length;document.getElementById("volumes-no").innerText="Volumes - ".concat(l)}()}();
//# sourceMappingURL=main.js.map