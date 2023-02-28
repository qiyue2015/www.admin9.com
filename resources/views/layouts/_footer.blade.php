<footer class="footer footer-center p-10 bg-base-200 text-base-content rounded">
    <ul class="grid md:grid-flow-col gap-4">
@if(config('site.gdj_beian'))
        <li>广播电视节目制作经营许可证：<a href="http://gdj.beijing.gov.cn/" rel="nofollow noreferrer" target="_blank">{{config('site.gdj_beian')}}</a></li>
@endif
@if(config('site.ip_vpn_beian'))
        <li>{{config('site.ip_vpn_beian')}}</li>
@endif
@if(config('site.beian'))
        <li><a href="http://beian.miit.gov.cn/" rel="nofollow noreferrer" class="ml-2" target="_blank">{{ config('site.beian') }}</a></li>
@endif
@if(config('site.gongan_beian'))
        <li><a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=11010802040184" rel="nofollow noreferrer" target="_blank">{{ config('site.gongan_beian') }}</a></li>
@endif
    </ul>
    <div>
        <p>Copyright © 2023 - All right reserved by {{ config('site.name') }}</p>
        <p>侵权举报：本页面所涉内容为用户发表并上传，相应的法律责任由用户自行承担，本网站仅提供存储服务。</p>
        <p>如存在侵权问题，请权利人与本网站联系删除！</p>
        <p>邮箱: <span class="email-link">{{ config('site.email') }}</span></p>
    </div>
</footer>

