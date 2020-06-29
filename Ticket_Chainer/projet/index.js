var Inscription = document.getElementById('cdt2')
var connexion = document.getElementById('cdt')
var patate = document.getElementById('poulet')
var all = document.getElementById('all1')
var all2 = document.getElementById('all2')
var banane = document.getElementById('banane1')
var banane2 = document.getElementById('banane2')


connexion.addEventListener('click', function(){
    banane.style.zIndex = "-999999"
    banane2.style.zIndex = "0"
    all.classList.add('but')
    all2.classList.add('rightButton')
    patate.classList.add('leftButton')
});

Inscription.addEventListener('click', function(){
    all.classList.remove('rightButton')
    all.classList.remove('but')
    patate.classList.remove('leftButton')
    banane.style.zIndex = "0"
    banane2.style.zIndex = "-999999"
});





