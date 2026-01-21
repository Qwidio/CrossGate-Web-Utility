function linker(reqstate) {
    switch (reqstate) {
        case 'login':
            window.location.replace('login.php');
            break;
        case 'dashboard':
            window.location.replace('dashboard.php');
            break; 
        case 'topic':
            window.location.replace('topic.php');
            break;
        case 'category':
            window.location.replace('category.php');
            break;
        case 'markout':
            window.location.replace('markout.php');
            break;
        case 'profile':
            window.location.replace('../../profile.php?user=self');
            break;
        case 'forum':
            window.location.replace('../../TS/forum/dashboard.php');
            break;
        case 'index':
            window.location.replace('../../index.php');
            break;
        case 'logout':
            window.location.replace('processes/logout.php');
            break;
        default:
            return;
    }
};

function SetDialog(reqstate) {
    switch (reqstate) {
        case 'add':
            var adds = document.getElementById('add-dialog');
            if (adds) {
                adds.style.display = (adds.style.display === 'none' || adds.style.display === '') ? "flex" : "none";
            }
            break;
        case 'edit':
            var udt = document.getElementById('edit-dialog');
            if (udt) {
                udt.style.display = (udt.style.display === 'none' || udt.style.display === '') ? "flex" : "none";
            }
            break;
        case 'update':
            var udt = document.getElementById('update-dialog');
            if (udt) {
                udt.style.display = (udt.style.display === 'none' || udt.style.display === '') ? "flex" : "none";
            }
            break;
        default:
        return;
    }
};

function confirmElement(reqstate) {
    var ConfirmElems = document.getElementById('confirmElems');
    if (reqstate == true) {
        ConfirmElems.style.display = "flex";
    } else {
        ConfirmElems.style.display = "none";
    }
}

function copy(id) {
  var copyText = document.getElementById(id);
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copyText.value);
  alerter("Copied to clipboard");
} 

var loadFile = function(event) {
    var output = document.getElementById('prev');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src)
    }
};

var loadAFiles = function(event) {
    var output = document.getElementById('previ');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src)
    }
};
var loadFiles = function(event) {
    var output = document.getElementById('prevs');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src)
    }
};

function reloadFile(imgObj) {
    var output = document.getElementById('prevs');
    output.src = imgObj;
    output.onload = function() {
      URL.revokeObjectURL(output.src)
    }
};

function LoadPublishtoArchive(imgObj, ReqstData) {
    var output = document.getElementById('previ');
    output.src = imgObj;
    output.onload = function() {
      URL.revokeObjectURL(output.src)
    }
    const form = document.forms.ARCHIVES;
    const values = ReqstData.dataset;
    Object.keys(values).forEach((key) => {
        if (form[key]) 
            form[key].value = values[key];
    });
};
function LoadPublishs(ReqstData) {
    const form = document.forms.EDITSTUFF;
    const values = ReqstData.dataset;
    Object.keys(values).forEach((key) => {
        if (form[key]) 
            form[key].value = values[key];
    });
};

function LoadBios(ReqstData) {
    const form = document.forms.BIOS;
    const values = ReqstData.dataset;
    Object.keys(values).forEach((key) => {
        if (form[key]) 
            form[key].value = values[key];
    });
};