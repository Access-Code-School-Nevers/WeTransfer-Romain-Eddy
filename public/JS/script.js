"use strict";

  var transferProgress = document.getElementById('overlay-transfert');


if(document.getElementById('form-transfer')){ // début test #main-form

    var form = document.getElementById('form-transfer');
    var dropArea = document.getElementById('drop-zone');
    var dropShow = document.getElementById('drop-show');
    var droppedFiles;
    var sendButton = document.getElementById("form-transfer");
    sendButton.addEventListener("submit", formValidated);

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

function formValidated() {

    transferProgress.style.display = "flex";
    var myData = new FormData(form);
    if(droppedFiles){
        console.log(droppedFiles);
        for (var i = 0; i < droppedFiles.length; i++) {
          myData.append("file" + i, droppedFiles[i]);
        }
    }
    for(var entryForm of myData.entries()){
        // console.log(entryForm[0], entryForm[1]);
    }

    var normalFiles = document.getElementById('televerser').files;
    // console.log(normalFiles);
    var requestObj = new XMLHttpRequest();

    requestObj.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("message-transfer").innerHTML = '<p style="margin-top: 5px;">Transfert réussi!</p><i class="fas fa-check-square" style="color: #00c100;"></i>';

      var tmp = JSON.parse(this.responseText); // Parse json to access variables
      console.log(tmp.link);
    }
 };

    requestObj.open('post', form.action);
    requestObj.send(myData);

    transferProgress.classList.remove = "hidden";
} // fin fn formValidated
function leaveTransferProgress(){
  transferProgress.classList.add = 'hidden';
  transferProgress.style.display = "none";
}
