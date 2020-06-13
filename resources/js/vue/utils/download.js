export const downloadFileData = (filename, data, type='text/csv;charset=utf-8;') => {
    let saveto = filename ? filename : "data.csv"
    
    if (window.navigator && window.navigator.msSaveOrOpenBlob) { // IE variant
        window.navigator.msSaveOrOpenBlob(new Blob([data], { type: type }), saveto);
    } else {
        const url = window.URL.createObjectURL(new Blob([data], { type: type }));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', saveto);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}