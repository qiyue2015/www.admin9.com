import _ from 'lodash';

window._ = _;

// import './bootstrap';
// import links from './link'
const links = []
let htmlTpl = ''
_.forEach(links, row => {
    let itemTpl = _.map(row.list, (item) => {
        if (!item.qrcode) {
            return '<a href="' + item.url + '">' +
                '   <div class="item">' +
                '      <div class="logo"><img src="http://www.alloyteam.com/nav' + item.logo.url + '" alt="">' + item.name + '</div>' +
                '      <div class="desc">' + item.desc + '</div>' +
                '   </div>' +
                '</a>'
        } else {
            return '<a href="' + item.url + '">' +
                '   <div class="item">' +
                '      <div class="logo"><img src="http://www.alloyteam.com/nav' + item.qrcode + '" alt="">' + item.name + '</div>' +
                '      <div class="desc">' + item.desc + '</div>' +
                '   </div>' +
                '</a>'
        }
    })
    htmlTpl += '<div class="box ' + row.id + '">'
    htmlTpl += '  <div class="hd sub-category">' + row.name + '</div>'
    htmlTpl += '  <div class="bd list">' + itemTpl.join('') + '</div>'
    htmlTpl += '</div>'

    document.getElementById('main').innerHTML = htmlTpl
})
