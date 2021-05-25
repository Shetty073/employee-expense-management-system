// files download/view
function downloadBills(urls) {


    urls.forEach(url => {
        let a = document.createElement('a');
        a.setAttribute('href', url);
        a.setAttribute('download', '');
        a.setAttribute('target', '_blank');

        setTimeout(a.click(), 300);
    });
}
