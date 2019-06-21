document.querySelector(".card-flip").classList.toggle("flip");


Holder.addTheme('gray', {
    bg: '#777',
    fg: 'rgba(255,255,255,.75)',
    font: 'Helvetica',
    fontweight: 'normal'
});

var vglnk = {key: 'aa20b4f1a9ca752cb20f3ab70d55dee7'};
(function(d, t) {
    var s = d.createElement(t);s.type = 'text/javascript';
    s.async = true;s.src = '//cdn.viglink.com/api/vglnk.js';
    var r = d.getElementsByTagName(t)[0];
    r.parentNode.insertBefore(s, r);
}(document, 'script'));

document.getElementById("submit-btn").addEventListener("click", function(event){
    event.preventDefault()
});

const myform = document.getElementById('myform');

myform.addEventListener('submit', function(e){
    e.preventDefault();


    const formData = new FormData(this);

    fetch('contact-form.php',{
        method:'post',
        body: formData
    }).then(function(response){
        return response.text();
    }).then(function (text){
        console.log(text);
    }).catch(function (error) {
        console.error(error);
    })
});


function validation() {
    swal("Good Job!", "Your Email Has Been Sent!", "success");
}
