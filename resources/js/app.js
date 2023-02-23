// import './bootstrap';

// 模式切换
import {themeChange} from 'theme-change'

themeChange()

// const div1 = document.getElementsByClassName('block')[0];
// window.onscroll = function () {
//     let distance = div1.offsetTop - document.documentElement.scrollTop
//     if (distance < -300) {
//         div1.setAttribute('style', 'position: fixed; top:80px')
//     } else {
//         div1.setAttribute('style', 'position: static;')
//     }
// }

// 搜索框部份功能
$('input.search-input').bind('input propertychange', function () {
    if ($(this).val().length) {
        $('.clear-btn').show()
    }
})

$('.clear-btn').click(function () {
    $('input.search-input').val('');
    $(this).hide()
})

$('input.search-input').keypress(function (e) {
    var keyword = $(this).val()
    if (e.keyCode === 13 && keyword) {
        var formTpl = '<form id="search-form" action="/search" method="get"><input type="text" name="keyword" value="' + keyword + '"><input class="submit" type="submit" value="搜索"></form>';
        $('body').append(formTpl);
        $('#search-form .submit').click()
    }
});

var links = '';
$('.navbar-center>.menu>li>a').each(function (index) {
    if (index) {
        links += '<a href="' + $(this).attr('href') + '">' + $(this).text() + '</a>';
    }
})
$('body').append('<input type="checkbox" id="my-modal-3" class="modal-toggle" />\n' +
    '<div class="modal pb-72 md:pb-96">\n' +
    '    <div class="modal-box relative">\n' +
    '        <label for="my-modal-3" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>\n' +
    '        <h3 class="text-lg font-bold">更多分类</h3>\n' +
    '        <div class="grid grid-cols-4 gap-4 mt-4">' + links + '</div>\n' +
    '    </div>\n' +
    '</div>');

