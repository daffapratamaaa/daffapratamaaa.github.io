<script>/*<![CDATA[*/
/**
 * Anti Ad Blocker Code by Fineshop Design
 * Last Updated: 16:42 12/08/2023 IST
 * Copyright: [Deo Kumar, Fineshop Design](https://www.fineshopdesign.com)
 */
(function(){const n=document,o=n.head;var t="pointer-events: none; height: 1px; width: 0; opacity: 0; visibility: hidden; position: fixed; bottom: 0;";const a=n.createElement("div"),s=n.createElement("div"),d=n.createElement("ins");a.id="div-gpt-ad-3061307416813-0",a.style=t,s.className="textads banner-ads banner_ads ad-unit ad-zone ad-space adsbox ads",s.style=t,d.className="adsbygoogle",d.style="display: none;";const i={allowed:null,elements:[a,s,d]};this.checkAdsStatus=function(t){const e=n.body;"function"==typeof t&&("boolean"==typeof i.allowed?t(i):(e.appendChild(a),e.appendChild(s),e.appendChild(d),setTimeout(function(){if(0===a.offsetHeight||0===s.offsetHeight||d.firstElementChild)i.allowed=!1,t(i);else{const e=n.createElement("script");e.src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js",e.async=!0,e.crossOrigin="anonymous",e.onload=function(){i.allowed=!0,t(i)},e.onerror=function(){i.allowed=!1,t(i)},o.appendChild(e)}a.remove(),s.remove(),d.remove()},40)))}}).call(this);

function antiAdBlockerHandler() {
  window.checkAdsStatus(function(ads) {
    if (!ads.allowed) {
      // Ads are Blocked
      console.log("%c[ADS]", "color:#d32f2f;", "Penggunaan AdBlocker terdeteksi");

      const icon = "<svg style='stroke:none;fill:currentColor!important' viewBox='0 0 24 24'><path d='M12.2 9L10.2 7H13C14.1 7 15 7.9 15 9V11.8L13 9.8V9H12.2M23 9V7H19C17.9 7 17 7.9 17 9V11C17 12.1 17.9 13 19 13H21V15H18.2L20.2 17H21C22.1 17 23 16.1 23 15V13C23 11.9 22.1 11 21 11H19V9H23M22.1 21.5L20.8 22.8L14.4 16.4C14.1 16.7 13.6 17 13 17H9V10.9L7 8.9V17H5V13H3V17H1V9C1 7.9 1.9 7 3 7H5.1L1.1 3L2.4 1.7L22.1 21.5M5 9H3V11H5V9M13 14.9L11 12.9V15H13V14.9Z'/></svg>";
      const title = "Penggunaan AdBlocker terdeteksi!";
      const message = "<p>Kami memahami keinginan Anda untuk menghindari iklan, tetapi iklan adalah sumber pendapatan kami.<br/>Silakan matikan adblocker atau izinkan iklan pada situs kami.</p>";

      // Lets create the popup
      const element = document.createElement("div");
      element.className = "popSc";
      element.innerHTML = "<div class='popBo'>" + icon + "<h2>" + title + "</h2><div class='popCo'>" + message + "</div></div>"
      document.body.appendChild(element);
    } else {
      // Ads are allowed;
      console.log("%c[ADS]", "color:#43a047;", "Tidak ada AdBlocker terdeteksi");
    }
  });
  document.removeEventListener("DOMContentLoaded", antiAdBlockerHandler);
};

if (document.readyState === "complete" || document.readyState !== "loading") {
  antiAdBlockerHandler();
} else {
  document.addEventListener("DOMContentLoaded", antiAdBlockerHandler);
}
/*]]>*/</script>
