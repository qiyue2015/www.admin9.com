var reportTpl = '<label for="my-modal" class="btn btn-sm">举报</label>';

// 弹出层
var modalTpl = '<input type="checkbox" id="my-modal" class="modal-toggle"/>\n' +
    '<div class="report-modal">\n' +
    '    <div class="modal-box">\n' +
    '        <label for="my-modal" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>\n' +
    '        <h3 class="font-bold text-lg">举报</h3>\n' +
    '        <p class="py-4">这条内容存在什么问题？</p>\n' +
    '        <div class="radio-list">\n' +
    '            <label class="radio-list__item">\n' +
    '                <input type="radio" name="type" value="1" class="radio"/> 违法及不良信息\n' +
    '            </label>\n' +
    '            <label class="radio-list__item">\n' +
    '                <input type="radio" name="type" value="2" class="radio"/> 内容过期或错误\n' +
    '            </label>\n' +
    '            <label class="radio-list__item">\n' +
    '                <input type="radio" name="type" value="3" class="radio"/> 垃圾广告信息\n' +
    '            </label>\n' +
    '            <label class="radio-list__item">\n' +
    '                <input type="radio" name="type" value="4" class="radio"/> 知识产权侵权\n' +
    '            </label>\n' +
    '            <label class="radio-list__item">\n' +
    '                <input type="radio" name="type" value="4" class="radio"/> 名誉侵权\n' +
    '            </label>\n' +
    '        </div>\n' +
    '        <div class="report-desc">\n' +
    '            <p>为帮助审核人员更加快速处理，请补充违规内容出现位置等详细信息。(选填)</p>\n' +
    '            <label>\n' +
    '                <textarea class="textarea" name="message" placeholder="请补充违规内容出现位置等详细信息"></textarea>\n' +
    '            </label>\n' +
    '        </div>\n' +
    '        <div class="modal-action">\n' +
    '            <label for="my-modal" class="btn">提交举报</label>\n' +
    '        </div>\n' +
    '    </div>\n' +
    '</div>'

var alertTpl = '<div class="report-msg">\n' +
    '            <div>\n' +
    '                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-6 h-6">\n' +
    '                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>\n' +
    '                </svg>\n' +
    '                <span>已收到你的举报信息，我们将在3个工作日内处理，一经核实即刻删除.</span>\n' +
    '            </div>\n' +
    '        </div>'

$(function () {
    $('.report').append(reportTpl);
    $('body').append(modalTpl);
    $('.modal-action>.btn').click(function () {
        $('.report-msg').remove()
        $('.tips').after(alertTpl)
    })
})
