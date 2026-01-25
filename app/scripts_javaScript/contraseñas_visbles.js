
const password =document.getElementById("password");
const password_2 = document.getElementById("password_2");
const ver_pass = document.getElementById("ver_pass");

ver_pass.addEventListener("click", ()=>{
    if(password.type === "password")
    {
        password.type = "text";
        ver_pass.textContent = "Ocultar";
    }else{
        password.type = "password";
        ver_pass.textContent = "Mostrar";
    }
});