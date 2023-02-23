var a='<label for="my-modal" class="btn btn-sm">\u4E3E\u62A5</label>',l=`<input type="checkbox" id="my-modal" class="modal-toggle"/>
<div class="report-modal">
    <div class="modal-box">
        <label for="my-modal" class="btn btn-sm btn-circle absolute right-2 top-2">\u2715</label>
        <h3 class="font-bold text-lg">\u4E3E\u62A5</h3>
        <p class="py-4">\u8FD9\u6761\u5185\u5BB9\u5B58\u5728\u4EC0\u4E48\u95EE\u9898\uFF1F</p>
        <div class="radio-list">
            <label class="radio-list__item">
                <input type="radio" name="type" value="1" class="radio"/> \u8FDD\u6CD5\u53CA\u4E0D\u826F\u4FE1\u606F
            </label>
            <label class="radio-list__item">
                <input type="radio" name="type" value="2" class="radio"/> \u5185\u5BB9\u8FC7\u671F\u6216\u9519\u8BEF
            </label>
            <label class="radio-list__item">
                <input type="radio" name="type" value="3" class="radio"/> \u5783\u573E\u5E7F\u544A\u4FE1\u606F
            </label>
            <label class="radio-list__item">
                <input type="radio" name="type" value="4" class="radio"/> \u540D\u8A89\u53CA\u77E5\u8BC6\u4EA7\u6743\u4FB5\u6743
            </label>
        </div>
        <div class="report-desc">
            <p>\u4E3A\u5E2E\u52A9\u5BA1\u6838\u4EBA\u5458\u66F4\u52A0\u5FEB\u901F\u5904\u7406\uFF0C\u8BF7\u8865\u5145\u8FDD\u89C4\u5185\u5BB9\u51FA\u73B0\u4F4D\u7F6E\u7B49\u8BE6\u7EC6\u4FE1\u606F\u3002(\u9009\u586B)</p>
            <label>
                <textarea class="textarea" name="message" placeholder="\u8BF7\u8865\u5145\u8FDD\u89C4\u5185\u5BB9\u51FA\u73B0\u4F4D\u7F6E\u7B49\u8BE6\u7EC6\u4FE1\u606F"></textarea>
            </label>
        </div>
        <div class="modal-action">
            <label for="my-modal" class="btn">\u63D0\u4EA4\u4E3E\u62A5</label>
        </div>
    </div>
</div>`,e=`<div class="report-msg">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>\u5DF2\u6536\u5230\u4F60\u7684\u4E3E\u62A5\u4FE1\u606F\uFF0C\u6211\u4EEC\u5C06\u57283\u4E2A\u5DE5\u4F5C\u65E5\u5185\u5904\u7406\uFF0C\u4E00\u7ECF\u6838\u5B9E\u5373\u523B\u5220\u9664.</span>
            </div>
        </div>`;$(function(){$(".report").append(a),$("body").append(l),$(".modal-action>.btn").click(function(){$(".report-msg").remove(),$(".tips").after(e)})});
