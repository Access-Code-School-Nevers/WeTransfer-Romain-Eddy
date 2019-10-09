"use strict";

if(document.getElementById('form-transfer')){ // début test #main-form

    var form = document.getElementById('form-transfer');
    var dropArea = document.getElementById('drop-zone');
    var dropShow = document.getElementById('drop-show');
    var droppedFiles;
    document.getElementById('submit').addEventListener("submit", formValidated);

    function formValidated(e) {
        e.preventDefault();

        var myData = new FormData(form);
        if(droppedFiles){
            // console.log(droppedFiles);
        }
        for(var entryForm of myData.entries()){
            // console.log(entryForm);
        }

        var normalFiles = document.getElementById('submit').files;
        // console.log(normalFiles);

        var requestObj = new XMLHttpRequest();

        requestObj.open('post', form.action);
        requestObj.send(myData);


    } // fin fn formValidated

/* ------------------------- écoute des evts drag'n drop ----------------*/

//evt enter
dropArea.ondragenter = function(e){
    e.preventDefault();
    this.classList.add('over');
    // console.log('enter');
}

dropArea.ondragover = function(e){
    e.preventDefault();
    this.classList.add('over');
    // console.log('over');
}

dropArea.ondragleave = function(e){
    e.preventDefault();
    this.classList.remove('over');
    // console.log('enter');
}

dropArea.ondrop = function(e){
    e.preventDefault();
    this.classList.remove('over');

    droppedFiles = e.dataTransfer.files;
    var droppedItem;
    dropShow.innerHTML = "";
    for (var i = 0; i < droppedFiles.length; i++) {
        droppedItem = document.createElement('p');
        droppedItem.className = 'mb-0';
        droppedItem.innerHTML = droppedFiles[i].name + ' (' + droppedFiles[i].size + ' Kb)';
        dropShow.appendChild(droppedItem);
    }

} // fin ondrop



} //fin du test #main-form
