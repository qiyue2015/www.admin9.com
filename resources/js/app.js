// import './bootstrap';

// 风格切换
import {themeChange} from 'theme-change'

themeChange()

$(function () {
    // 获得顶部底部高度，控制中间部份最小高度
    var _height = $(window).height();
    var _navbarH = $('.navbar-wrap').height();
    var _headerH = $('.banner-wrapper').height();
    var _footerH = $('.footer').height();
    var _pageContentH = _height - (_navbarH + _headerH + _footerH) + 'px';
    $('.page-content').attr('style', 'min-height:' + _pageContentH);

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
            var formTpl = '<form id="search-form" target="_blank" action="https://www.baidu.com/s" method="get"><input name="si" value="tuge.net" style="display: none;"><input name="ct" value="2097152" style="display: none;"><input type="text" name="wd" value="' + keyword + '"><input class="submit" type="submit" value="搜索"></form>';
            $('body').append(formTpl);
            $('#search-form .submit').click()
        }
    });

    // 弹出更多分类
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
        '        <h3 class="text-lg font-bold">频道</h3>\n' +
        '        <div class="grid grid-cols-4 gap-4 mt-4">' + links + '</div>\n' +
        '    </div>\n' +
        '</div>');

    var navBar = $('.block');
    var navToTop = navBar.offset().top;
    $(document).on('scroll', function () {
        var scrollDistance = $(document).scrollTop();
        if (scrollDistance > navToTop) {
            navBar.addClass("fix");
        } else {
            navBar.removeClass("fix");
        }
    })
})
