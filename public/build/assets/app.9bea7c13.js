var m={exports:{}};(function(i,d){function c(){var t=document.querySelector("[data-toggle-theme]");(function(e=localStorage.getItem("theme")){localStorage.getItem("theme")&&(document.documentElement.setAttribute("data-theme",e),t&&[...document.querySelectorAll("[data-toggle-theme]")].forEach(a=>{a.classList.add(t.getAttribute("data-act-class"))}))})(),t&&[...document.querySelectorAll("[data-toggle-theme]")].forEach(e=>{e.addEventListener("click",function(){var a=e.getAttribute("data-toggle-theme");if(a){var o=a.split(",");document.documentElement.getAttribute("data-theme")==o[0]?o.length==1?(document.documentElement.removeAttribute("data-theme"),localStorage.removeItem("theme")):(document.documentElement.setAttribute("data-theme",o[1]),localStorage.setItem("theme",o[1])):(document.documentElement.setAttribute("data-theme",o[0]),localStorage.setItem("theme",o[0]))}[...document.querySelectorAll("[data-toggle-theme]")].forEach(s=>{s.classList.toggle(this.getAttribute("data-act-class"))})})})}function n(){(function(t=localStorage.getItem("theme")){if(t!=null&&t!="")if(localStorage.getItem("theme")&&localStorage.getItem("theme")!=""){document.documentElement.setAttribute("data-theme",t);var e=document.querySelector("[data-set-theme='"+t.toString()+"']");e&&([...document.querySelectorAll("[data-set-theme]")].forEach(a=>{a.classList.remove(a.getAttribute("data-act-class"))}),e.getAttribute("data-act-class")&&e.classList.add(e.getAttribute("data-act-class")))}else{var e=document.querySelector("[data-set-theme='']");e.getAttribute("data-act-class")&&e.classList.add(e.getAttribute("data-act-class"))}})(),[...document.querySelectorAll("[data-set-theme]")].forEach(t=>{t.addEventListener("click",function(){document.documentElement.setAttribute("data-theme",this.getAttribute("data-set-theme")),localStorage.setItem("theme",document.documentElement.getAttribute("data-theme")),[...document.querySelectorAll("[data-set-theme]")].forEach(e=>{e.classList.remove(e.getAttribute("data-act-class"))}),t.getAttribute("data-act-class")&&t.classList.add(t.getAttribute("data-act-class"))})})}function l(){(function(t=localStorage.getItem("theme")){if(localStorage.getItem("theme")){document.documentElement.setAttribute("data-theme",t);var e=document.querySelector("select[data-choose-theme] [value='"+t.toString()+"']");e&&[...document.querySelectorAll("select[data-choose-theme] [value='"+t.toString()+"']")].forEach(a=>{a.selected=!0})}})(),document.querySelector("select[data-choose-theme]")&&[...document.querySelectorAll("select[data-choose-theme]")].forEach(t=>{t.addEventListener("change",function(){document.documentElement.setAttribute("data-theme",this.value),localStorage.setItem("theme",document.documentElement.getAttribute("data-theme")),[...document.querySelectorAll("select[data-choose-theme] [value='"+localStorage.getItem("theme")+"']")].forEach(e=>{e.selected=!0})})})}function r(t=!0){t===!0?document.addEventListener("DOMContentLoaded",function(e){c(),l(),n()}):(c(),l(),n())}i.exports={themeChange:r}})(m);m.exports.themeChange();$(function(){var i=$(window).height(),d=$(".navbar-wrap").height(),c=$(".banner-wrapper").height(),n=$(".footer").height(),l=i-(d+c+n)+"px";$(".page-content").attr("style","min-height:"+l),$("input.search-input").bind("input propertychange",function(){$(this).val().length&&$(".clear-btn").show()}),$(".clear-btn").click(function(){$("input.search-input").val(""),$(this).hide()}),$("input.search-input").keypress(function(a){var o=$(this).val();if(a.keyCode===13&&o){var s='<form id="search-form" target="_blank" action="https://www.baidu.com/s" method="get"><input name="si" value="tuge.net" style="display: none;"><input name="ct" value="2097152" style="display: none;"><input type="text" name="wd" value="'+o+'"><input class="submit" type="submit" value="\u641C\u7D22"></form>';$("body").append(s),$("#search-form .submit").click()}});var r="";$(".navbar-center>.menu>li>a").each(function(a){a&&(r+='<a href="'+$(this).attr("href")+'">'+$(this).text()+"</a>")}),$("body").append(`<input type="checkbox" id="my-modal-3" class="modal-toggle" />
<div class="modal pb-72 md:pb-96">
    <div class="modal-box relative">
        <label for="my-modal-3" class="btn btn-sm btn-circle absolute right-2 top-2">\u2715</label>
        <h3 class="text-lg font-bold">\u9891\u9053</h3>
        <div class="grid grid-cols-4 gap-4 mt-4">`+r+`</div>
    </div>
</div>`);var t=$(".block"),e=t.offset().top;$(document).on("scroll",function(){var a=$(document).scrollTop();a>e?t.addClass("fix"):t.removeClass("fix")})});