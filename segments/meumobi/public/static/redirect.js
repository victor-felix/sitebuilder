function RedirectToMeuMobi(mobileUrl) {
  try {
    var mobileUAs = /(iPhone|iPod|BlackBerry|Android|webOS|Windows CE|IEMobile|Opera Mini|Opera Mobi|HTC|LG-|LGE|SAMSUNG|Samsung|SEC-SGH|Symbian|Nokia|PlayStation|PLAYSTATION|Nintendo DSi)/i
      , isMobile = navigator.userAgent.match(mobileUAs)
			, noRedirectStr = 'no_redirect=true'
			, utmSourceStr = 'utm_source=casaevideo'
      , noRedirect = location.search.indexOf(utmSourceStr) >= 0 || location.search.indexOf(noRedirectStr) >= 0
			         || document.cookie.indexOf(noRedirectStr) >= 0
    ;

    if(!isMobile || document.getElementById("dmRoot")) return;

    if(noRedirect) document.cookie = noRedirectStr;
    else location.replace(mobileUrl);

  } catch(err) {}
}

//RedirectToMeuMobi('http://m.meumobi.com');
