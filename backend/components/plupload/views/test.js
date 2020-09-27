function wfwef (up, files) {

    let videoSize = 0;
    let checkList = [];
    for(let i in files){
        let file = files[i];
        if(file.type == 'video/mp4'){
            checkList.push(file);
            videoSize++
        }
    }

    var checkVideoDuration = new Promise(function(resolve,reject){
        let checkedFileSize = 0;
        let URL = window.URL || window.webkitURL;
        if(videoSize <= 0){
            resolve();
        }else{
            for (let x in checkList){
                let checkFile = checkList[x];
                let video = document.createElement('video');
                video.preload = 'metadata';
                video.onloadedmetadata = function(){
                    URL.revokeObjectURL(video.src);
                    let duration = video.duration;
                    console.log(duration);
                    if(duration > 3){
                        reject('视频长度过长');
                    }else{
                        checkedFileSize++;
                        if(checkedFileSize == videoSize){
                            resolve();
                        }
                    }
                };
                video.src = URL.createObjectURL(checkFile);
            }
        }
    });

    checkVideoDuration.then(function(){
        console.log('检查完毕');
    }).catch(function(err){
        console.log(err);
    });

}