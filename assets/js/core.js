// MyOMR core JS: small utilities
(function(){
  // GA helper namespace
  window.MyOMR = window.MyOMR || {};
  window.MyOMR.gtagSafe = function(){ return (typeof window.gtag === 'function') ? window.gtag : function(){}; };
})();


