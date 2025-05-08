let ajaxConfig = {
    ajaxRequester: function (config, uploadFile, pCall, sCall, eCall) {
        let progress = 0
        let interval = setInterval(() => {
            progress += 10;
            pCall(progress)
            if (progress >= 100) {
                clearInterval(interval)
                const windowURL = window.URL || window.webkitURL;
                sCall({
                    data: windowURL.createObjectURL(uploadFile.file)
                })

            }
        }, 300)
    }
}
$("#upload1").uploader({ multiple: true, ajaxConfig: ajaxConfig, autoUpload: false });
$("#upload2").uploader({ multiple: false, ajaxConfig: ajaxConfig, autoUpload: false });

