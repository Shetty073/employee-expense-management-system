// files download/view
function downloadBills(urls) {
    if(urls.length === 1) {
        // open the file in new window
        // ! TODO: Remove this block later in github version of project
        urls.forEach(url => {
            let a = document.createElement('a');
            a.setAttribute('href', url);
            a.setAttribute('target', '_blank');
            setTimeout(a.click(), 300);
        });
    } else {
        // download all files
        urls.forEach(url => {
            let a = document.createElement('a');
            a.setAttribute('href', url);
            a.setAttribute('download', '');
            a.setAttribute('target', '_blank');

            setTimeout(a.click(), 300);
        });
    }

}
