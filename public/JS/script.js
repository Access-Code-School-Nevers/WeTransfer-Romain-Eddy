"use strict";

var transferProgress = document.getElementById('overlay-transfert');
var form = document.getElementById('form-transfer');

if(document.getElementById('form-transfer')){ // début test #main-form
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
    let transferWait = document.getElementById("transfert-wait");
    transferWait.style.display = "flex";
    let transferSuccess = document.getElementById("transfert-success");
    transferSuccess.style.display = "none";
    let leaveButton = document.getElementById("leave-overlay");
    leaveButton.style.display = "none";
    let transferError = document.getElementById("transfert-error");
    transferError.style.display = "none";
    let overlayTransfer = document.getElementById("transfert-in-progress");
    overlayTransfer.style.backgroundColor = "white";
    overlayTransfer.style.height = "50%";
    let dowloadInput = document.getElementById("lien-download");
    let dowloadButton = document.getElementById("link-download");

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
      transferSuccess.style.display = "flex";
      transferWait.style.display = "none";
      transferError.style.display = "none";
      leaveButton.style.display = "block";
      overlayTransfer.style.backgroundColor = "#f3ffa7";
      overlayTransfer.style.height = "50%";
      var tmp = JSON.parse(this.responseText); // Parse json to access variables

      dowloadInput.value = "https://easytransfer.promo-29.codeur.online" + tmp.link;
      dowloadButton.href = tmp.link;
      form.reset();

    } else if (this.readyState == 4 && this.status == 500) {
      transferSuccess.style.display = "none";
      transferWait.style.display = "none";
      transferError.style.display = "flex";
      leaveButton.style.display = "block";
      overlayTransfer.style.height = "27%";
      overlayTransfer.style.backgroundColor = "#ff4b4b";
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
