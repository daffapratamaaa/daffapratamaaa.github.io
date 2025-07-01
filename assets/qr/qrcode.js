let img_qrcode = document.getElementById('img_qrcode');

function generate() {
    let area_text = document.getElementById('area_text');
    console.log(area_text.value);
    if (!area_text.value) {
        // area_text.classList.add('error');
        return
    }
    img_qrcode.innerHTML = '';
    img_qrcode.style.visibility = 'hidden';
    let size = parseInt(document.getElementById('input_size').value);
    if (size <= 0 || size > 1000) {
        return;
    }

    try {
        new QRCode(img_qrcode, {
            text: area_text.value,
            width: size,
            height: size,
            colorDark: document.getElementById('input_fg').value,
            colorLight: document.getElementById('input_bg').value,
            correctLevel: parseInt(document.getElementById('select_level').value)
        });
        setTimeout(() => img_qrcode.style.visibility = 'visible');
        document.querySelector("#tool-container .float-end").style.display = 'flex';
    } catch (e) {
        document.getElementById('area_text').classList.add('error');
        let text_help = document.getElementById('text_help');
        text_help.classList.add('text-error');
        text_help.innerText = '内容过多'
    }
}

function onInputText(e) {
    e.classList.remove('error');
    let text_help = document.getElementById('text_help');
    text_help.classList.remove('text-error');
    text_help.innerText = '字数越多，越不易扫描';
}

// function save() {
//     let img = img_qrcode.getElementsByTagName('img')[0];
//     let url = img.src;
//     let a = document.createElement('a');
//     let event = new MouseEvent('click');
//     a.download = 'qrcode_' + Math.round(new Date() / 1000) + '.png';
//     a.href = url;
//     a.dispatchEvent(event)
// }

function save() {
    let img = img_qrcode.getElementsByTagName('img')[0];
    let canvas = img_qrcode.querySelector('canvas');
    canvas.toBlob(function(blob) {
        let a = document.createElement('a');
        a.download = 'qrcode_' + Math.round(new Date() / 1000) + '.png';
        a.href = URL.createObjectURL(blob);
        a.click();
    }, 'image/png');
}