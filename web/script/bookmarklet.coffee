baseUrl = 'http://localhost:9000/web/index.html#/add'
url = location.href
title = document.title || url
window.open baseUrl + '?url=' + encodeURIComponent(url) + '&title=' + encodeURIComponent(title), '_blank'
